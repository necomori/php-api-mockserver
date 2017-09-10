<?php
/**
 * Copyright (c) necomori LLC (https://necomori.asia)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright  Copyright (c) necomori LLC (https://necomori.asia)
 * @since      0.1.0
 * @license    https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\ValueObject;

/**
 * Response ValueObject
 *
 * @package App\ValueObject
 */
class Response
{
    /** @var int */
    public $code;

    /** @var array|null */
    public $header;

    /** @var string|array|null */
    public $body;
}
