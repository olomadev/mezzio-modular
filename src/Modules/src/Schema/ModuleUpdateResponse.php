<?php

namespace Modules\Schema;

/**
 * @OA\Schema()
 */
class ModuleUpdateResponse
{
    /**
     * @var object
     * @OA\Property(
     *     ref="#/components/schemas/OldRecordObject",
     * )
     */
    public $oldRecord;
}
