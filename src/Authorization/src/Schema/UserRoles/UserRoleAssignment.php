<?php

namespace Authorization\Schema\UserRoles;

/**
 * @OA\Schema()
 */
class UserRoleAssignment
{
    /**
     * @var string
     * @OA\Property(
     *     format="uuid"
     * )
     */
    public $userId;
    /**
     * @var string
     * @OA\Property(
     *     format="uuid"
     * )
     */
    public $roleId;
}
