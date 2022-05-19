<?php

namespace bemibbs;

/**
 * @author Lingqi <3615331065>
 * @time 2022-05-17 18:36
 */
class Exception
{
    public static function throw($message)
    {
        $value = "<div style='color: #e30707;background: #4f4f4f;font-size: 16px;font-family: Cambria,,serif;border-radius: 4px;margin: 10px;padding: 10px;letter-spacing: 2px;backdrop-filter: blur(4px)'>$message</div>";
        die($value);
    }
}