<?php

namespace bemibbs\validator\rules;

use bemibbs\validator\Chain;

/**
 * @author Lingqi <3615331065@qq.com>
 * @time 2022-05-18 15:54
 */
class Domain extends Chain
{
    public string $RULE = "/(https?:\/\/)?([\w-]+\.)+\w+(\:\d{2,6})?/";

    public function validate(): bool
    {
        if (preg_match($this->RULE, $this::$data))
            return true;
        return false;
    }
}