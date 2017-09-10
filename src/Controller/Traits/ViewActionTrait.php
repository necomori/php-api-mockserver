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
use Cake\Utility\Inflector;
use InvalidArgumentException;
use UnexpectedValueException;

/**
 * ViewAction を追加する
 *
 * @method RepositoryInterface loadModel($modelClass = null, $modelType = null)
 *
 * @package App\Controller\Traits
 */
trait ViewActionTrait
{
    /**
     * View method
     *
     * @param string|null $id
     * @return void
     * @throws MissingModelException
     * @throws InvalidArgumentException
     * @throws UnexpectedValueException
     * @throws NotFoundException
     */
    public function view($id = null)
    {
        $table = $this->loadModel();
        $tableAlias = $table->getAlias();
        $alias = Inflector::singularize(Inflector::tableize($tableAlias));

        /** @var AppForm $form */
        $class = sprintf('\App\Form\%s\ViewForm', $tableAlias);
        $form = new $class();

        $entity = $form->execute(['id' => $id]);
        if ($entity === false) {
            throw new NotFoundException();
        }

        $this->set($alias, $entity);
    }
}
