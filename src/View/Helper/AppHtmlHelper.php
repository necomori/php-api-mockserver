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

namespace App\View\Helper;

use Cake\Utility\Hash;
use Cake\View\Helper;
use InvalidArgumentException;

/**
 * HTML表示の補助を行う
 *
 * @package App\View\Helper
 */
class AppHtmlHelper extends Helper
{
    /**
     * 文字列を表示する
     *
     * @param string $str
     * @param array $options
     * @return string
     * @throws InvalidArgumentException
     */
    public function str($str, array $options = [])
    {
        $default = Hash::get($options, 'default', '--');
        $escape = Hash::get($options, 'escape');

        $out = $str;
        if (($str === null) || ($str === '')) {
            $out = $default;
        }

        if ($escape) {
            $out = h($out);
        }

        return $out;
    }

    /**
     * 日時を表示する
     *
     * @param string $str
     * @param array $options
     * @return string
     * @throws InvalidArgumentException
     */
    public function datetime($str, array $options = [])
    {
        $format = Hash::get($options, 'format', 'Y-m-d H:i:s');
        $default = Hash::get($options, 'default');
        $escape = Hash::get($options, 'escape');

        if (($str === null) || ($str === '')) {
            $out = $default;
        } else {
            $dateTime = new \DateTime($str);
            $out = $dateTime->format($format);
        }

        if ($escape) {
            $out = h($out);
        }

        return $out;
    }
}
