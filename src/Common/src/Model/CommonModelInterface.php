<?php
declare(strict_types=1);

namespace Common\Model;

use Laminas\Db\Adapter\AdapterInterface;

interface CommonModelInterface
{
    public function findModules(): array;
    
    public function findLocaleIds(): array;

    public function findLocales(): array;

    public function getAdapter(): AdapterInterface;
}
