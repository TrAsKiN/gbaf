<?php
session_start();

require __DIR__ . '/database.php';

if (!isset($_SESSION['isConnected'])
    && !preg_match('/(login|signup|lost-password|mentions-legales|contact)/', $_GET['action'])
) {
    redirect('/user.php?action=login');
}

function redirect(string $url)
{
    header('Location: ' . $url);
    header('HTTP/1.1 301 Moved Permanently');
    exit;
}

function notFound()
{
    header('HTTP/1.1 404 Not Found');
    exit;
}

function addFlash(string $message)
{
    $_SESSION['flashMessages'][] = $message;
}
