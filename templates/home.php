<?php
$output = file_get_contents(dirname(__DIR__) . '/_main.html');

$title = 'GBAF';
$output = preg_replace('/({TITLE})/', $title, $output);

$body = '<h1>Accueil</h1>';
$output = preg_replace('/({BODY})/', $body, $output);

echo $output;
