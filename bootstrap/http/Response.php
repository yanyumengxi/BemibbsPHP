<?php

namespace bemibbs\http;

/**
 * @author Lingqi <3615331065@qq.com>
 * @time 2022-05-16 15:29
 */
class Response
{
    public function setStatusCode(int $code)
    {
        http_response_code($code);
    }
}