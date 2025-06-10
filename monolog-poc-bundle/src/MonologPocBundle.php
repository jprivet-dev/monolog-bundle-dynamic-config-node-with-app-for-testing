<?php

namespace Local\Bundle\MonologPocBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MonologPocBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
