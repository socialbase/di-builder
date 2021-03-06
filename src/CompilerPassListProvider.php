<?php
declare(strict_types=1);

namespace Lcobucci\DependencyInjection;

use Generator as DefaultGenerator;
use Lcobucci\DependencyInjection\Config\Package;

interface CompilerPassListProvider extends Package
{
    public function getCompilerPasses(): DefaultGenerator;
}
