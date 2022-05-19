<?php

namespace bemibbs\http;

use bemibbs\Application;
use bemibbs\Exception;

class Router
{
    /**
     * 请求类
     * @var Request
     */
    public Request $request;
    /**
     * 响应类
     * @var Response
     */
    public Response $response;
    /**
     * 所有路由
     * @var array
     */
    protected array $routes = [];

    /**
     * Route 构造方法
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * POST请求方式
     * @param $path
     * @param $callback
     * @return void
     */
    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    /**
     * POST请求方式
     * @param $path
     * @param $callback
     * @return void
     */
    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    /**
     * PUT请求方式
     * @param $path
     * @param $callback
     * @return void
     */
    public function put($path, $callback)
    {
        $this->routes['put'][$path] = $callback;
    }

    /**
     * 所有请求方式
     * @param $path
     * @param $callback
     * @return void
     */
    public function all($path, $callback)
    {
        $this->routes['*'][$path] = $callback;
    }

    /**
     * 自定义请求方式
     * @param $method
     * @param $path
     * @param $callback
     * @return void
     */
    public function method($method, $path, $callback){
        $this->routes[$method][$path] = $callback;
    }

    /**
     * 普通路由
     * @return void
     */
    public function resolve()
    {
        $path = $this->request->getPath();
        $this->resource_path($path);
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;
        if ($callback === false) {
            $this->response->setStatusCode(404);
            Application::$app->view->show("404");
            return "";
        }
        if (is_string($callback)) {
            Application::$app->view->show($callback);
            return "";
        }
        if (is_array($callback)) {
            $path = Application::$ROOT_DIR . '/' . $callback[0] . ".php";
            if (file_exists($path)) {
                Application::$app->controller = new $callback[0]();
            } else {
                Exception::throw("控制器不存在！！！");
            }
            $callback[0] = Application::$app->controller;
        }
        return call_user_func($callback, $this->request);
    }

    /**
     * 渲染静态资源
     * @param $path
     * @return void
     */
    public function resource_path($path)
    {
        // 定义获取静态路由名
        $static_resource_router_name_arr = array(
            "router"  =>  "static_resource",
        );
        // 定义获取静态文件路径
        $static_resource_file_path_arr = array(
            "router"  =>  "path",
        );
        // 静态路由名
        $static_resource_router_name = Application::$app->config->get($static_resource_router_name_arr);
        // 静态文件路径
        $static_resource_file_path = Application::$app->config->get($static_resource_file_path_arr);
        //分割请求的路由
        $path_arr = explode('/',trim($path, '/'));
        // 是否为静态路由
        $isStatic = $path_arr[0] === $static_resource_router_name;
        if ($isStatic) {
            // 静态文件路径
            $resource_file_path = $static_resource_file_path . trim(str_replace($path_arr[0], "", $path), '/');
            if (file_exists($resource_file_path)) {
                include_once $resource_file_path;
            } else {
                Application::$app->response->setStatusCode(404);
                Application::$app->view->show("404");
            }
            exit;
        }
    }
}