<?php

namespace Authentication\Schema;

/**
 * @OA\Schema()
 */
class AuthResult
{
    /**
     * @var string
     * @OA\Property()
     */
    public $token;
    /**
     * @var object
     * @OA\Property(
     *     ref="#/components/schemas/UserObject",
     * )
     */
    public $user;
    /**
     * @var object
     * @OA\Property(
     *     ref="#/components/schemas/AvatarObject",
     * )
     */
    public $avatar;
    /**
     * @var string
     * @OA\Property(
     *    description="Expiration date of token",
     *    format="date-time"
     * )
     */
    public $expiresAt;
}
