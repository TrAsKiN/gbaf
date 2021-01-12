<?php
namespace GBAF;

class Template
{
    public $title = 'Groupement Banque-Assurance Français';
    protected $output = '';
    protected $before = '';

    public function __construct(?string $title = null)
    {
        $db = new Database();
        $this->output = file_get_contents(App::TEMPLATES_DIRECTORY . '/main.html');

        if ($title) {
            $this->output = preg_replace('/({TITLE})/', $title, $this->output);
        } else {
            $this->output = preg_replace('/({TITLE})/', $this->title, $this->output);
        }

        /**
         * Displays navigation
         */
        $navOutput = file_get_contents(App::TEMPLATES_DIRECTORY . '/nav.html');

        if (isset($_SESSION['isConnected']) && $_SESSION['isConnected']) {
            $userTemplate = file_get_contents(App::TEMPLATES_DIRECTORY . '/user.html');
            $user = $db->getUserByUsername($_SESSION['username']);
            if (!$user) {
                App::addFlash("Erreur avec les informations utilisateur !");
                App::redirect('/logout');
            }
            $userTemplate = preg_replace('/({LASTNAME})/', $user['lastname'], $userTemplate);
            $userTemplate = preg_replace('/({FIRSTNAME})/', $user['firstname'], $userTemplate);
            $navOutput = preg_replace('/({USER})/', $userTemplate, $navOutput);
        }

        $this->output = preg_replace('/({NAV})/', $navOutput, $this->output);
    }

    public function send(): void
    {
        /**
         * Displays debug
         */
        $this->before = ob_get_contents();
        ob_clean();
        if (!empty($this->before)) {
            $this->output = preg_replace(
                '/({DEBUG})/',
                '<pre class="debug">' . $this->before . '</pre>', $this->output
            );
        }

        /**
         * Displays flash messages
         */
        if (isset($_SESSION['flashMessages']) && !empty($_SESSION['flashMessages'])) {
            $flashMessages = '';
            while($message = array_shift($_SESSION['flashMessages'])) {
                $flashOutput = file_get_contents(App::TEMPLATES_DIRECTORY . '/flash.html');
                $flashMessages .= preg_replace('/({MESSAGE})/', $message, $flashOutput);
            }
            $this->output = preg_replace('/({FLASH})/', $flashMessages, $this->output);
        }

        /**
         * Remove all empty template variables
         */
        $this->output = preg_replace('/({\w*})/', '', $this->output);

        print_r($this->output);
        
        if (ob_get_length()) {
            ob_end_flush();
        }
        exit;
    }
}
