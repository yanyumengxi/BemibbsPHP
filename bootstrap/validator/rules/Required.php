<?php

namespace bemibbs\validator\rules;

use bemibbs\validator\Chain;

/**
 * @author Lingqi <3615331065@qq.com>
 * @time 2022-05-18 15:25
 */
class Required extends Chain
{
    public function validate(): bool
    {
        if (!empty($this->data) || strlen($this::$data) !== 0) {
            return true;
        }
        return false;
    }
}