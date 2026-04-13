<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ConvertImagesToWebp extends Command
{
    protected $signature   = 'images:convert-to-webp';
    protected $description = 'Convert all existing uploaded images to WebP format and update DB records';

    /**
     * Each entry: [ table, column, path, isJson ]
     *  isJson = true means the column holds a JSON array of filenames (bikes.images, services.images)
     */
    private array $targets = [
        ['table' => 'bikes',        'column' => 'images',  'path' => 'uploads/bike_images/',    'isJson' => true],
        ['table' => 'bikes',        'column' => 'banner',  'path' => 'uploads/bike_images/',    'isJson' => false],
        ['table' => 'banners',      'column' => 'image',   'path' => 'uploads/banner_images/',  'isJson' => false],
        ['table' => 'galleries',    'column' => 'image',   'path' => 'uploads/gallery_images/', 'isJson' => false],
        ['table' => 'hero_sliders', 'column' => 'image',   'path' => 'uploads/slider_images/',  'isJson' => false],
        ['table' => 'services',     'column' => 'images',  'path' => 'uploads/service_images/', 'isJson' => true],
    ];

    public function handle(): int
    {
        $manager = new ImageManager(new Driver());

        foreach ($this->targets as $target) {
            $this->info("Processing table [{$target['table']}] column [{$target['column']}]...");

            $rows = DB::table($target['table'])->select('id', $target['column'])->get();

            foreach ($rows as $row) {
                $raw = $row->{$target['column']};

                if (empty($raw)) {
                    continue;
                }

                if ($target['isJson']) {
                    // JSON array of filenames
                    $files = json_decode($raw, true);
                    if (!is_array($files)) {
                        continue;
                    }

                    $updated = [];
                    $changed = false;

                    foreach ($files as $filename) {
                        $newName = $this->convertFile($manager, $filename, $target['path']);
                        $updated[] = $newName;
                        if ($newName !== $filename) {
                            $changed = true;
                        }
                    }

                    if ($changed) {
                        DB::table($target['table'])
                            ->where('id', $row->id)
                            ->update([$target['column'] => json_encode($updated)]);
                        $this->line("  ✓ Row {$row->id}: updated JSON images.");
                    }
                } else {
                    // Single filename
                    $newName = $this->convertFile($manager, $raw, $target['path']);
                    if ($newName !== $raw) {
                        DB::table($target['table'])
                            ->where('id', $row->id)
                            ->update([$target['column'] => $newName]);
                        $this->line("  ✓ Row {$row->id}: {$raw} → {$newName}");
                    }
                }
            }
        }

        $this->info('✅ All images have been converted to WebP successfully!');
        return self::SUCCESS;
    }

    /**
     * Convert a single image file to WebP if it is not already WebP.
     * Returns the (possibly new) filename.
     */
    private function convertFile(ImageManager $manager, string $filename, string $path): string
    {
        // Already WebP – nothing to do
        if (strtolower(pathinfo($filename, PATHINFO_EXTENSION)) === 'webp') {
            return $filename;
        }

        $sourcePath = public_path($path . $filename);

        if (!File::exists($sourcePath)) {
            $this->warn("  ⚠ File not found, skipping: {$sourcePath}");
            return $filename;
        }

        try {
            $newFilename = pathinfo($filename, PATHINFO_FILENAME) . '.webp';
            $destPath    = public_path($path . $newFilename);

            $manager->read($sourcePath)->toWebp(80)->save($destPath);

            // Remove the original non-webp file
            File::delete($sourcePath);

            return $newFilename;
        } catch (\Throwable $e) {
            $this->error("  ✗ Failed to convert {$filename}: " . $e->getMessage());
            return $filename;
        }
    }
}
