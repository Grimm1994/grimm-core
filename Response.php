<?php

namespace grimm1994\grimmCore;

class Response
{
    public function setStatusCode(int $code): void
    {
        http_response_code($code);
    }

    public function redirect(string $url): never
    {
        header("Location: $url");
        exit();
    }
}