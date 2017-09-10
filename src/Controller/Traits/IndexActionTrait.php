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

use Cake\Datasource\Exception\MissingModelException;
use Cake\Datasource\RepositoryInterface;
use Cake\ORM\Entity;
use Cake\Utility\Inflector;
use InvalidArgumentException;
use UnexpectedValueException;

/**
 * IndexAction を追加する
 *
 * @method RepositoryInterface loadModel($modelClass = null, $modelType = null)
 * @method Entity[] paginate($object = null, array $settings = [])
 *
 * @package App\Controller\Traits
 */
trait IndexActionTrait
{
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
        $table = $this->loadModel();
        $tableAlias = $table->getAlias();
        $alias = Inflector::tableize($tableAlias);

        $this->set($alias, $this->paginate($table));
    }
}
