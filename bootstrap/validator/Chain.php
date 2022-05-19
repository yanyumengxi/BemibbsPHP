<?php

namespace bemibbs\validator;

use bemibbs\validator\rules\Domain;
use bemibbs\validator\rules\Email;
use bemibbs\validator\rules\Length;
use bemibbs\validator\rules\Number;
use bemibbs\validator\rules\Required;
use bemibbs\validator\rules\URL;

/**
 * @author Lingqi <3615331065@qq.com>
 * @time 2022-05-18 14:05
 */
class Chain
{
    public array $rules;
    /**
     * @var string 被验证的内容
     */
    public static string $data;
    /**
     * @var string 验证规则
     */
    public static string $type;

    /**
     * 校验Email
     * @return bool
     */
    public function email(): bool
    {
        $email = new Email();
        return $email->validate();
    }

    /**
     * 校验不为空
     * @return bool
     */
    public function required(): bool
    {
        $required = new Required();
        return $required->validate();
    }

    /**
     * 校验字符串长度
     * @param int $min 最小长度
     * @param int $max 最大长度
     * @return bool
     */
    public function length(int $min,int $max): bool
    {
        $length = new Length($min, $max);
        return $length->validate();
    }

    /**
     * 校验域名
     * @return bool
     */
    public function domain(): bool
    {
        $domain = new Domain();
        return $domain->validate();
    }

    /**
     * 校验数字
     * @return bool
     */
    public function number(): bool
    {
        $domain = new Number();
        return $domain->validate();
    }

    /**
     * 校验Url地址
     * @return bool
     */
    public function url(): bool
    {
        $url = new URL();
        return $url->validate();
    }

    /**
     * 自定义校验
     * @param string $regx 正则表达式
     * @return bool
     */
    public function rule(string $regx): bool
    {
        if (preg_match($regx, self::$data)) {
            return true;
        }
        return false;
    }
}