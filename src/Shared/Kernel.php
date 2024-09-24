<?php

declare(strict_types=1);

namespace ChronicleKeeper\Shared;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

use function dirname;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function getProjectDir(): string
    {
        return dirname(
            __DIR__,
            2,
        );
    }
}
