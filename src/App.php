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
    private $content;

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
            && !preg_match('/(login|signup|lost-password|mentions-legales|contact)/', $uri)
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
                $this->content = (new HomeController())->home();
                break;
            case '/login':
                $this->content = (new UserController())->login();
                break;
            case '/signup':
                $this->content = (new UserController())->signup();
                break;
            case '/logout':
                (new UserController())->logout();
                break;
            case '/profile':
                $this->content = (new UserController())->profile();
                break;
            case '/lost-password':
                $this->content = (new UserController())->lostPassword();
                break;
            case '/mentions-legales':
                $this->content = (new HomeController())->legals();
                break;
            case '/contact':
                $this->content = (new HomeController())->contact();
                break;
            default:
                if (preg_match('/partner-(\d+)/', $uri, $id)) {
                    $this->content = (new PartnerController())->partner($id[1]);
                } else {
                    $this->notFound();
                }
                break;
        }

        /**
         * Sending to the browser if there is content
         */
        $this->content->send();
    }

    public static function redirect(string $url): void
    {
        header('Location: ' . $url);
        header('HTTP/1.1 301 Moved Permanently');
    }

    public static function notFound(): void
    {
        header('HTTP/1.1 404 Not Found');
    }

    public static function addFlash(string $message): void
    {
        $_SESSION['flashMessages'][] = $message;
    }
}
