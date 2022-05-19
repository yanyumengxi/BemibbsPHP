<?php
$template_dir = dirname(__DIR__) . '/./app/views/';
$cache_dir = dirname(__DIR__) . '/./runtime/';
$addon_dir = dirname(__DIR__) . '/./addons/';
$resource_dir = dirname(__DIR__) . '/./static/';
return [
    // 应用状态：0(关闭), 1(开启), 2(维护)
    'status'    =>  1,
    // 控制器文件后缀(不能包含小数点)
    'controller_suffix'    =>   'Controller',
    // 路由配置
    "router"  => [
        // 静态资源路由.注:不能包含符号且只能为一个字符串。例如，配置为:"static_resource"  =>  "static",;则请求index.css的地址为http://网站地址/static/index.css
        "static_resource"  =>  "static",
        // 静态资源文件存放地址
        "path"  =>  $resource_dir,
    ],
    // 模板配置
    'view'  =>  [
        // 设置模板文件的后缀
        'suffix' => '.html',
        // 设置模板所在的文件夹
        'template_dir' => $template_dir,
        // 设置编译后存放的目录
        'cache_dir' => $cache_dir,
        // 是否需要编译成静态的HTML文件
        'cache_html' => false,
        // 设置编译文件的后缀
        'suffix_cache' => '.html',
        // 设置自动更新时间, 单位秒
        'cache_time' => 10,
        // 是否支持原生PHP代码
        'php_turn' => true,
    ],
    // 扩展插件配置
    'addons'  =>  [
        // 是否开启插件引擎
        'enable'  =>  true,
        // 插件存放目录
        'dir'  =>  $addon_dir,
        // 插件类文件后缀
        'suffix'  =>  '.addon',
        // 插件入口类文件名
        'entrance'  =>  'index',
        // 插件入口类文件后缀
        'entrance_suffix'  =>  '.Addon',
    ],
];