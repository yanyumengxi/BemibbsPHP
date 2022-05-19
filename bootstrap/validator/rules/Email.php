<?php

namespace bemibbs\validator\rules;

use bemibbs\validator\Chain;

/**
 * @author Lingqi <3615331065@qq.com>
 * @time 2022-05-18 13:53
 */
class Email extends Chain
{
    private string $RULE = "/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/";

    public function validate(): bool
    {
        if (preg_match($this->RULE, $this::$data))
            return true;
        return false;
    }
}