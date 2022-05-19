<?php

namespace app\models;

use bemibbs\Model;

/**
 * @author Lingqi <3615331065@qq.com>
 * @time 2022-05-16 17:36
 */
class RegisterModel extends Model
{
    public string $username;
    public string $nickname;

    public function register()
    {
    }

    public function rules(): array
    {
        return [
            'username' => [self::RULE_REQUIRED],
            'nickname' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 3], [self::RULE_MAX, 'max' => 16]]
         ];
    }
}