<?php
/**
 * Copyright (c) necomori LLC (http://necomori.asia)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright  Copyright (c) necomori LLC (http://necomori.asia)
 * @since      0.1.0
 * @license    https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Utility;

use Cake\Core\Configure;

/**
 * 共通メッセージを扱う
 *
 * @package App\Utility
 */
class Message
{
    /**
     * 指定されたキーに対するバリデーションメッセージを返却
     *
     * @param string $key
     * @param array ...$args
     * @return mixed
     */
    public static function getValidationMessage($key, ...$args)
    {
        return static::getMessage("Validation.{$key}", ...$args);
    }

    /**
     * 指定されたキーに対するメッセージを返却
     *
     * @param string $key
     * @param array ...$args
     * @return mixed
     */
    public static function getMessage($key, ...$args)
    {
        if (empty($args)) {
            $message = __(Configure::read("messages.{$key}"));
        } else {
            $message = __(Configure::read("messages.{$key}"), $args);
        }

        return $message;
    }
}
