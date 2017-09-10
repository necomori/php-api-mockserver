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
 * リソースの更新処理を行う
 *
 * @package App\Form\Mocks
 */
class EditForm extends \App\Form\Resources\EditForm
{
    /**
     * リクエストデータを必要に応じてエンコード
     *
     * @param int $id
     * @param string $input
     * @return array
     */
    public function encode($id, $input)
    {
        $data = [];

        if (is_string($input) && is_array(json_decode($input, true)) && (json_last_error() === JSON_ERROR_NONE)) {
            $data = json_decode($input, true);
            if (is_array(Hash::get($data, 'response'))) {
                $data['response'] = json_encode($data['response']);
            }
            $data['id'] = $id;
        }

        return $data;
    }
}
