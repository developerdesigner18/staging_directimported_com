<?php

namespace App\Services\CarSensor;

class CarSensorUrlValidator
{
    /**
     * Blocked IP/hostname patterns for SSRF protection.
     * We accept any public web URL — no allowlist restriction on hostname.
     */
    private const BLOCKED_PATTERNS = [
        '/^localhost$/i',
        '/^127\.\d+\.\d+\.\d+$/',
        '/^10\.\d+\.\d+\.\d+$/',
        '/^172\.(1[6-9]|2\d|3[01])\.\d+\.\d+$/',
        '/^192\.168\.\d+\.\d+$/',
        '/^0\.0\.0\.0$/',
        '/^::1$/',
        '/^fc00:/i',
        '/^fe80:/i',
        '/^169\.254\.\d+\.\d+$/',
    ];

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

        $host   = strtolower($parsed['host']);
        $scheme = strtolower($parsed['scheme'] ?? '');

        if (!in_array($scheme, ['http', 'https'], true)) {
            throw new \InvalidArgumentException('Only HTTP/HTTPS URLs are permitted.');
        }

        // SSRF guard — block private/loopback names
        foreach (self::BLOCKED_PATTERNS as $pattern) {
            if (preg_match($pattern, $host)) {
                throw new \InvalidArgumentException('That URL is not permitted (private/internal address).');
            }
        }

        // Resolve hostname and also block internal IPs
        $resolvedIp = @gethostbyname($host);
        if ($resolvedIp && $resolvedIp !== $host) {
            foreach (self::BLOCKED_PATTERNS as $pattern) {
                if (preg_match($pattern, $resolvedIp)) {
                    throw new \InvalidArgumentException(
                        'That URL resolves to a private network address and is not permitted.'
                    );
                }
            }
        }

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
}

