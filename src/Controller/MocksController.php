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

use App\Form\Mocks\AddForm;
use App\Form\Mocks\DeleteForm;
use App\Form\Mocks\EditForm;
use App\Form\Mocks\IndexForm;
use App\Form\Mocks\ViewForm;
use App\Model\Entity\Resource;
use App\Model\Table\ResourcesTable;
use Cake\Core\Configure;
use Cake\Datasource\Exception\MissingModelException;
use InvalidArgumentException;
use UnexpectedValueException;

/**
 * Mocks Controller
 *
 * @property ResourcesTable $Resources
 * @method Resource[] paginate($object = null, array $settings = [])
 */
class MocksController extends AppController
{
    /**
     * Initialization hook method.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        Configure::write('debug', false);

        $this->viewBuilder()->setLayout('');
        $this->viewBuilder()->enableAutoLayout(false);
        $this->autoRender = false;

        $this->loadComponent('RequestHandler');
    }

    /**
     * Index method
     *
     * @return void
     * @throws MissingModelException
     * @throws InvalidArgumentException
     * @throws UnexpectedValueException
     */
    public function index()
    {
        $this->request->allowMethod(['get']);

        $form = new IndexForm();

        $mocks = $form->execute([]);

        $this->response = $this->response
            ->withType('application/json')
            ->withCharset('UTF-8')
            ->withStringBody(json_encode(
                $mocks,
                JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES
            ));
    }

    /**
     * View method
     *
     * @param string|null $id
     * @return void
     */
    public function view($id = null)
    {
        $this->request->allowMethod(['get']);

        $form = new ViewForm();

        $entity = $form->execute(['id' => $id]);
        if ($entity === false) {
            $this->response = $this->response
                ->withType('application/json')
                ->withCharset('UTF-8')
                ->withStatus(404);

            return;
        }

        /** @var \App\Model\Entity\Resource $entity */
        $mock = $form->convertMock($entity);

        $this->response = $this->response
            ->withType('application/json')
            ->withCharset('UTF-8')
            ->withStringBody(json_encode(
                $mock,
                JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES
            ));
    }

    /**
     * Add method
     *
     * @return void
     */
    public function add()
    {
        $this->request->allowMethod(['post']);

        $form = new AddForm();

        $data = $form->encode($this->request->getData());

        if ($form->execute($data)) {
            $this->response = $this->response
                ->withType('application/json')
                ->withCharset('UTF-8')
                ->withStatus(201);

            return;
        }

        $errors = $form->errors();

        if (isset($errors['method']['isUniqueURLAndMethod'])) {
            $this->response = $this->response
                ->withType('application/json')
                ->withCharset('UTF-8')
                ->withStatus(409);
        } else {
            $this->response = $this->response
                ->withType('application/json')
                ->withCharset('UTF-8')
                ->withStatus(400);
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id
     * @return void
     */
    public function edit($id = null)
    {
        $this->request->allowMethod(['patch', 'put']);

        $form = new EditForm();

        $data = $form->encode($id, $this->request->input());

        if ($form->execute($data)) {
            $this->response = $this->response
                ->withType('application/json')
                ->withCharset('UTF-8')
                ->withStatus(200);

            return;
        }

        $errors = $form->errors();

        if (isset($errors['method']['isUniqueURLAndMethod'])) {
            $this->response = $this->response
                ->withType('application/json')
                ->withCharset('UTF-8')
                ->withStatus(409);
        } elseif (isset($errors['exception']) && ($errors['exception'] === 'Record not found in table "resources"')) {
            $this->response = $this->response
                ->withType('application/json')
                ->withCharset('UTF-8')
                ->withStatus(404);
        } else {
            $this->response = $this->response
                ->withType('application/json')
                ->withCharset('UTF-8')
                ->withStatus(400);
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id
     * @return void
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['delete']);

        $form = new DeleteForm();

        if ($form->execute(['id' => $id])) {
            $this->response = $this->response
                ->withType('application/json')
                ->withCharset('UTF-8')
                ->withStatus(200);
        } else {
            $this->response = $this->response
                ->withType('application/json')
                ->withCharset('UTF-8')
                ->withStatus(404);
        }
    }
}
