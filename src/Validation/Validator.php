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

namespace App\Validation;

use App\Utility\Message;

class Validator extends \Cake\Validation\Validator
{
    /**
     * CakePHP Core の add の挙動を変更する
     *
     * @param string $field
     * @param array|string $name
     * @param array $rule
     * @return $this
     */
    public function add($field, $name, $rule = [])
    {
        $fieldSet = $this->field($field);

        if (!is_array($name)) {
            $rules = [$name => $rule];
        } else {
            $rules = $name;
        }

        foreach ($rules as $name => $rule) {
            if (!isset($rule['message'])) {
                $message = null;
                $args = $rule['rule'];
                if (is_array($args)) {
                    $ruleName = array_shift($args);
                    $message = Message::getValidationMessage($ruleName, ...$args);
                } elseif (is_string($args)) {
                    $ruleName = $args;
                    $message = Message::getValidationMessage($ruleName);
                }

                if ($message) {
                    $rule['message'] = $message;
                }
            }
            $fieldSet->add($name, $rule);
        }

        return $this;
    }
}
