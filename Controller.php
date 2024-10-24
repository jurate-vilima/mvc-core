<?php
namespace JurateVilima\MvcCore;

abstract class Controller {
    protected View $view;
    protected Request $request;

    public function __construct(View $view, Request $request) {
        $this->view = $view;
        $this->request = $request;
    }

    protected function render(string $view, array $params = [], $layout = 'main'): void {
        // $this->view->title = $this->pageTitles[$view];
        $this->view->renderPage($view, $params, $layout);
    }
}