<?php

namespace Users\MyAccount\Schema;

/**
 * @OA\Schema()
 */
class MyAccountFindMe
{
    /**
     * @var object
     * @OA\Property(
     *     ref="#/components/schemas/MyAccountFindMeObject",
     * )
     */
    public $data;
}
