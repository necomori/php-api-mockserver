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

namespace App\Controller;

use App\Controller\Traits\AddActionTrait;
use App\Controller\Traits\DeleteActionTrait;
use App\Controller\Traits\EditActionTrait;
use App\Controller\Traits\IndexActionTrait;
use App\Controller\Traits\ViewActionTrait;
use App\Form\AppForm;
use App\Model\Entity\Resource;
use App\Model\Table\ResourcesTable;
use App\ValueObject\Request;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Http\Response;

/**
 * Resources Controller
 *
 * @property ResourcesTable $Resources
 * @method Resource[] paginate($object = null, array $settings = [])
 */
class ResourcesController extends AppController
{
    use AddActionTrait;
    use DeleteActionTrait;
    use EditActionTrait;
    use IndexActionTrait;
    use ViewActionTrait;

    public $paginate = [
        'limit' => 10,
        'order' => [
            'Resources.url' => 'asc',
        ],
    ];

    /**
     * Initialization hook method.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');

        if ($this->request->action === 'execute') {
            Configure::write('debug', false);

            $this->viewBuilder()->setLayout('');
            $this->viewBuilder()->enableAutoLayout(false);
            $this->autoRender = false;
        } else {
            $this->viewBuilder()->setLayout('AdminLTE.default');
            $this->loadComponent('Flash');
            $this->loadComponent('Security');
            $this->loadComponent('Csrf');
        }
    }

    /**
     * Before render callback.
     *
     * @param Event $event The beforeRender event.
     * @return Response|null|void
     */
    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        if ($this->request->action !== 'execute') {
            $this->viewBuilder()->setTheme('AdminLTE');
            $this->viewBuilder()->setClassName('AdminLTE.AdminLTE');
        }
    }

    /**
     * APIのモック動作を行う
     *
     * @throws \InvalidArgumentException
     */
    public function execute()
    {
        $this->loadModel('Resources');

        $request = new Request();
        $response = $this->Resources->getResponse($request);

        $this->response = $this->response
            ->withStatus($response->code)
            ->withType('application/json')
            ->withCharset('UTF-8');

        if (!empty($response->header) && is_array($response->header)) {
            /** @var array $header */
            $header = $response->header;
            foreach ($header as $key => $value) {
                $this->response = $this->response
                    ->withHeader($key, $value);
            }
        }

        $this->response = $this->response
            ->withStringBody(json_encode(
                $response->body,
                JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES
            ));
    }

    /**
     * AddAction のアプリケーションルールで発生したエラーに対する処理を行う
     *
     * @param AppForm $form
     * @return AppForm
     */
    protected function addErrorsForAddAction($form)
    {
        $errors = $form->errors();

        if (isset($errors['method']['isUniqueURLAndMethod'])) {
            $form->addError('method', ['isUnique' => __('Request Method for URL already exists')]);
        }

        return $form;
    }

    /**
     * EditAction のアプリケーションルールで発生したエラーに対する処理を行う
     *
     * @param AppForm $form
     * @return AppForm
     */
    protected function addErrorsForEditAction($form)
    {
        return $this->addErrorsForAddAction($form);
    }
}
