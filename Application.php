<?php
namespace JurateVilima\MvcCore;

class Application {
    public static Application $app;
    public Router $router;
    private Request $request;
    private Response $response;
    private View $view;
    public Database $db;
    public Session $session;
    public static string $ROOT_DIR;
    public static string $BASE_URL;
    const EVENT_BEFORE_REQUEST = 'beforeRequest';
    const EVENT_AFTER_REQUEST = 'afterRequest';
    private array $eventListeners = [];

    public function __construct($config) {
        self::$app = $this;
        self::$ROOT_DIR = $config['paths']['root'];
        self::$BASE_URL = $config['paths']['base_url'];

        $this->request = new Request();
        $this->response = new Response();
        $this->view = new View();
        $this->router = new Router($this->request, $this->response, $this->view);

        $this->db = new Database($config['db']);
        $this->session = new Session();
    }

    public function run() {
        $this->trigger(self::EVENT_BEFORE_REQUEST);
        echo $this->router->resolve();
    }

    public function on($event, $callback) {
        if(!isset($this->eventListeners[$event])) 
            $this->eventListeners[$event] = [];

        array_push($this->eventListeners[$event], $callback);
    }

    public function trigger($event) {
        if(isset($this->eventListeners[$event])) {
            foreach($this->eventListeners[$event] as $event) {
                call_user_func($event);
            }
        }
    }
}