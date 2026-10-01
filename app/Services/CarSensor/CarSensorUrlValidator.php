<?php

namespace App\Services\CarSensor;

class CarSensorUrlValidator
{
    /**
     * Resolved public IP per host, so a listing's many images on the same host are resolved once.
     *
     * @var array<string, string>
     */
    private array $resolved = [];

    /**
     * Validate any public listing URL and return structured details.
     *
     * @return array{url: string, host: string, source_id: string, is_carsensor: bool}
     * @throws \InvalidArgumentException
     */
    public function validate(string $url): array
    {
        $url = trim($url);

        if (empty($url)) {
            throw new \InvalidArgumentException('Please enter a listing URL.');
        }

        if (!preg_match('/^https?:\/\//i', $url)) {
            throw new \InvalidArgumentException('Please enter a valid URL (must start with http:// or https://).');
        }

        $parsed = parse_url($url);
        if (!$parsed || empty($parsed['host'])) {
            throw new \InvalidArgumentException('Please enter a valid URL.');
        }

        // SSRF guard — scheme, credentials and every resolved address must be public
        $target = $this->assertPublicUrl($url);
        $host   = $target['host'];

        // Generate a source ID — prefer CarSensor listing ID, otherwise hash the URL
        $isCarsensor = in_array($host, ['www.carsensor.net', 'carsensor.net'], true);
        $sourceId    = null;

        if ($isCarsensor) {
            $sourceId = $this->extractCarSensorId($parsed['path'] ?? '');
        }

        if (!$sourceId) {
            // Generic: use a short hash of the URL as the listing reference
            $sourceId = strtoupper(substr(md5($url), 0, 12));
        }

        return [
            'url'          => $url,
            'host'         => $host,
            'source_id'    => $sourceId,
            'is_carsensor' => $isCarsensor,
        ];
    }

    /**
     * Ensure a URL only reaches the public internet (SSRF protection).
     * Used for the listing page, every redirect hop and every image.
     *
     * @return array{host: string, port: int, ip: string}
     * @throws \InvalidArgumentException
     */
    public function assertPublicUrl(string $url): array
    {
        $parts  = parse_url($url);
        $scheme = strtolower($parts['scheme'] ?? '');

        if (!$parts || !in_array($scheme, ['http', 'https'], true) || empty($parts['host'])) {
            throw new \InvalidArgumentException('Only public http:// or https:// URLs are permitted.');
        }

        if (isset($parts['user']) || isset($parts['pass'])) {
            throw new \InvalidArgumentException('URLs containing a username or password are not permitted.');
        }

        $host = strtolower(trim($parts['host'], '[]'));
        $port = (int) ($parts['port'] ?? ($scheme === 'https' ? 443 : 80));

        $this->resolved[$host] ??= $this->resolvePublicIp($host);

        return ['host' => $host, 'port' => $port, 'ip' => $this->resolved[$host]];
    }

    /**
     * HTTP client options for a checked target: connect only to the verified IP
     * (prevents DNS rebinding) and re-check every redirect hop.
     */
    public function requestOptions(array $target): array
    {
        $ip = str_contains($target['ip'], ':') ? "[{$target['ip']}]" : $target['ip'];

        return [
            'allow_redirects' => [
                'max'         => 5,
                'protocols'   => ['http', 'https'],
                'on_redirect' => function ($request, $response, $uri) {
                    $this->assertPublicUrl((string) $uri);
                },
            ],
            'curl' => [
                CURLOPT_RESOLVE => ["{$target['host']}:{$target['port']}:{$ip}"],
            ],
        ];
    }

    /**
     * True only for globally routable addresses: excludes private, loopback, link-local
     * (incl. 169.254.169.254 cloud metadata), carrier-grade NAT, reserved and IPv6 internal ranges.
     */
    public function isPublicIp(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_GLOBAL_RANGE) !== false;
    }

    /**
     * Extract CarSensor listing ID from URL path.
     * e.g. /usedcar/detail/AU7320809064/index.html -> AU7320809064
     */
    public function extractCarSensorId(string $path): ?string
    {
        if (preg_match('#/usedcar/detail/([A-Z0-9]{6,20})/#i', $path, $m)) {
            return strtoupper($m[1]);
        }
        if (preg_match('#/detail/([A-Z0-9]{6,20})#i', $path, $m)) {
            return strtoupper($m[1]);
        }
        return null;
    }

    /**
     * Resolve a host and require every address it resolves to be public.
     * Returns an address to connect to (IPv4 preferred).
     */
    private function resolvePublicIp(string $host): string
    {
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            $ips = [$host];
        } else {
            if ($host === 'localhost' || str_ends_with($host, '.localhost') || !str_contains($host, '.')) {
                throw new \InvalidArgumentException('That URL is not permitted (private/internal address).');
            }

            $ips = [];
            foreach (@dns_get_record($host, DNS_A | DNS_AAAA) ?: [] as $record) {
                $ips[] = $record['ip'] ?? $record['ipv6'] ?? null;
            }
            $ips = array_values(array_filter($ips));

            if (empty($ips)) {
                $ips = @gethostbynamel($host) ?: [];
            }

            if (empty($ips)) {
                throw new \InvalidArgumentException('The website address could not be found. Please check the URL.');
            }
        }

        foreach ($ips as $ip) {
            if (!$this->isPublicIp($ip)) {
                throw new \InvalidArgumentException('That URL resolves to a private network address and is not permitted.');
            }
        }

        $ipv4 = array_values(array_filter($ips, fn ($ip) => filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)));

        return $ipv4[0] ?? $ips[0];
    }
}
