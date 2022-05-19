<?php

namespace bemibbs\validator\rules;


use bemibbs\validator\Chain;

/**
 * @author Lingqi <3615331065@qq.com>
 * @time 2022-05-18 15:51
 */
class Number extends Chain
{
    public string $RULE = "/^[0-9]+$/";

    public function validate(): bool
    {
        if (preg_match($this->RULE, $this::$data))
            return true;
        return false;
    }
}