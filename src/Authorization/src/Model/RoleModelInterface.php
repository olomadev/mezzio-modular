<?php
declare(strict_types=1);

namespace Authorization\Model;

interface RoleModelInterface
{
    /**
     * Find roles assigned to a user by their userId.
     *
     * @param string $userId User ID
     * @return array List of role keys
     */
    public function findRolesByUserId(string $userId): array;

    /**
     * Find all available roles for selection.
     *
     * @return array List of roles
     */
    public function findRoles(): ?array;
    
    /**
     * Find a role by its roleId.
     *
     * @param string $roleId Role ID
     * @return array Role details with permissions
     */
    public function findOneById(string $roleId);

    /**
     * Create a new role and its associated permissions.
     *
     * @param array $data Role and permission data
     * @return void
     */
    public function create(array $data) : void;

    /**
     * Update an existing role and its associated permissions.
     *
     * @param array $data Role and permission data
     * @return void
     */
    public function update(array $data) : void;

    /**
     * Delete a role by its roleId.
     *
     * @param string $roleId Role ID
     * @return void
     */
    public function delete(string $roleId) : void;
}
