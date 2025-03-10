<?php

namespace Users\Schema;

/**
 * @OA\Schema()
 */
class UsersFindOneByIdObject
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
    public $firstname;
    /**
     * @var string
     * @OA\Property()
     */
    public $lastname;
    /**
     * @var string
     * @OA\Property()
     */
    public $email;
    /**
     * @var integer
     * @OA\Property()
     */
    public $active;
    /**
     * @var integer
     * @OA\Property()
     */
    public $emailActivation;
    /**
     * @var string
     * @OA\Property()
     */
    public $themeColor;
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
     *     format="date-time",
     * )
     */
    public $createdAt;
    /**
     * @var string
     * @OA\Property(
     *     format="date-time",
     * )
     */
    public $updatedAt;
    /**
     * @var string
     * @OA\Property(
     *     format="date-time",
     * )
     */
    public $lastLogin;
}
