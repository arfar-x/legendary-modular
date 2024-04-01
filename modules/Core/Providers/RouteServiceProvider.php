<?php

namespace Modules\Core\Providers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Middleware\ResponseFormat;
use Modules\Core\Http\Middleware\ThrottleRequests as CoreThrottleRequests;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected string $moduleName = 'Core';

    /**
     * The module namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected string $moduleNamespace = 'Modules\Core\Http\Controllers';

    /**
     * Global middlewares that are applied to the entire application.
     *
     * @var array
     */
    protected array $globalMiddleware = [
        ResponseFormat::class
    ];

    /**
     * All these middlewares are applied to overall application middleware group.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'api' => [
            CoreThrottleRequests::class,
        ],
    ];

    /**
     * Module's specific middleware aliases.
     *
     * @var array
     */
    protected array $aliasMiddlewares = [
        'throttle' => CoreThrottleRequests::class,
    ];

    /**
     * Define the routes for the application.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function map(): void
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->registerMiddlewares();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes(): void
    {
        if (file_exists(module_path($this->moduleName, 'Routes/web.php'))) {
            Route::middleware('web')
                ->namespace($this->moduleNamespace)
                ->group(module_path($this->moduleName, 'Routes/web.php'));
        }
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes(): void
    {
        if (file_exists(module_path($this->moduleName, 'Routes/api.php'))) {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->moduleNamespace)
                ->group(module_path($this->moduleName, 'Routes/api.php'));
        }
    }

    /**
     * Register the module's middlewares.
     *
     * @return void
     * @throws BindingResolutionException
     */
    protected function registerMiddlewares(): void
    {
        // Register global middlewares.
        if (!empty($this->globalMiddleware)) {
            $kernel = $this->app->make(Kernel::class);
            foreach ($this->globalMiddleware as $middleware) {
                $kernel->pushMiddleware($middleware);
            }
        }

        // Register alias middlewares.
        foreach ($this->aliasMiddlewares as $alias => $middleware) {
            $this->aliasMiddleware($alias, $middleware);
        }

        // Register middleware groups.
        foreach (array_keys($this->middlewareGroups) as $group) {
            foreach ($this->middlewareGroups[$group] ?? [] as $middleware) {
                $this->pushMiddlewareToGroup($group, $middleware);
            }
        }
    }
}
