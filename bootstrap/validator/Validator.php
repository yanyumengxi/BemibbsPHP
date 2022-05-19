<?php

namespace bemibbs\validator;

/**
 * @author Lingqi <3615331065@qq.com>
 * @time 2022-05-17 21:56
 */
class Validator
{
    public const RULE_REQUIRED = "required";
    public const RULE_EMAIL = "email";
    public const RULE_MIN = "min";
    public const RULE_MAX = "max";
    public const RULE_NUMBER = "number";
    public const RULE_HTTP_S = "http_s";
    public const RULE_DOMAIN = "domain";

    /**
     * 验证规则
     * @var array
     */
    public array $rules;
    /**
     * @var Chain 验证规则对象
     */
    public Chain $chain;

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->chain = new Chain();
        // 初始化规则正则
        $this->rules = $this->InitRules();
    }

    /**
     * 自定义验证规则
     * @param $rules
     * @return void
     */
    public function rule($rules)
    {
        $this->rules = array_merge($this->rules, $rules);
    }

    /**
     * 验证字符串
     * @param $data string 被验证的内容
     * @param $type string 验证类型
     * @param $rules array 自定义的验证规则
     * @return Chain
     */
    public function check(string $data, string $type = "", array $rules = []): Chain
    {
        $this->chain::$data = $data;
        $this->chain::$type = $type;
        return $this->chain;
    }

    /**
     * 初始化验证规则
     * @param int $min
     * @param int $max
     * @return array
     */
    protected function InitRules(int $min = 4, int $max = 12): array
    {
        return [
            self::RULE_REQUIRED => true,
            self::RULE_MIN => "^(\w{{$min},})$",
            self::RULE_MAX => "^(\w{0,{$max}})$",
            self::RULE_EMAIL => "\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*",
            self::RULE_NUMBER => "[0-9]",
            self::RULE_HTTP_S => "https?://([\w-]+\.)+[\w-]+(/[\w-./?%&=]*)?",
            self::RULE_DOMAIN => "(https?:\/\/)?([\w-]+\.)+\w+(\:\d{2,6})?",
        ];
    }
}