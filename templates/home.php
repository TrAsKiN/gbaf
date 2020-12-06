<?php
$before = ob_get_contents();
ob_clean();
$output = file_get_contents(__DIR__ . '/_main.html');

$title = 'GBAF';
$output = preg_replace('/({TITLE})/', $title, $output);

$body = '<h1>Accueil</h1>';
if (!empty($before)) {
    $body .= '<pre class="debug">' . $before . '</pre>';
}
$output = preg_replace('/({BODY})/', $body, $output);

print_r($output);
