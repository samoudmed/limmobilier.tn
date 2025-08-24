<?php

function getInternalLinksFromPage($url, $baseUrl) {
    $html = @file_get_contents($url);
    if ($html === false) {
        return []; // Return an empty array if the page cannot be loaded
    }

    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    $baseUrlParts = parse_url($baseUrl);
    $baseHost = $baseUrlParts['host'] ?? '';

    $links = [];
    $excludedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'bmp', 'ico', 'pdf', 'mp4', 'avi', 'mov']; // Ignore these file types

    foreach ($dom->getElementsByTagName('a') as $link) {
        $href = $link->getAttribute('href');
        $parsedUrl = parse_url($href);

        if ($parsedUrl === false || empty($href)) {
            continue; // Skip invalid or empty URLs
        }

        // Convert relative URLs to absolute
        if (empty($parsedUrl['scheme']) && empty($parsedUrl['host'])) {
            $href = ltrim($href, '/');
        }

        // Parse again after conversion
        $parsedUrl = parse_url($href);
        $host = $parsedUrl['host'] ?? '';

        // Allow only internal links (same domain)
        if ($host === '' || $host === $baseHost) {
            // Check file extension and exclude images/videos
            $path = $parsedUrl['path'] ?? '';
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

            if (!in_array($ext, $excludedExtensions)) {
                $links[] = $href;
            }
        } else {
            echo "Excluded external link: $href\n"; // Show excluded external URLs
        }
    }

    return array_unique($links); // Remove duplicates
}

function crawlWebsite($url, $baseUrl, &$visited) {
    if (isset($visited[$url])) {
        return; // Stop if already visited
    }

    $visited[$url] = true; // Mark as visited

    $internalLinks = getInternalLinksFromPage($url, $baseUrl);

    foreach ($internalLinks as $link) {
        if (!isset($visited[$link])) {
            crawlWebsite($link, $baseUrl, $visited);
        }
    }
}

function getUrls($url, $visited = array(), $pdo) {
    // Decode the URL if needed
    $decodedUrl = urldecode($url);
    if ((!strpos($decodedUrl, 'limmobilier.tn/uploads')) && (!strpos($decodedUrl, 'limmobilier.tn/tel:'))) {
        // If the URL has already been visited or does not belong to 'limmobilier.tn', return the visited array
        if (in_array($decodedUrl, $visited) || !preg_match('/^https?:\/\/(?:www\.)?limmobilier\.tn/', $decodedUrl)) {
            return $visited;
        }

        foreach ($pages as $page) {
            $url = $xml->addChild('url');
            $url->addChild('loc', $page);
        }

        // Append the urlset element to the DOM
        $dom->appendChild($urlset);

        // Save the XML sitemap
        $dom->formatOutput = true; // Nicely formats output with indentation and extra space.
        //$sitemapXML = $dom->saveXML();
        $dom->save('sitemap.xml'); // Save to file
        // Output sitemap or status for debugging
        echo "Sitemap has been generated and saved as sitemap.xml";
    }
}

$baseUrl = 'https://limmobilier.tn/';
$visited = [];

// Start crawling from the homepage
crawlWebsite($baseUrl, $baseUrl, $visited);

// Generate the sitemap with all found pages (excluding images & external links)
generateSitemap($baseUrl, array_keys($visited));

echo 'Le fichier sitemap.xml a été généré avec succès.';
?>
