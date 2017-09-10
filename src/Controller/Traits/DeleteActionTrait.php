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
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Utility\Inflector;
use InvalidArgumentException;
use UnexpectedValueException;

/**
 * DeleteAction を追加する
 *
 * @method RepositoryInterface loadModel($modelClass = null, $modelType = null)
 *
 * @package App\Controller\Traits
 */
trait DeleteActionTrait
{
    /**
     * Delete method
     *
     * @param string|null $id
     * @return Response Redirects to index.
     * @throws MissingModelException
     * @throws InvalidArgumentException
     * @throws UnexpectedValueException
     * @throws MethodNotAllowedException
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $table = $this->loadModel();
        $tableAlias = $table->getAlias();
        $singularAlias = Inflector::singularize(Inflector::humanize($tableAlias));

        /** @var AppForm $form */
        $class = sprintf('\App\Form\%s\DeleteForm', $tableAlias);
        $form = new $class();

        if ($form->execute(['id' => $id])) {
            $this->Flash->success(__('The {0} has been deleted', $singularAlias));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', $singularAlias));
        }

        return $this->redirect(['action' => 'index']);
    }
}
