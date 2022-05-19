<?php

namespace bemibbs;

/**
 * @author Lingqi <3615331065@qq.com>
 * @time 2022-05-17 13:10
 */
class Addons
{
    /**
     * 已加载插件
     * @var array
     */
    public array $addons = [];
    /**
     * 已注册插件
     * @var array
     */
    public array $addons_registered = [];
    /**
     * 插件存放目录
     * @var mixed|string
     */
    public $addons_dir;
    /**
     * 插件类后缀
     * @var mixed|string
     */
    public $suffix;
    /**
     * 插件类入口类名
     * @var mixed|string
     */
    public $entrance;
    /**
     * 插件类入口类后缀
     * @var mixed|string
     */
    public $entrance_suffix;

    /**
     * 构造参数
     */
    public function __construct()
    {
        $this->addons_dir = Application::$app->config->get(array(
            'addons' => 'dir'
        ));
        $this->suffix = Application::$app->config->get(array(
            'addons' => 'suffix'
        ));
        $this->entrance = Application::$app->config->get(array(
            'addons' => 'entrance'
        ));
        $this->entrance_suffix = Application::$app->config->get(array(
            'addons' => 'entrance_suffix'
        ));
    }

    /**
     * 注册插件
     * @param string $addonName
     * @return void
     */
    public function register(string $addonName)
    {
        $loaded_addons = $this->getAll_load()['list'];
        if (!array_key_exists($addonName, $loaded_addons)) {
            Exception::throw("<span style='color:#ff0000;'>未找到插件！</span><br> <span style='color: #67cd03'>插件名</span><span style='color: #b07b05'>：</span><span style='color: aqua'>$addonName</span><br><span style='color: #67cd03'>位&nbsp;&nbsp; 置</span><span style='color: #b07b05'>：</span><span style='color: aqua'>".__FILE__."</span>");
        } else {
            if ($this->isExits($addonName)) {
                return;
            }
            $paths = array();
            foreach ($loaded_addons as $k => $v) {
                if ($k === $addonName) {
                    $addon_files = $loaded_addons[$addonName]['files'];
                    for ($f = 0; $f < sizeof($addon_files); $f++) {
                        $type = $addon_files[$f]['type'];
                        $path = $addon_files[$f]['path'];
                        if ($type === 0) {
                            $paths[] = $path;
                        }
                    }
                }
            }
            $this->addons_registered['path'] = $paths;
        }
    }

    /**
     * 判断该插件是否已经注册，防止重复注册
     * @param string $addonName
     * @return bool
     */
    private function isExits(string $addonName): bool
    {
        foreach ($this->addons_registered['name'] as $v) {
            if ($addonName === $v) {
                return true;
            }
        }
        return false;
    }

    /**
     * 注册插件
     * @return void
     */
    public function registerAll()
    {
        $paths = array();
        $names = array();
        $loaded_addons = $this->getAll_load()['list'];
        foreach ($loaded_addons as $k => $v) {
            $names[] = $k;
            $addon_files = $loaded_addons[$k]['files'];
            for ($f = 0; $f < sizeof($addon_files); $f++) {
                $type = $addon_files[$f]['type'];
                $path = $addon_files[$f]['path'];
                if ($type === 0) {
                    $paths[] = $path;
                }
            }
        }
        $this->addons_registered['name'] = $names;
        $this->addons_registered['path'] = $paths;
    }

    /**
     * 取消注册插件
     * @param string $addonName
     * @return void
     */
    public function unregister(string $addonName)
    {
        for ($i = 0; $i < sizeof($this->addons_registered["name"]); $i++) {
            if ($this->addons_registered['name'][$i] === $addonName) {
                unset($this->addons_registered['name'][$i]);
                unset($this->addons_registered['path'][$i]);
            }
        }
    }

    /**
     * 获取所有已加载插件
     * @return array
     */
    public function getAll_load(): array
    {
        return $this->addons;
    }

    /**
     * 获取所有已注册插件
     * @return array
     */
    public function getAll_register(): array
    {
        return $this->addons_registered;
    }

    /**
     * 加载插件
     * @param string $addonName
     * @return void
     */
    public function load(string $addonName)
    {
        $addons_dir_son_dir = opendir($this->addons_dir);
        while (($name = readdir($addons_dir_son_dir)) !== false) {
            if ($name !== '.' && $name !== '..') {
                if ($name === $addonName) {
                    // 插件根目录
                    $addon_dir = $this->addons_dir . $name;
                    // 插件配置文件内容
                    $addon_config_file_content = $this->getContent($addon_dir . '/__addon__.json');
                    $addon_config_file_content_json_object = json_decode($addon_config_file_content, true);
                    // 插件名称
                    $addon_name = $addon_config_file_content_json_object['name'];
                    // 插件简介
                    $addon_description = $addon_config_file_content_json_object['description'];
                    //插件所有文件
                    $addon_files = $addon_config_file_content_json_object['file']['list'];
                    // 设置插件名称
                    $this->addons['list'][$addon_name]['name'] = $addon_name;
                    // 设置插件简介
                    $this->addons['list'][$addon_name]['description'] = $addon_description;
                    for ($i = 0; $i < sizeof($addon_files); $i++) {
                        // 文件类型
                        $type = $addon_files[$i]['type'];
                        // 文件名称
                        $name = $addon_files[$i]['name'];
                        // 文件路径
                        $path = dirname(__DIR__) . '/' . $addon_files[$i]['path'] . '/' . $name;
                        // 设置文件类型
                        $this->setFiletype($type, $addon_name, $i, $path);
                    }
                }
            }
        }
        closedir($addons_dir_son_dir);
    }

    /**
     * 卸载插件
     * @param string $addonName
     * @return void
     */
    public function unload(string $addonName)
    {
        foreach ($this->addons['list'] as $key => $value) {
            if ($key === $addonName)
                unset($this->addons['list'][$key]);
        }
    }

    /**
     * 加载所有插件
     * @return void
     */
    public function loadAll()
    {
        // 打开插件目录
        $addons_dir_son_dir = opendir($this->addons_dir);
        // 插件目录存放的所有插件
        $addons = [];
        while (($name = readdir($addons_dir_son_dir)) !== false) {
            if ($name !== '.' && $name !== '..') {
                $addon_dir = $this->addons_dir . $name;
                if (is_dir($addon_dir)) {
                    // 插件入口类文件路径
                    $addon_class_path = $addon_dir . '/' . $this->entrance . $this->entrance_suffix . '.php';
                    // 插件配置文件名
                    $addon_list_file = $addon_dir . '/__addon__.json';
                    if (!file_exists($addon_list_file)) {
                        echo "插件异常";
                        return;
                    }
                    if (!file_exists($addon_class_path)) {
                        echo "插件异常";
                        return;
                    }
                    // 获取插件配置文件并解析为json对象
                    $addons[] = $addon_list_file;
                }
            }
        }
        //关闭目录
        closedir($addons_dir_son_dir);
        foreach ($addons as $addon) {
            $addons_list_file_content = json_decode($this->getContent($addon), true);
            // 插件名称
            $addon_name = $addons_list_file_content['name'];
            // 插件简介
            $addon_description = $addons_list_file_content['description'];
            // 设置插件名称
            $this->addons['list'][$addon_name]['name'] = $addon_name;
            // 设置插件简介
            $this->addons['list'][$addon_name]['description'] = $addon_description;
            for ($i = 0; $i < count($addons_list_file_content["file"]["list"]); $i++) {
                // 文件类型
                $type = $addons_list_file_content["file"]["list"][$i]['type'];
                // 文件名称
                $name = $addons_list_file_content["file"]["list"][$i]['name'];
                // 文件路径
                $path = dirname(__DIR__) . '/addons/' . $addon_name . '/' . $addons_list_file_content["file"]["list"][$i]['path'] . '/' . $name;
                // 设置文件类型
                $this->setFiletype($type, $addon_name, $i, $path);
            }
        }
    }

    /**
     * 获取文件内容
     * @param string $path
     * @return false|string|void
     */
    public function getContent(string $path)
    {
        if (file_exists($path)) {
            $fo = fopen($path, "r");
            return fread($fo, filesize($path));
        }
    }

    /**
     * 上设置文件类型
     * @param $type
     * @param $addon_name
     * @param int $i
     * @param string $path
     * @return void
     */
    private function setFiletype($type, $addon_name, int $i, string $path): void
    {
        if ($type === 0) {
            $this->addons['list'][$addon_name]['files'][$i]['type'] = 0;
            $path = $path . $this->entrance_suffix . '.php';
        } else {
            $this->addons['list'][$addon_name]['files'][$i]['type'] = 1;
            $path = $path . $this->suffix . '.php';
        }
        //设置文件路径
        $this->addons['list'][$addon_name]['files'][$i]['path'] = $path;
    }

    /**
     * 执行所有插件
     * @return void
     */
    public function run()
    {
        foreach ($this->addons_registered['path'] as $value) {
            include $value;
        }
    }
}