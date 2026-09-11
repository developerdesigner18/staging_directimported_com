<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use SimpleXMLElement;

class ImportSoroBlogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'soro:import-blogs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and import or update blog posts from the Soro RSS feed into the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $rssUrl = config('services.soro.rss_url');

        if (empty($rssUrl)) {
            $this->error('Soro RSS URL is not configured in services.soro.rss_url or .env (SORO_RSS_URL).');
            Log::error('ImportSoroBlogs: Soro RSS URL is missing.');
            return Command::FAILURE;
        }

        $this->info("Fetching Soro RSS feed from: {$rssUrl}");

        try {
            $response = Http::withoutVerifying()->timeout(30)->get($rssUrl);

            if (!$response->successful()) {
                $this->error("Failed to fetch RSS feed. HTTP Status Code: {$response->status()}");
                Log::error("ImportSoroBlogs: HTTP request failed with status {$response->status()} for URL {$rssUrl}");
                return Command::FAILURE;
            }

            $xmlBody = $response->body();
            if (empty(trim($xmlBody))) {
                $this->error('RSS feed body is empty.');
                Log::error('ImportSoroBlogs: RSS feed body is empty.');
                return Command::FAILURE;
            }

            $xml = @simplexml_load_string($xmlBody, 'SimpleXMLElement', LIBXML_NOCDATA);

            if ($xml === false) {
                $this->error('Failed to parse RSS XML feed.');
                Log::error('ImportSoroBlogs: XML parsing failed.');
                return Command::FAILURE;
            }

            // Support standard RSS channel items and Atom entries
            $items = [];
            if (isset($xml->channel->item)) {
                $items = $xml->channel->item;
            } elseif (isset($xml->entry)) {
                $items = $xml->entry;
            }

            if (count($items) === 0) {
                $this->warn('No blog posts found in the RSS feed.');
                return Command::SUCCESS;
            }

            $importedCount = 0;
            $updatedCount = 0;

            foreach ($items as $item) {
                $namespaces = $item->getNamespaces(true);

                // Title
                $title = trim((string) $item->title);
                if (empty($title)) {
                    continue;
                }

                // Soro URL / Link
                $soroUrl = '';
                if (isset($item->link)) {
                    $linkAttr = $item->link->attributes();
                    if (isset($linkAttr['href'])) {
                        $soroUrl = trim((string) $linkAttr['href']);
                    } else {
                        $soroUrl = trim((string) $item->link);
                    }
                }

                // GUID
                $guid = '';
                if (isset($item->guid)) {
                    $guid = trim((string) $item->guid);
                } elseif (isset($item->id)) {
                    $guid = trim((string) $item->id);
                }

                if (empty($guid)) {
                    $guid = !empty($soroUrl) ? $soroUrl : md5($title);
                }

                // Published Date
                $pubDateStr = '';
                if (isset($item->pubDate)) {
                    $pubDateStr = (string) $item->pubDate;
                } elseif (isset($item->published)) {
                    $pubDateStr = (string) $item->published;
                } elseif (isset($item->updated)) {
                    $pubDateStr = (string) $item->updated;
                }

                $publishedAt = null;
                if (!empty($pubDateStr)) {
                    try {
                        $publishedAt = Carbon::parse($pubDateStr);
                    } catch (\Exception $e) {
                        $publishedAt = now();
                    }
                }

                // Description / Excerpt
                $description = '';
                if (isset($item->description)) {
                    $description = (string) $item->description;
                } elseif (isset($item->summary)) {
                    $description = (string) $item->summary;
                }

                // Content (Check content:encoded namespace, fallback to description)
                $content = $description;
                if (isset($namespaces['content'])) {
                    $contentNs = $item->children($namespaces['content']);
                    if (isset($contentNs->encoded) && !empty((string) $contentNs->encoded)) {
                        $content = (string) $contentNs->encoded;
                    }
                }

                // Featured Image Extraction
                $featuredImage = null;

                // 1. Check media namespace
                if (isset($namespaces['media'])) {
                    $mediaNs = $item->children($namespaces['media']);
                    if (isset($mediaNs->content)) {
                        $mediaAttr = $mediaNs->content->attributes();
                        if (isset($mediaAttr['url'])) {
                            $featuredImage = (string) $mediaAttr['url'];
                        }
                    } elseif (isset($mediaNs->thumbnail)) {
                        $thumbAttr = $mediaNs->thumbnail->attributes();
                        if (isset($thumbAttr['url'])) {
                            $featuredImage = (string) $thumbAttr['url'];
                        }
                    }
                }

                // 2. Check RSS enclosure
                if (empty($featuredImage) && isset($item->enclosure)) {
                    $encAttr = $item->enclosure->attributes();
                    if (isset($encAttr['url'])) {
                        $type = (string) ($encAttr['type'] ?? '');
                        if (empty($type) || str_contains($type, 'image')) {
                            $featuredImage = (string) $encAttr['url'];
                        }
                    }
                }

                // 3. Fallback: Parse <img> tag from content or description HTML
                if (empty($featuredImage)) {
                    $htmlToScan = !empty($content) ? $content : $description;
                    if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $htmlToScan, $matches)) {
                        $featuredImage = $matches[1];
                    }
                }

                // Clean/Trim text
                $cleanDescription = Str::limit(strip_tags($description), 500);

                // Slug generation
                $baseSlug = Str::slug($title);
                $slug = !empty($baseSlug) ? $baseSlug : Str::slug($guid);

                // Insert or update using soro_guid
                $existingPost = BlogPost::where('soro_guid', $guid)->first();

                BlogPost::updateOrCreate(
                    ['soro_guid' => $guid],
                    [
                        'title' => $title,
                        'slug' => $slug,
                        'description' => $cleanDescription,
                        'content' => $content,
                        'featured_image' => $featuredImage,
                        'soro_url' => $soroUrl,
                        'published_at' => $publishedAt,
                    ]
                );

                if ($existingPost) {
                    $updatedCount++;
                } else {
                    $importedCount++;
                }
            }

            $this->info("Import completed successfully! Total imported: {$importedCount}, Total updated: {$updatedCount}");
            Log::info("ImportSoroBlogs: Successfully imported {$importedCount} posts and updated {$updatedCount} posts.");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('An error occurred during RSS import: ' . $e->getMessage());
            Log::error('ImportSoroBlogs Exception: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return Command::FAILURE;
        }
    }
}
