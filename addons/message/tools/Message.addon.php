<?php

namespace aaa;

/**
 * @author Lingqi <3615331065@qq.com>
 * @time 2022-05-17 15:30
 */
class Message
{
    public function success(string $message)
    {
        echo "<div style='color: #67cd03;margin: 10px;padding: 5px;font-family: Calibri,serif;background: #525252;border-radius: 6px'>$message</div>";
    }
    public function warning(string $message)
    {
        echo "<div style='color: #ce9504;margin: 10px;padding: 5px;font-family: Calibri,serif;background: #525252;border-radius: 6px'>$message</div>";
    }
    public function error(string $message)
    {
        echo "<div style='color: #ce0b0b;margin: 10px;padding: 5px;font-family: Calibri,serif;background: #525252;border-radius: 6px'>$message</div>";
    }
    public function info(string $message)
    {
        echo "<div style='color: #2a2a2a;margin: 10px;padding: 5px;font-family: Calibri,serif;background: #525252;border-radius: 6px'>$message</div>";
    }
}