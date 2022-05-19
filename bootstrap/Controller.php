<?php

namespace bemibbs;

use bemibbs\view\View;

/**
 * @author Lingqi <3615331065@qq.com>
 * @time 2022-05-16 16:17
 */
class Controller
{
    public string $layout = "index";
    public View $view;
    public function __construct()
    {
        $this->view = Application::$app->view;
    }

    /**
     * 设置父布局
     * @param string $layout 布局文件名
     * @return void
     */
    public function setLayout(string $layout)
    {
        $this->layout = $layout;
    }

    /**
     * 模板注入变量
     * @param string $key 变量名
     * @param string|array $value 变量值
     * @return void
     */
    public function assign(string $key, $value)
    {
        $this->view->assign($key, $value);
    }

    /**
     * 渲染模板
     * @param string $view 模板文件名
     * @return void
     */
    public function render(string $view)
    {
        Application::$app->view->show($view);
    }
}