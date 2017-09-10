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

use Cake\View\Helper;

/**
 * Bootstrap 関連の描画を行う Helper
 *
 * @package App\View\Helper
 */
class BootstrapHelper extends Helper
{
    /**
     * Label Component (Primary) を出力する
     *
     * @param string $title
     * @param array $classes
     * @return string
     */
    public function primary($title, array $classes = [])
    {
        $classes[] = 'label-primary';

        return $this->label($title, $classes);
    }

    /**
     * Label Component を出力する
     *
     * @param string $title
     * @param array $classes
     * @return string
     */
    public function label($title, array $classes = ['label-default'])
    {
        return sprintf('<label class="label %s">%s</label>', implode(' ', $classes), $title);
    }

    /**
     * Label Component (Success) を出力する
     *
     * @param string $title
     * @param array $classes
     * @return string
     */
    public function success($title, array $classes = [])
    {
        $classes[] = 'label-success';

        return $this->label($title, $classes);
    }

    /**
     * Label Component (Info) を出力する
     *
     * @param string $title
     * @param array $classes
     * @return string
     */
    public function info($title, array $classes = [])
    {
        $classes[] = 'label-info';

        return $this->label($title, $classes);
    }

    /**
     * Label Component (Warning) を出力する
     *
     * @param string $title
     * @param array $classes
     * @return string
     */
    public function warning($title, array $classes = [])
    {
        $classes[] = 'label-warning';

        return $this->label($title, $classes);
    }

    /**
     * Label Component (Danger) を出力する
     *
     * @param string $title
     * @param array $classes
     * @return string
     */
    public function danger($title, array $classes = [])
    {
        $classes[] = 'label-danger';

        return $this->label($title, $classes);
    }
}
