<?php

namespace Modules\Schema;

/**
 * @OA\Schema()
 */
class OldRecordObject
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
    public $name;
    /**
     * @var string
     * @OA\Property()
     */
    public $version;
    /**
     * @var integer
     * @OA\Property()
     */
    public $isActive;
}
