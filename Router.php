<?php
namespace app\core;

use app\core\exceptions\ForbiddenAccess;
use app\core\middlewares\BaseMiddleware;

class Router {
    private Request $request;
    private Response $response;
    private View $view;
    public array $routes = [];
    public $lastInsertedRoute = null;

    public function __construct(Request $request, Response $response, View $view) {
        $this->request = $request;
        $this->response = $response;
        $this->view = $view;
    }

    public function get($path, $callback) {
        $this->routes['get'][$path] = $callback;
        $this->fillLastInsertedRoute('get', $path);

        return $this;
    }

    public function fillLastInsertedRoute($method, $path) {
        $this->lastInsertedRoute = [
            'method' => $method,
            'path' => $path,
        ];
    }

    public function only($roles = []) {
        $method = $this->lastInsertedRoute['method'];
        $path = $this->lastInsertedRoute['path'];

        $this->routes[$method][$path]['middleware'] = $roles;
    }

    public function post($path, $callback) {
        $this->routes['post'][$path] = $callback;
        $this->fillLastInsertedRoute('post', $path);
        
        return $this;
    }

    // Resolves the current request's path and method, then executes the appropriate callback or shows an error.
    public function resolve() {
        $path = $this->request->getPath(); //gets requested path
        $method = strtolower($this->request->getMethod());

        // check if user is authenticated(in $_SESSION exists 'user' key)
        // if not- guest role
        $userRole = Application::$app->session->get('user')['role'] ?? 'guest';

        if($obj = $this->routes[$method][$path] ?? false) {
            
            try {
                // if there are restrictions on access(who is able to get this page)
                if(isset($obj['middleware'])) {

                    // check if current role IS NOT specified in an array of those who are able to get access to this path
                    if(!in_array($userRole, $obj['middleware'])) {
                        $middleware = BaseMiddleware::MAP[$userRole];
                        
                        (new $middleware)->handle(); // here Exception is thrown that user cannot access page
                    }
                }
            } catch(\Exception $e) {
                $this->response->setErrorCode(403);
                $this->view->renderPage('error', ['error_msg' => $e->getMessage()]);
            }

            // if the page is accessible for all OR
            // if the role matches one of the allowed to access page
            $callback = $this->routes[$method][$path];
            $this->handleCallback($callback);
        }
        else {
            // if there's not such address- display an error
            $this->response->setErrorCode(404);
            $this->view->renderPage('error');
        }
    }

    private function handleCallback($callback): void {
        if (is_string($callback)) {
            $this->view->renderPage($callback);
        } elseif (is_array($callback)) {
            // If the callback is an array, create an instance of the class and call the method
            $controller = new $callback[0]($this->view, $this->request);
            $method = $callback[1];

            // Calls the callback given by the first parameter and passes the remaining parameters as arguments.
            // In PHP, non-static methods need to be called on an instance of a class, not on the class itself.
            call_user_func([$controller, $method], $this->request, $this->response);
        } else {
            call_user_func($callback);
        }
    }

}