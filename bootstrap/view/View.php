<?php

namespace bemibbs\view;

use bemibbs\Application;

/**
 * @author Lingqi <3615331065@qq.com>
 * @time 2022-05-16 19:25
 */
class View
{
    private array $config = array(
        "view" => array(
            'suffix' => '.html',      // 设置模板文件的后缀
            'template_dir' => 'templates/',    // 设置模板所在的文件夹
            'cache_dir' => 'cache/',    // 设置编译后存放的目录
            'cache_html' => false,    // 是否需要编译成静态的HTML文件
            'suffix_cache' => '.html',    // 设置编译文件的后缀
            'cache_time' => 7200,    //  多长时间自动更新，单位秒
            'php_turn' => true,   // 是否支持原生PHP代码
        )
    );
    private static ?View $instance = null;
    private array $value = array();   // 值栈
    // 编译器
    public string $file;     // 模板文件名，不带路径
    public array $debug = array();   // 调试信息
    private array $controlData = array();

    public function __construct($config = array())
    {

        $this->debug['begin'] = microtime(true);
        $this->config = $config + $this->config;

        $global_config = Application::$app->config->getAll();
        $this->config = array_merge($this->config, $global_config);
        if (!is_dir($this->config['view']['template_dir'])) {
            exit("模板目录不存在！");
        }

        if (!is_dir($this->config['view']['cache_dir'])) {
            mkdir($this->config['view']['cache_dir'], 0770);
        }
        $this->getPath();
        include __DIR__ . '/./Compile.php';
    }

    /**
     *获取绝对路径
     */
    public function getPath()
    {
        $this->config['view']['template_dir'] = strtr(realpath($this->config['view']['template_dir']), '\\', '/') . '/';
        $this->config['view']['cache_dir'] = strtr(realpath($this->config['view']['cache_dir']), '\\', '/') . '/';
    }

    /**
     * 取得模板引擎的实例
     */
    public static function getInstance(): ?View
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 设置模板引擎参数
     * @param $key
     * @param $value
     * @return void
     */
    public function setConfig($key, $value = null)
    {
        if (is_array($key)) {
            $this->config = $key + $this->config['view'];
        } else {
            $this->config['view'][$key] = $value;
        }
    }

    /**
     * 获取当前模板引擎配置,仅供调试使用
     * @param $key
     * @return mixed
     */
    public function getConfig($key = null)
    {
        if ($key) {
            return $this->config['view'][$key];
        } else {
            return $this->config['view'];
        }
    }

    /**
     * 注入单个变量
     */
    public function assign($key, $value)
    {
        $this->value[$key] = $value;
    }

    /**
     * 注入数组变量
     */
    public function assignArray($array)
    {
        if (is_array($array)) {
            foreach ($array as $k => $v) {
                $this->value[$k] = $v;
            }
        }
    }

    /**
     * 获取模板文件完整路径
     */
    public function path(): string
    {
        return $this->config['view']['template_dir'] . $this->file . $this->config['view']['suffix'];
    }

    /**
     * 判断是否开启了缓存
     */
    public function needCache()
    {
        return $this->config['view']['cache_html'];
    }

    /**
     * 是否需要重新生成静态文件
     */
    public function reCache($file): bool
    {
        $flag = true;
        $cacheFile = $this->config['view']['cache_dir'] . md5($file) . $this->config['view']['suffix_cache'];
        if ($this->config['view']['cache_html'] === true) {
            $timeFlag = (time() - @filemtime($cacheFile)) < $this->config['view']['cache_time'];
            if (is_file($cacheFile) && filesize($cacheFile) > 1 && $timeFlag && filemtime($cacheFile) >= filemtime($this->path())) {
                $flag = false;
            }
        }
        return $flag;
    }


    /**
     * 显示模板
     */
    public function show($file)
    {
        $this->file = $file;
        if (!is_file($this->path())) {
            exit('找不到对应的模板！');
        }
        $compileFile = $this->config['view']['cache_dir'] . md5($file) . '.php';
        $cacheFile = $this->config['view']['cache_dir'] . md5($file) . $this->config['view']['suffix_cache'];
        extract($this->value, EXTR_OVERWRITE);
        if ($this->config['view']['cache_html'] === true) { // 开启缓存的处理逻辑
            if ($this->reCache($file) === true) { // 需要更新缓存的处理逻辑
                $this->debug['cached'] = 'false';
                $compileTool = new Compile($this->path(), $compileFile, $this->config['view']);
                if ($this->needCache()) {
                    ob_start();
                } // 打开输出控制缓冲
                if (!is_file($compileFile) || filemtime($compileFile) < filemtime($this->path())) {
                    $compileTool->value = $this->value;
                    $compileTool->compile();
                    include $compileFile;
                } else {
                    include $compileFile;
                }
                if ($this->needCache()) {
                    $message = ob_get_contents(); // 获取输出缓冲中的内容（在include编译文件的时候就有输出了）
                    echo "<pre>";
                    var_dump($message);
                    echo "</pre>";
                    file_put_contents($cacheFile, $message);
                }
            } else {
                readfile($cacheFile);
                $this->debug['cached'] = 'true';
            }
        } else {
            if (!is_file($compileFile) || filemtime($compileFile) < filemtime($this->path())) {
                $compileTool = new Compile($this->path(), $compileFile, $this->config['view']);
                $compileTool->value = $this->value;
                $compileTool->compile();
                include $compileFile;
            } else {
                include $compileFile;
            }
        }
        $this->debug['spend'] = microtime(true) - $this->debug['begin'];
        $this->debug['count'] = count($this->value);
        //$this->debug_info();
    }

    public function debug_info()
    {
        if ($this->config['debug'] === true) {
            echo PHP_EOL, '---------debug info---------', PHP_EOL;
            echo "程序运行日期：", date("Y-m-d H:i:s"), PHP_EOL;
            echo "模板解析耗时：", $this->debug['spend'], '秒', PHP_EOL;
            echo '模板包含标签数目：', $this->debug['count'], PHP_EOL;
            echo '是否使用静态缓存：', $this->debug['cached'], PHP_EOL;
            echo '模板引擎实例参数：', var_dump($this->getConfig());
        }
    }

    /**
     * 清理缓存的HTML文件
     */
    public function clean($path = null)
    {
        if ($path === null) {
            $path = $this->config['view']['cache_dir'];
            $path = glob($path . '* ' . $this->config['view']['suffix_cache']);
        } else {
            $path = $this->config['view']['cache_dir'] . md5($path) . $this->config['view']['suffix_cache'];
        }
        foreach ((array)$path as $v) {
            unlink($v);
        }
    }
}