<?php
declare(strict_types=1);

namespace Users\Model;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Paginator\Paginator;

interface UserModelInterface
{
    /**
     * Find all user by pagination
     * 
     * @param  array  $get query string
     * @return Paginator object
     */
    public function findAllByPaging(array $get): Paginator;
    
    /**
     * Find one user by user id
     * 
     * @param  string $userId id
     * @return false|array
     */
    public function findOneById(string $userId);
    
    /**
     * Find one user by username
     * 
     * @param  string $username email
     * @return false|array
     */
    public function findOneByUsername(string $username);
    
    /**
     * Create a user
     * 
     * @param  array  $data schema data
     * @return void
     */
    public function create(array $data);
    
    /**
     * Update a user
     * 
     * @param  array  $data schema data
     * @return void
     */
    public function update(array $data);
    
    /**
     * Delete a user
     * 
     * @param  string $userId user id
     * @return void
     */
    public function delete(string $userId);
    
    /**
     * Update user password by user id
     * 
     * @param  string $userId      user id
     * @param  string $newPassword new password
     * @return void
     */
    public function updatePasswordById(string $userId, string $newPassword);
    
    /**
     * Returns to laminas adapter
     * 
     * @return Laminas\Db\Adapter\AdapterInterface
     */
    public function getAdapter(): AdapterInterface;
}
