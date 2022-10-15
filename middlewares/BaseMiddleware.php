<?php

namespace grimm\grimmmvc\middlewares;

abstract class BaseMiddleware
{
    abstract public function execute();
}