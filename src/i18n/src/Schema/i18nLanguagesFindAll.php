<?php

namespace i18n\Schema;

/**
 * @OA\Schema()
 */
class i18nLanguagesFindAll
{
    /**
    *  @var array
    *  @OA\Property(
    *      type="array",
    *      @OA\Items(
    *           @OA\Property(
    *             property="id",
    *             type="string",
    *           ),
    *            @OA\Property(
    *             property="name",
    *             type="string",
    *           )
    *     ),
    *  )
    */
    public $data;
}
