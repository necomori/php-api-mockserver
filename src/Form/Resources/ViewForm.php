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

namespace App\Form\Resources;

use App\Form\AppForm;
use App\Model\Table\ResourcesTable;
use Cake\Form\Schema;
use Cake\Utility\Hash;
use Cake\Validation\Validator;

/**
 * リソースの情報表示処理を行う
 *
 * @package App\Form\Resources
 */
class ViewForm extends AppForm
{
    /** @var ResourcesTable */
    public $Resources;

    /**
     * Schemaを組み立てて返却
     *
     * @param Schema $schema
     * @return Schema
     */
    protected function _buildSchema(Schema $schema)
    {
        return $schema->addField('id', 'integer');
    }

    /**
     * バリデーションルールを組み立てて返却
     *
     * @param Validator $validator
     * @return Validator
     */
    protected function _buildValidator(Validator $validator)
    {
        $appValidator = new \App\Validation\Validator();

        return $appValidator
            ->requirePresence('id')
            ->notEmpty('id');
    }

    /**
     * ロジックを実行
     *
     * @param array $data
     * @return \App\Model\Entity\Resource|bool
     */
    protected function _execute(array $data)
    {
        try {
            $this->loadModel('Resources');

            $id = Hash::get($data, 'id');

            /** @var \App\Model\Entity\Resource $resource */
            $resource = $this->Resources->get($id, [
                'contain' => [],
            ]);
        } catch (\Exception $e) {
            $this->log($e->getMessage(), 'debug');

            return false;
        }

        return $resource;
    }
}
