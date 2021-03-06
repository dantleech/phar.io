<?php

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.github.com/repos/phar-io/phive/releases');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, 'phive release redirector');
curl_setopt($ch, CURLOPT_HTTPHEADER, [ sprintf('Authorization: token %s', $_SERVER['GITHUB_API_TOKEN']) ]);

$releasesRAW = curl_exec($ch);
curl_close($ch);

$releases = json_decode($releasesRAW);

$requestedFile = basename($_SERVER['REQUEST_URI']);
switch($requestedFile) {
    case 'phive.phar': {
        $target = $releases[0]->assets[0]->browser_download_url;
        break;
    }
    case 'phive.phar.asc': {
        $target = $releases[0]->assets[1]->browser_download_url;
        break;
    }
    default: {
        $target = '';
    }
}

if ($target != '') {
    header('Location: ' . $target , 302);
    die('Forwarded to ' . $target);
}

header('Not Found', 404);
die('404');

