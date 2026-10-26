<?php
declare(strict_types=1);

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$uri = urldecode((string)$uri);

// Root bo'lsa -> public/index.php
if ($uri === '/' || $uri === '' || $uri === '/index.php') {
    $targetFile = dirname(__DIR__) . '/public/index.php';
} else {
    $cleanPath = ltrim($uri, '/');
    $candidate = dirname(__DIR__) . '/' . $cleanPath;
    
    if (is_file($candidate) && pathinfo($candidate, PATHINFO_EXTENSION) === 'php') {
        $targetFile = $candidate;
    } elseif (is_dir($candidate) && is_file($candidate . '/index.php')) {
        $targetFile = $candidate . '/index.php';
    } elseif (is_file($candidate . '.php')) {
        $targetFile = $candidate . '.php';
    } else {
        $publicCandidate = dirname(__DIR__) . '/public/' . $cleanPath;
        if (is_file($publicCandidate) && pathinfo($publicCandidate, PATHINFO_EXTENSION) === 'php') {
            $targetFile = $publicCandidate;
        } elseif (is_file($publicCandidate . '.php')) {
            $targetFile = $publicCandidate . '.php';
        } else {
            http_response_code(404);
            require dirname(__DIR__) . '/public/index.php';
            exit;
        }
    }
}

$_SERVER['SCRIPT_FILENAME'] = $targetFile;
$_SERVER['SCRIPT_NAME'] = str_replace(str_replace('\\', '/', dirname(__DIR__)), '', str_replace('\\', '/', $targetFile));
$_SERVER['PHP_SELF'] = $_SERVER['SCRIPT_NAME'];

chdir(dirname($targetFile));
require $targetFile;
