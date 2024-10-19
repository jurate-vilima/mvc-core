<?php
namespace JurateVilima\MvcFramework;

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
        echo $this->router->resolve();
    }
}