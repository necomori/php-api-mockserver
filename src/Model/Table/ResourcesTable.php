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

namespace App\Model\Table;

use App\Model\Entity\Resource;
use App\ValueObject\Request;
use App\ValueObject\Response;
use Cake\Datasource\EntityInterface;
use Cake\Log\LogTrait;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use InvalidArgumentException;
use RuntimeException;

/**
 * Resources Model
 *
 * @method Resource get($primaryKey, $options = [])
 * @method Resource newEntity($data = null, array $options = [])
 * @method Resource[] newEntities(array $data, array $options = [])
 * @method Resource|bool save(EntityInterface $entity, $options = [])
 * @method Resource patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Resource[] patchEntities($entities, array $data, array $options = [])
 * @method Resource findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ResourcesTable extends Table
{
    use LogTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     * @throws RuntimeException
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('resources');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('url')
            ->allowEmpty('url');

        $validator
            ->scalar('method')
            ->allowEmpty('method');

        $validator
            ->scalar('response')
            ->allowEmpty('response');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param RulesChecker $rules The rules object to be modified.
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['url', 'method']), 'isUniqueURLAndMethod', [
            'errorField' => 'method',
            'message' => __('Request Method for URL already exists'),
        ]);

        return $rules;
    }

    /**
     * 指定されてURLとMethodに対するレスポンスを返却
     *
     * @param Request $request
     * @return Response|bool
     * @throws InvalidArgumentException
     */
    public function getResponse($request)
    {
        $resource = $this->find('all', [
            'conditions' => [
                'Resources.url' => $request->url,
                'Resources.method' => $request->method,
            ],
        ])->first();

        $result = new Response();

        $result->code = 404;
        $result->header = null;
        $result->body = [
            'status' => 404,
            'message' => 'Not Found',
        ];

        if (empty($resource)) {
            return $result;
        }

        /** @var \App\Model\Entity\Resource $resource */
        $response = json_decode($resource->response, true);

        if (isset($response['code'])) {
            $result->code = Hash::get($response, 'code');
            $result->header = Hash::get($response, 'header');
            $result->body = Hash::get($response, 'body');
        } else {
            $count = $resource->count % count($response);

            $result->code = Hash::get($response, "${count}.code");
            $result->header = Hash::get($response, "${count}.header");
            $result->body = Hash::get($response, "${count}.body");
        }

        try {
            $resource = $this->patchEntity($resource, [
                'count' => ++$resource->count,
            ]);
            $this->save($resource);
        } catch (\Exception $e) {
            $this->log($e->getMessage(), 'debug');

            $result = false;
        }

        return $result;
    }
}
