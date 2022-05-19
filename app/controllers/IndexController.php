<?php

namespace app\controllers;

use bemibbs\Controller;

/**
 * @author Lingqi <3615331065@qq.com>
 * @time 2022-05-18 17:15
 */
class IndexController extends Controller
{
    public function index()
    {
        $this->view->assign("hhhs", "<link rel='stylesheet' type='text/css' href='ssssss.css'>");
        $this->render('index');
    }
}