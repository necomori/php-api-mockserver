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

use Cake\Utility\Hash;
use InvalidArgumentException;

/**
 * Request ValueObject
 *
 * @package App\ValueObject
 */
class Request
{
    /** @var string */
    public $url;

    /** @var string */
    public $method;

    /**
     * constructor
     *
     * @throws InvalidArgumentException
     */
    public function __construct()
    {
        $this->url = Hash::get($_SERVER, 'REQUEST_URI');
        $this->method = Hash::get($_SERVER, 'REQUEST_METHOD');
    }
}
