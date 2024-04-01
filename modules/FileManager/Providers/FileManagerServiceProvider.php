<?php

namespace Modules\FileManager\Providers;

use Modules\Core\Providers\CoreServiceProvider;

class FileManagerServiceProvider extends CoreServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected string $moduleName = 'FileManager';

    /**
     * @var string $moduleNameLower
     */
    protected string $moduleNameLower = 'file-manager';

    /**
     * Configs files that should be registered.
     */
    protected array $configs = [
        //
    ];

    /**
     * Any additional service-provider that should be injected rather than RouteServiceProvider.
     *
     * @var array|string[]
     */
    protected array $additionalServiceProviders = [
        RouteServiceProvider::class
    ];
}
