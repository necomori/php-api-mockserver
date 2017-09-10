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
use App\ValueObject\Request;
use App\ValueObject\Response;

/**
 * APIのモック動作を行う
 *
 * @package App\Form\Mocks
 */
class ExecuteForm extends AppForm
{
    /** @var \App\Model\Table\ResourcesTable */
    protected $Resources;

    /**
     * リソースの配列をValueObjectの配列に詰めて返却
     *
     * @param array $data
     * @return Response|bool
     */
    public function execute(array $data)
    {
        $this->loadModel('Resources');

        return $this->Resources->getResponse(new Request());
    }
}
