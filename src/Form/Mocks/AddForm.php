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

namespace App\Form\Mocks;

use Cake\Utility\Hash;

/**
 * リソースの追加処理を行う
 *
 * @package App\Form\Mocks
 */
class AddForm extends \App\Form\Resources\AddForm
{
    /**
     * リクエストデータを必要に応じてエンコード
     *
     * @param array $data
     * @return array
     */
    public function encode(array $data)
    {
        if (is_array(Hash::get($data, 'response'))) {
            $data['response'] = json_encode($data['response']);
        }

        return $data;
    }
}
