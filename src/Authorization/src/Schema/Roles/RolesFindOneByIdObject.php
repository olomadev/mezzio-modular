<?php

namespace Authorization\Schema\Roles;

/**
 * @OA\Schema()
 */
class RolesFindOneByIdObject
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
    public $roleName;
    /**
     * @var string
     * @OA\Property()
     */
    public $roleKey;
    /**
     * @var string
     * @OA\Property()
     */
    public $roleLevel;
    /**
    *  @var array
    *  @OA\Property(
    *      type="array",
    *      @OA\Items(
    *           @OA\Property(
    *             property="permId",
    *             type="string",
    *           ),
    *           @OA\Property(
    *             property="moduleName",
    *             type="string",
    *           ),
    *           @OA\Property(
    *             property="method",
    *             type="string",
    *           ),
    *           @OA\Property(
    *             property="route",
    *             type="string",
    *           ),
    *           @OA\Property(
    *             property="action",
    *             type="string",
    *           ),
    *     ),
    *  );
    */
    public $rolePermissions;
    /**
    *  @var array
    *  @OA\Property(
    *      type="array",
    *      @OA\Items(
    *           @OA\Property(
    *             property="id",
    *             type="string",
    *           ),
    *           @OA\Property(
    *             property="firstname",
    *             type="string",
    *           ),
    *           @OA\Property(
    *             property="lastname",
    *             type="string",
    *           ),
    *           @OA\Property(
    *             property="active",
    *             type="number",
    *           ),
    *           @OA\Property(
    *             property="createdAt",
    *             type="string",
    *           ),
    *     ),
    *  );
    */
    public $roleUsers;
}
