<?php

namespace bemibbs\view;

use bemibbs\Application;

/**
 * @author Lingqi <3615331065@qq.com>
 * @time 2022-05-16 21:12
 */
class Compile
{
    public string $parentLayoutContent;   // 父布局文件内容
    public string $template;   // 待编译的文件
    public $content;   // 需要替换的文件内容
    public string $comfile;   // 编译后的文件内容
    public string $prefix = '{{';   // 左定界符
    public string $suffix = '}}';   // 右定界符
    public array $value = array();   // 值栈
    public string $phpTurn;
    public array $T_P = array();   // 匹配正则数组
    public array $T_R = array();   // 替换数组
    public function __construct($template, $compileFile, $config) {
        $this->template = $template;
        $this->comfile = $compileFile;
        $this->content = file_get_contents($template);
        if ($config['php_turn'] === true) {
            $this->T_P[] = "#<\?(=|php|)(.+?)\?#is";
            $this->T_R[] = "&lt;?\1\2?&gt;";
        }
        // 变量匹配
        $this->T_P[] = "#".$this->prefix."css (.*?) ".$this->suffix."#";
        $this->T_P[] = "#".$this->prefix."js (.*?) ".$this->suffix."#";
        $this->T_P[] = "#".$this->prefix."php (.*?) ".$this->suffix."#";
        // \x7f-\xff表示ASCII字符从127到255，其中\为转义，作用是匹配汉字
        $this->T_P[] = "#".$this->prefix."y ([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*) ".$this->suffix."#";
        $this->T_P[] = "#".$this->prefix." ([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*) ".$this->suffix."#";
        // foreach标签盘匹配
        $this->T_P[] = "#".$this->prefix."(loop|foreach)\s+\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*".$this->suffix."#i";
        $this->T_P[] = "#".$this->prefix."([k|v])".$this->suffix."#";
        $this->T_P[] = "#".$this->prefix."\/(loop|foreach|if)".$this->suffix."#";
        // if else标签匹配
        $this->T_P[] = "#".$this->prefix."if (.*?)".$this->suffix."#";
        $this->T_P[] = "#".$this->prefix."(else if|elseif) (.*?)".$this->suffix."#";
        $this->T_P[] = "#".$this->prefix."else".$this->suffix."#";
        $this->T_P[] = "#".$this->prefix."(\#|\*)(.*?)(\#|\*)".$this->suffix."#";

        $this->T_R[] = "<link rel='stylesheet' type='text/css' href='\\1'>";
        $this->T_R[] = "<script type='text/javascript' src='\\1'></script>";
        $this->T_R[] = "<?php \\1 ?>";
        $this->T_R[] = "<?php echo \$\\1; ?>";
        $this->T_R[] = "<?php echo \$this->value['\\1']; ?>";
        $this->T_R[] = "<?php foreach ((array)\$this->value['\\2'] as \$k => \$v) { ?>";
        $this->T_R[] = "<?php echo \$\\1?>";
        $this->T_R[] = "<?php } ?>";
        // if else标签
        $this->T_R[] = "<?php if(\\1){ ?>";
        $this->T_R[] = "<?php }elseif(\\2){ ?>";
        $this->T_R[] = "<?php }else{ ?>";
        $this->T_R[] = "";
    }
    public function render() {
        $this->c_var();
//        $this->c_staticFile();
        return $this->content;
    }

    public function compile()
    {
        $layoutName = Application::$app->layout;
        $viewContent = $this->render();
        ob_start();
        include_once Application::$ROOT_DIR. "/app/views/layouts/$layoutName.php";
        $layoutContent = ob_get_clean();
        $layoutContent = preg_replace($this->T_P, $this->T_R, $layoutContent);
        $this->content = str_replace("{{IndexLayout}}", $this->content, $layoutContent);
        file_put_contents($this->comfile, $this->content);
    }

    public function c_var() {
        $this->content = preg_replace($this->T_P, $this->T_R, $this->content);
    }
    /* 对引入的静态文件进行解析，应对浏览器缓存 */
    public function c_staticFile() {
        $this->content = preg_replace('#\{\!(.*?)\!\}#', '<script src=\1'.'?t='.time().'></script>', $this->content);
    }
    public function __set($name, $value) {
        $this->$name = $value;
    }
    public function __get($name) {
        return $this->$name;
    }
}