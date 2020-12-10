<?php
namespace GBAF;

class Template
{
    public $title = 'Groupement Banque-Assurance Français';
    protected $output = '';
    protected $before = '';

    /**
     * @param string $title
     */
    public function __construct($title = null)
    {
        $this->before = ob_get_contents();
        ob_clean();

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
            $navOutput = preg_replace('/({USER})/', '<a href="/logout">Déconnexion</a>', $navOutput);
        }

        $this->output = preg_replace('/({NAV})/', $navOutput, $this->output);

        /**
         * Displays flash messages
         */
        $flashOutput = file_get_contents(App::TEMPLATES_DIRECTORY . '/flash.html');
        if (isset($_SESSION['flashMessages']) && !empty($_SESSION['flashMessages'])) {
            while($message = array_shift($_SESSION['flashMessages'])) {
                $flashOutput = preg_replace('/({MESSAGE})/', $message, $flashOutput);

                $this->output = preg_replace('/({FLASH})/', $flashOutput, $this->output);
            }
        }
    }

    /**
     * Clear and send to the browser
     * 
     * @return void
     */
    public function send(): void
    {
        /**
         * Displays debug
         */
        if (!empty($this->before)) {
            $this->output = preg_replace('/({DEBUG})/', '<pre class="debug">' . $this->before . '</pre>', $this->output);
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
