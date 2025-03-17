<?php

namespace Modules\Schema;

/**
 * @OA\Schema()
 */
class ModuleSave
{
    /**
     * @var string
     * @OA\Property(
     *     format="uuid"
     * )
     */
    public $moduleId;
    /**
     * @var string
     * @OA\Property()
     */
    public $moduleName;
    /**
     * @var string
     * @OA\Property()
     */
    public $moduleVersion;
    /**
     * @var integer
     * @OA\Property()
     */
    public $isActive;
}
