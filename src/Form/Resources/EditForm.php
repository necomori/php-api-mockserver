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
 * リソースの更新処理を行う
 *
 * @package App\Form\Resources
 */
class EditForm extends AppForm
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
        $schema
            ->addField('url', ['type' => 'string'])
            ->addField('method', ['type' => 'string'])
            ->addField('response', ['type' => 'text']);

        return $schema;
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

        $appValidator
            ->requirePresence('url', 'create')
            ->notEmpty('url')
            ->maxLength('url', 255);

        $appValidator
            ->requirePresence('method', 'create')
            ->notEmpty('method')
            ->maxLength('method', 10)
            ->add('method', 'requestMethod', [
                'rule' => function ($value, $context) {
                    return in_array(strtoupper($value), ['GET', 'POST', 'PUT', 'DELETE'], true);
                },
                'message' => __('This field must select from GET / POST / PUT / DELETE'),
            ]);

        $appValidator
            ->requirePresence('response', 'create')
            ->notEmpty('response')
            ->maxLength('response', 1000)
            ->add('response', 'json', [
                'rule' => function ($value, $context) {
                    if (empty($value)) {
                        return false;
                    }

                    return is_string($value) && is_array(json_decode($value, true)) && (json_last_error() === JSON_ERROR_NONE);
                },
                'message' => __('This field must be in json format'),
            ])
            ->add('response', 'response', [
                'rule' => function ($value, $context) {
                    if (empty($value)) {
                        return false;
                    }

                    $response = json_decode($value, true);
                    if (empty($response)) {
                        return false;
                    }

                    if (isset($response['code'], $response['body'])) {
                        return true;
                    }

                    $result = true;

                    /** @var array $response */
                    foreach ($response as $row) {
                        if (!isset($row['code'], $row['body'])) {
                            $result = false;
                            break;
                        }
                    }

                    return $result;
                },
                'message' => __('This field must contain code and body'),
            ]);

        return $appValidator;
    }

    /**
     * ロジックを実行
     *
     * @param array $data
     * @return bool
     */
    protected function _execute(array $data)
    {
        $result = true;

        try {
            $this->loadModel('Resources');

            $id = Hash::get($data, 'id');

            /** @var \App\Model\Entity\Resource $resource */
            $resource = $this->Resources->get($id, [
                'contain' => [],
            ]);
            $resource = $this->Resources->patchEntity($resource, $data);

            $this->Resources->save($resource);
            $errors = $resource->getErrors();
        } catch (\Exception $e) {
            $this->log($e->getMessage(), 'debug');

            $errors = [
                'exception' => $e->getMessage(),
            ];
        }

        if (!empty($errors)) {
            $this->setErrors($errors);
            $result = false;
        }

        return $result;
    }
}
