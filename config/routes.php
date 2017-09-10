<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Core\Plugin;
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 */
Router::defaultRouteClass(DashedRoute::class);

Router::scope('/', function (RouteBuilder $routes) {
    $routes->connect('/**', ['controller' => 'Resources', 'action' => 'execute']);

    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);`
     *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);`
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks(DashedRoute::class);
});

Router::scope('/mocks', function (RouteBuilder $routes) {
    $routes->connect('/', ['controller' => 'Mocks', 'action' => 'index', '_method' => 'GET']);
    $routes->connect('/:id', ['controller' => 'Mocks', 'action' => 'view', '_method' => 'GET'], ['id' => '\d+', 'pass' => ['id']]);
    $routes->connect('/', ['controller' => 'Mocks', 'action' => 'add', '_method' => 'POST']);
    $routes->connect('/:id', ['controller' => 'Mocks', 'action' => 'edit', '_method' => ['PATCH', 'PUT']], ['id' => '\d+', 'pass' => ['id']]);
    $routes->connect('/:id', ['controller' => 'Mocks', 'action' => 'delete', '_method' => 'DELETE'], ['id' => '\d+', 'pass' => ['id']]);
});

Router::scope('/resources', function (RouteBuilder $routes) {
    $routes->connect('/:id', ['controller' => 'Resources', 'action' => 'view'], ['id' => '\d+', 'pass' => ['id']]);
    $routes->connect('/add', ['controller' => 'Resources', 'action' => 'add']);
    $routes->connect('/edit/:id', ['controller' => 'Resources', 'action' => 'edit'], ['id' => '\d+', 'pass' => ['id']]);
    $routes->connect('/delete/:id', ['controller' => 'Resources', 'action' => 'delete'], ['id' => '\d+', 'pass' => ['id']]);
    $routes->connect('/', ['controller' => 'Resources', 'action' => 'index']);

    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);`
     *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);`
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks(DashedRoute::class);
});

/**
 * Load all plugin routes. See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
Plugin::routes();
