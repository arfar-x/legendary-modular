<?php

namespace Modules\FileManager\Providers;

use Modules\Core\Providers\RouteServiceProvider as CoreRouteServiceProvider;

class RouteServiceProvider extends CoreRouteServiceProvider
{
    /**
     * @var string
     */
    protected string $moduleName = 'FileManager';

    /**
     * The module namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected string $moduleNamespace = 'Modules\FileManager\Http\Controllers';

    /**
     * Module's specific middleware aliases.
     *
     * @var array
     */
    protected array $aliasMiddlewares = [
        //
    ];

    /**
     * Global middlewares that are applied to the entire application.
     *
     * @var array
     */
    protected array $globalMiddleware = [
        //
    ];

    /**
     * All these middlewares are applied to overall application middleware group.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'api' => [
            //
        ],
    ];
}
