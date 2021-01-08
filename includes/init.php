<?php
session_start();

require __DIR__ . '/database.php';

function addFlash(string $message) {
    $_SESSION['flashMessages'][] = $message;
}
