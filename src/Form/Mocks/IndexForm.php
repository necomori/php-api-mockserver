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

use App\Form\AppForm;
use App\ValueObject\Mock;

/**
 * リソースの一覧表示処理を行う
 *
 * @package App\Form\Mocks
 */
class IndexForm extends AppForm
{
    /** @var \App\Model\Table\ResourcesTable */
    protected $Resources;

    /**
     * リソースの配列をValueObjectの配列に詰めて返却
     *
     * @param array $data
     * @return array
     */
    public function execute(array $data)
    {
        $this->loadModel('Resources');

        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ]);

        if ($resources === null) {
            return [];
        }

        $mocks = [];
        foreach ($resources as $resource) {
            $mock = new Mock();
            $mock->id = $resource->id;
            $mock->url = $resource->url;
            $mock->method = $resource->method;
            $mock->response = json_decode($resource->response, true);

            $mocks[] = $mock;
        }

        return $mocks;
    }
}
