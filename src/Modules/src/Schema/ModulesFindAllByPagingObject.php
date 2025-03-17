<?php

namespace Modules\Schema;

/**
 * @OA\Schema()
 */
class ModulesFindAllByPagingObject
{
    /**
     * @var string
     * @OA\Property(
     *     format="uuid"
     * )
     */
    public $id;
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
