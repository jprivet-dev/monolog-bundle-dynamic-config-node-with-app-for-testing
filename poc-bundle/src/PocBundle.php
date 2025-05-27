<?php

namespace Local\Bundle\PocBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class PocBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
