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

namespace App\Form;

use Cake\Datasource\ModelAwareTrait;
use Cake\Form\Form;
use Cake\Http\ServerRequest;
use Cake\Log\LogTrait;
use Cake\ORM\Entity;

/**
 * アプリケーションで利用するFormの基底クラス
 *
 * @package App\Form
 */
class AppForm extends Form
{
    use LogTrait;
    use ModelAwareTrait;

    /**
     * リクエストデータに値を追加して返却
     *
     * @param ServerRequest $request
     * @param Entity $entity
     * @return ServerRequest
     */
    public function withData($request, $entity)
    {
        $this->schema();

        foreach ($this->_schema->fields() as $field) {
            $request = $request->withData($field, $entity->{$field});
        }

        return $request;
    }

    /**
     * エラーメッセージを追加する
     *
     * @param string $field
     * @param array $error
     * @return void
     */
    public function addError($field, array $error)
    {
        $this->_errors[$field] = $error;
    }
}
