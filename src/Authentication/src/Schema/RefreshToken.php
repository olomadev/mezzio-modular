<?php

namespace Authentication\Schema;

/**
 * @OA\Schema()
 */
class RefreshToken
{
    /**
    * @var string
    * @OA\Property()
    */
    public $token;
}
