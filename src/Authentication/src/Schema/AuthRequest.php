<?php

namespace Authentication\Schema;

/**
 * @OA\Schema()
 */
class AuthRequest
{
    /**
     * @var string
     * @OA\Property()
     */
    public $username;
    /**
     * @var string
     * @OA\Property()
     */
    public $password;
}
