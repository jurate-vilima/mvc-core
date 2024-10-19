<?php 
namespace JurateVilima\MvcFramework;

class View {
    public string $curPageTitle = '';
    public array $pageTitles = [];

    public function __construct() {
        $this->pageTitles = getPageTitles();
    }

    public function renderPage($view, $params = [], $layout = 'main') {
        $this->curPageTitle = $this->pageTitles[$view];
        var_dump($this->curPageTitle);
        $content = $this->getView($view, $params);
        $layoutPath = Application::$ROOT_DIR . "/views/layouts/$layout.php";

        if(file_exists($layoutPath))
            include_once $layoutPath;
        else
            throw new \Exception("No such layout");
    }

    protected function getView($view, $params = []) {
        $viewPath = Application::$ROOT_DIR . "/views/$view.php";

        if (file_exists($viewPath)) {
            ob_start();
            include $viewPath;
            return ob_get_clean();
        } else {
            throw new \Exception("No such view");
        }
    }
}
