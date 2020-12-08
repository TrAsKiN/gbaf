<?php
namespace GBAF;

use GBAF\Controller\HomeController;
use GBAF\Controller\PartnerController;
use GBAF\Controller\UserController;

class App
{
    const TEMPLATES_DIRECTORY = __DIR__ . '/../templates';

    /**
     * Main application
     * 
     * @return void
     */
    public function run(): void
    {
        $uri = $_SERVER['REQUEST_URI'];

        session_start();
        ob_start();

        /**
         * If not connected redirect to the login page
         */
        if (
            !isset($_SESSION['isConnected'])
            && !preg_match('/(login|signup|lost-password)/', $uri)
        ) {
            $this->redirect('/login');
        }

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
                (new HomeController())->home();
                break;
            case '/login':
                (new UserController())->login();
                break;
            case '/signup':
                print_r('Inscription');
                break;
            case '/logout':
                (new UserController())->logout();
                break;
            case '/profile':
                print_r('Profil');
                break;
            case '/lost-password':
                print_r('Mot de passe perdu');
                break;
            case '/partners':
                print_r('Partenaires');
                break;
            default:
                if (preg_match('/partner-(\d+)/', $uri, $id)) {
                    (new PartnerController())->partner($id[1]);
                }
                break;
        }

        /**
         * Sending to the browser if there is content
         */
        if (ob_get_length()) {
            ob_end_flush();
            exit;
        }

        /**
         * No route found
         */
        $this->notFound();
    }

    /**
     * Redirect to a new url
     * 
     * @param string $url The new URL
     * @return void
     */
    public static function redirect(string $url): void
    {
        header('Location: ' . $url);
        header('HTTP/1.1 301 Moved Permanently');
        exit;
    }

    /**
     * Return 404 Not Found
     * 
     * @return void
     */
    public static function notFound(): void
    {
        header('HTTP/1.1 404 Not Found');
        exit;
    }
}
