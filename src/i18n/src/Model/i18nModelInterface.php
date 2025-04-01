<?php

declare(strict_types=1);

namespace i18n\Model;

use Laminas\Db\Adapter\AdapterInterface;

interface i18nModelInterface
{
    /**
     * It brings up all languages and their settings.
     *
     * @return array
     */
    public function findAll(): array;

    /**
     * Returns the database adapter.
     *
     * @return AdapterInterface
     */
    public function getAdapter(): AdapterInterface;
}
