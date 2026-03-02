<?php

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($path === '/') {
    $pages = ['page1', 'page2', 'page3', 'not-allowed'];
    $html = '';

    foreach ($pages as $pageName) {
        $html .= '<a href="'.$pageName.'">'.$pageName.'</a><br />';
    }

    $html .= '<a href="https://spatie.be">Do not index this link</a>';

    header('Content-Type: text/html');
    echo $html;

    return;
}

if ($path === '/robots.txt') {
    header('Content-Type: text/plain');
    echo "User-agent: *\nDisallow: /not-allowed";

    return;
}

$page = ltrim($path, '/');
$html = 'You are on '.$page.'. Here is <a href="/page4">another one</a>';

if ($page === 'page3') {
    $html .= 'This link only appears on page3: <a href="/page5">ooo page 5</a>';
}

header('Content-Type: text/html');
echo $html;
