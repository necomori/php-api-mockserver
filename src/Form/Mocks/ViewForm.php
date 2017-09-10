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

use App\ValueObject\Mock;

/**
 * リソースの情報表示処理を行う
 *
 * @package App\Form\Mocks
 */
class ViewForm extends \App\Form\Resources\ViewForm
{
    /**
     * リソースをValueObjectに詰め直す
     *
     * @param \App\Model\Entity\Resource $resource
     * @return Mock
     */
    public function convertMock($resource)
    {
        if ($resource === null) {
            return null;
        }

        $mock = new Mock();
        $mock->url = $resource->url;
        $mock->method = $resource->method;
        $mock->response = json_decode($resource->response, true);

        return $mock;
    }
}
