<?php
declare(strict_types=1);

namespace Authorization\Model;

interface UserRoleModelInterface
{
    /**
     * Assign roles to user
     * 
     * @param  string $userId user id
     * @param  array  $roles  role ids
     * @return void
     */
    public function assignRoles(string $userId, array $roles);

    /**
     * Unassign roles from user
     * 
     * @param  string $userId user id
     * @param  array  $roles  role ids
     * @return void
     */
    public function unassignRoles(string $userId, array $roles);
}