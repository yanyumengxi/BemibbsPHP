<?php

namespace bemibbs\validator\rules;

use bemibbs\validator\Chain;
use bemibbs\validator\Rule;

/**
 * @author Lingqi <3615331065>
 * @time 2022-05-18 15:41
 */
class Length extends Chain
{
    /**
     * 最小长度
     * @var int
     */
    public int $min;
    /**
     * 最大长度
     * @var int
     */
    public int $max;

    public function __construct(int $min, int $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function validate(): bool
    {
        if (strlen($this::$data) >= $this->min && strlen($this::$data) <= $this->max) {
            return true;
        }
        return false;
    }
}