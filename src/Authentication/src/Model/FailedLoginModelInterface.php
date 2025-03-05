<?php
declare(strict_types=1);

namespace Authentication\Model;

interface FailedLoginModelInterface
{
    /**
     * Check username is banned
     * 
     * @param  string username
     * @return mixed
     */
    public function checkUsername(string $username);

    /**
     * Create login attempt
     * 
     * @param  array  $data attempt data
     * @return void
     */
    public function createAttempt(array $data);

    /**
     * Delete login attempts
     * 
     * @param  string $loginId login id
     * @return void
     */
    public function delete(string $loginId);

    /**
     * Delete all attempts of user and update login date
     * 
     * @param  array  $data  update data
     * @param  array  $where where options
     * @return void
     */
    public function deleteAttemptsAndUpdateUser(array $data, array $where);

    /**
     * Delete all attempts of user by username
     * 
     * @param  string $username username
     * @return void
     */
    public function deleteAttemptsByUsername(string $username);

    /**
     * Set ban message
     * 
     * @param string $message ban message
     */
    public function setMessage(string $message);

    /**
     * Get ban message
     * 
     * @return string message
     */
    public function getMessage();
}
