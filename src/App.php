<?php
namespace GBAF;

use GBAF\Controller\HomeController;
use GBAF\Controller\PartnerController;
use GBAF\Controller\UserController;

class App
{
    const TEMPLATES_DIRECTORY = __DIR__ . '/../templates';

    /**
     * @var Template
     */
    private $output;

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
                $this->output = (new HomeController())->home();
                break;
            case '/login':
                $this->output = (new UserController())->login();
                break;
            case '/signup':
                $this->output = (new UserController())->signup();
                break;
            case '/logout':
                $this->output = (new UserController())->logout();
                break;
            case '/profile':
                $this->output = (new UserController())->profile();
                break;
            case '/lost-password':
                $this->output = (new UserController())->lostPassword();
                break;
            case (preg_match('/partner-(\d+)/', $uri, $id) ? true : false):
                $this->output = (new PartnerController())->partner($id[1]);
                break;
            default:
                $this->notFound();
                break;
        }

        /**
         * Sending to the browser if there is content
         */
        $this->output->send();
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
