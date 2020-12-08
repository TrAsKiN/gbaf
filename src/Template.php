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

        $this->generateNav();

        if (!empty($this->before)) {
            $this->output = preg_replace('/({DEBUG})/', '<pre class="debug">' . $this->before . '</pre>', $this->output);
        } else {
            $this->output = preg_replace('/({DEBUG})/', '', $this->output);
        }
    }

    protected function generateNav()
    {
        $navOutput = file_get_contents(App::TEMPLATES_DIRECTORY . '/nav.html');

        if (isset($_SESSION['isConnected']) && $_SESSION['isConnected']) {
            $navOutput = preg_replace('/({USER})/', '<a href="/logout">Déconnexion</a>', $navOutput);
        } else {
            $navOutput = preg_replace('/({USER})/', 'Not connected', $navOutput);
        }

        $this->output = preg_replace('/({NAV})/', $navOutput, $this->output);
    }
}
