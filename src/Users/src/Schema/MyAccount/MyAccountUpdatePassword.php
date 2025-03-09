<?php

namespace Users\MyAccount\Schema;

/**
 * @OA\Schema()
 */
class MyAccountUpdatePassword
{
    /**
     * @var string
     * @OA\Property()
     */
    public $oldPassword;
    /**
     * @var string
     * @OA\Property()
     */
    public $newPassword;
}
