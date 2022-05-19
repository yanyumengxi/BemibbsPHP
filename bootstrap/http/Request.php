<?php

namespace bemibbs\http;

/**
 * 请求相关类
 * @author Lingqi <3615331065@qq.com>
 * @time 2022-05-16 14:40
 */
class Request
{
    /**
     * @return false|mixed|string 获取请求路径
     */
    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position === false) {
            return $path;
        }
        return substr($path, 0, $position);
    }

    /**
     * @return string 请求方式
     */
    public function method(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * @return bool 是否是Get请求
     */
    public function isGet(): bool
    {
        return $this->method() === "get";
    }

    /**
     * @return bool 是否是Post请求
     */
    public function isPost(): bool
    {
        return $this->method() === "post";
    }

    /**
     * @return array 获取请求参数
     */
    public function getBody(): array
    {
        $body = [];
        if ($this->method() === "get") {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if ($this->method() === "post") {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $body;
    }
}