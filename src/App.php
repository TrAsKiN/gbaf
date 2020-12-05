<?php
namespace GBAF;

use GBAF\Controller\Home;

class App
{
    private $tplDirectory = __DIR__ . '/../templates';

    /**
     * Main application
     * 
     * @return void
     */
    public function run(): void
    {
        $uri = $_SERVER['REQUEST_URI'];

        ob_start();

        /**
         * Remove the trailing slash at the end of the url
         */
        if (!empty($uri) && strlen($uri) > 1 && $uri[-1] === '/') {
            $this->redirect(substr($uri, 0, -1));
        }

        /**
         * Routing
         */
        switch ($uri) {
            case '/':
                (new Home($this->tplDirectory))->action();
                break;
            case '/login':
                print_r('Connexion');
                break;
            case '/logout':
                print_r('Déconnexion');
                break;
            case '/profile':
                print_r('Profil');
                break;
            case '/partners':
                print_r('Partenaires');
                break;
            default:
                // Check $uri for unique partner
                break;
        }

        /**
         * Sending to the browser if there is content
         */
        if (ob_get_length()) {
            ob_end_flush();
        }

        /**
         * No route found
         */
        header('HTTP/1.1 404 Not Found');
        exit;
    }

    /**
     * Redirect to a new url
     * 
     * @param string $url The new URL
     * @return void
     */
    private function redirect($url): void
    {
        header('Location: ' . $url);
        header('HTTP/1.1 301 Moved Permanently');
        exit;
    }
}
