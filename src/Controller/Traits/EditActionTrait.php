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

namespace App\Controller\Traits;

use App\Form\AppForm;
use Cake\Datasource\Exception\MissingModelException;
use Cake\Datasource\RepositoryInterface;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Response;
use Cake\Utility\Inflector;
use InvalidArgumentException;
use UnexpectedValueException;

/**
 * EditAction を追加する
 *
 * @method RepositoryInterface loadModel($modelClass = null, $modelType = null)
 * @method AppForm addErrorsForEditAction(AppForm $form, array $errors)
 *
 * @package App\Controller\Traits
 */
trait EditActionTrait
{
    /**
     * Edit method
     *
     * @param string|null $id
     * @return Response|null
     * @throws MissingModelException
     * @throws InvalidArgumentException
     * @throws UnexpectedValueException
     * @throws NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $table = $this->loadModel();
        $tableAlias = $table->getAlias();
        $singularAlias = Inflector::singularize(Inflector::humanize($tableAlias));

        /** @var AppForm $form */
        $class = sprintf('\App\Form\%s\EditForm', $tableAlias);
        $form = new $class();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $data['id'] = $id;
            if ($form->execute($data)) {
                $this->Flash->success(__('The {0} has been saved', $singularAlias));

                return $this->redirect(['action' => 'index']);
            }

            $form = $this->addErrorsForEditAction($form);

            $this->Flash->error(__('The {0} could not be saved. Please, try again.', $singularAlias));
        } else {
            /** @var AppForm $viewForm */
            $class = sprintf('\App\Form\%s\ViewForm', $tableAlias);
            $viewForm = new $class();

            $entity = $viewForm->execute(['id' => $id]);
            if ($entity === false) {
                throw new NotFoundException();
            }

            /** @var \Cake\ORM\Entity $entity */
            $this->request = $form->withData($this->request, $entity);
        }

        $this->set(compact('form'));
    }
}
