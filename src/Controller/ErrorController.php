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

use Cake\Event\Event;
use Cake\Http\Response;

/**
 * Class ErrorController
 *
 * @package App\Controller
 */
class ErrorController extends AppController
{
    /**
     * Initialization hook method.
     *
     * @return void
     */
    public function initialize()
    {
        $this->loadComponent('RequestHandler');
    }

    /**
     * beforeFilter callback.
     *
     * @param Event $event Event.
     * @return Response|null|void
     */
    public function beforeFilter(Event $event)
    {
    }

    /**
     * beforeRender callback.
     *
     * @param Event $event Event.
     * @return Response|null|void
     */
    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        $this->viewBuilder()->setTemplatePath('Error');
    }

    /**
     * afterFilter callback.
     *
     * @param Event $event Event.
     * @return Response|null|void
     */
    public function afterFilter(Event $event)
    {
    }
}
