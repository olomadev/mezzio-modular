<?php

namespace Authorization\Schema\Permissions;

/**
 * @OA\Schema()
 */
class PermissionSave
{
    /**
     * @var string
     * @OA\Property(
     *     format="uuid"
     * )
     */
    public $permId;
    /**
     * @var string
     * @OA\Property()
     */
    public $module;
    /**
     * @var string
     * @OA\Property()
     */
    public $name;
    /**
     * @var string
     * @OA\Property()
     */
    public $resource;
    /**
    * @var object
    * @OA\Property(
    *     ref="#/components/schemas/ObjectId",
    *     format="string",
    * )
    */
    public $action;
    /**
     * @var string
     * @OA\Property()
     */
    public $route;
    /**
    * @var object
    * @OA\Property(
    *     ref="#/components/schemas/ObjectId",
    *     format="string",
    * )
    */
    public $method;
}
