<?php
namespace GBAF;

class Controller
{
    protected function addFlash($message)
    {
        $_SESSION['flashMessages'][] = $message;
    }
}
