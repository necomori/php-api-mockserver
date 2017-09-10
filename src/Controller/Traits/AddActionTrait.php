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
use Cake\Http\Response;
use Cake\Utility\Inflector;
use InvalidArgumentException;
use UnexpectedValueException;

/**
 * AddAction を追加する
 *
 * @method RepositoryInterface loadModel($modelClass = null, $modelType = null)
 * @method AppForm addErrorsForAddAction(AppForm $form, array $errors)
 *
 * @package App\Controller\Traits
 */
trait AddActionTrait
{
    /**
     * Add method
     *
     * @return Response|null
     * @throws MissingModelException
     * @throws InvalidArgumentException
     * @throws UnexpectedValueException
     */
    public function add()
    {
        $table = $this->loadModel();
        $tableAlias = $table->getAlias();
        $singularAlias = Inflector::singularize(Inflector::humanize($tableAlias));

        /** @var AppForm $form */
        $class = sprintf('\App\Form\%s\AddForm', $tableAlias);
        $form = new $class();

        if ($this->request->is('post')) {
            if ($form->execute($this->request->getData())) {
                $this->Flash->success(__('The {0} has been saved', $singularAlias));

                return $this->redirect(['action' => 'index']);
            }

            $form = $this->addErrorsForAddAction($form);

            $this->Flash->error(__('The {0} could not be saved. Please, try again.', $singularAlias));
        }

        $this->set(compact('form'));
    }
}
