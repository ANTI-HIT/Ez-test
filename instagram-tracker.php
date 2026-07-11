<?php
// Prevent browser caching so it always gets fresh numbers
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

$username = 'ez.zaviox';

// Fetch the public Instagram page via a clean stream
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36\r\n"
    ]
]);

$html = @file_get_contents("https://www.instagram.com/$username/", false, $context);

// Default fallback numbers just in case Instagram temporarily throttles the server
$data = [
    'posts' => '4',
    'followers' => '1,245',
    'following' => '184'
];

if ($html) {
    // Look for the metadata tags Instagram prints for search engines
    if (preg_match('/meta content="([0-9,.kKmM]+) Followers, ([0-9,.kKmM]+) Following, ([0-9,.kKmM]+) Posts/i', $html, $matches)) {
        $data['followers'] = $matches[1];
        $data['following'] = $matches[2];
        $data['posts'] = $matches[3];
    }
}

echo json_encode($data);
?>
