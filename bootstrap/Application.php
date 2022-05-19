<?php

namespace bemibbs;

use bemibbs\http\Request;
use bemibbs\http\Response;
use bemibbs\http\Router;
use bemibbs\view\Compile;
use bemibbs\view\View;
use bemibbs\config\config;

class Application
{
    public static string $ROOT_DIR;
    public Router $router;
    public Request $request;
    public Response $response;
    public static Application $app;
    public Controller $controller;
    public string $layout = "index";
    public DB $db;
    public View $view;
    public Config $config;
    public Addons $addons;
    public Exception $exception;
    public string $AppShow;
    public string $controller_in_folder = "app/controllers";
    public function __construct($rootPath)
    {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->exception = new Exception();
        $this->db = new DB();
        $this->config = new Config();
        $this->view = new View();
        $this->addons = new Addons();
    }

    public function run()
    {
        $app_status = $this->config->get("status");
        switch ($app_status) {
            case 0:
                if (empty($this->AppShow)) {
                    $this->AppShow = call_user_func(function (){
                        return "<div style='color: #e30707;background: #4f4f4f;font-size: 16px;font-family: Cambria,,serif;border-radius: 4px;margin: 10px;padding: 10px;letter-spacing: 2px;backdrop-filter: blur(4px)'>The site is closed!!!</div>";
                    }, $this->request);
                }
                echo $this->AppShow;
                break;
            case 1:
                echo $this->router->resolve();
                break;
            case 2:
                if (empty($this->AppShow)) {
                    $this->AppShow = call_user_func(function (){
                        return "<div style='color: #e30707;background: #4f4f4f;font-size: 16px;font-family: Cambria,,serif;border-radius: 4px;margin: 10px;padding: 10px;letter-spacing: 2px;backdrop-filter: blur(4px)'>Website maintenance in progress!!!</div>";
                    }, $this->request);
                }
                echo $this->AppShow;
        }
    }

    /**
     * 设置站点状态为维护状态时显示的内容
     * @param $Maintain
     * @return void
     */
    public function setMaintain($Maintain)
    {
        $this->AppShow = call_user_func($Maintain, $this->request);
    }

    /**
     * 设置站点状态为关闭状态时显示的内容
     * @param $Close
     * @return void
     */
    public function setClose($Close)
    {
        $this->AppShow = call_user_func($Close, $this->request);
    }

    /**
     * @return Controller
     */
    public function getController(): Controller
    {
        return $this->controller;
    }

    /**
     * @param Controller $controller
     */
    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }
}