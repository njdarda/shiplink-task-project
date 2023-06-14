<?php declare(strict_types=1);

namespace App\Tests\Util;

use Zenstruck\Browser\KernelBrowser;

class ApiBrowser extends KernelBrowser
{
    use CanJwtAuthenticate;
}
