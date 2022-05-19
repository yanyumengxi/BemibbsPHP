<?php

namespace bemibbs\validator\rules;

use bemibbs\validator\Chain;

/**
 * @author Lingqi <3615331065@qq.com>
 * @time 2022-05-18 15:53
 */
class URL extends Chain
{
    public string $RULE = "/https?:\/{2}[-A-Za-z0-9+&@#\/\%?=~_|!:,.;]+[-A-Za-z0-9+&@#\/\%=~_|]/i";

    public function validate(): bool
    {
//        echo "<pre>";
//        var_dump($this->RULE);
//        echo "</pre>";
//        exit;
        if (preg_match($this->RULE, $this::$data))
            return true;
        return false;
    }
}