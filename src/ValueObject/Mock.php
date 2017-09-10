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
 * Mock ValueObject
 *
 * @package App\ValueObject
 */
class Mock
{
    /** @var int */
    public $id;

    /** @var string */
    public $url;

    /** @var string */
    public $method;

    /** @var string|array|null */
    public $response;
}
