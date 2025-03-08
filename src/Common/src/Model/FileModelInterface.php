<?php
declare(strict_types=1);

namespace Common\Model;

interface FileModelInterface
{
    /**
     * Find one file by file id
     *
     * @param  string $fileId
     * @return array
     */
    public function findOneById(string $fileId): array;
}
