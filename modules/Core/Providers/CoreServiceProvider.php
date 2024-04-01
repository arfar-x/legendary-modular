<?php

namespace Modules\Core\Providers;

use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected string $moduleName = 'Core';

    /**
     * @var string
     */
    protected string $moduleNameLower = 'core';

    /**
     * Configs files that should be registered.
     */
    protected array $configs = [
        'rate-limiter',
        'filter'
    ];

    /**
     * Any additional service-provider that should be injected rather than RouteServiceProvider.
     *
     * @var array|string[]
     */
    protected array $additionalServiceProviders = [
        RouteServiceProvider::class,
        ValidationServiceProvider::class,
    ];

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerMigrations();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        foreach ($this->additionalServiceProviders as $provider) {
            $this->app->register($provider);
        }
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
            $this->loadJsonTranslationsFrom(module_path($this->moduleName, 'Resources/lang'));
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [];
    }

    /**
     * Register configs.
     *
     * @return void
     */
    protected function registerConfig(): void
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'), $this->moduleNameLower
        );

        if ($this->configs) {
            foreach ($this->configs as $file) {
                $this->publishes([
                    module_path($this->moduleName, "Config/$file.php") => config_path($this->moduleNameLower . '.php'),
                ], 'config');
                $this->mergeConfigFrom(
                    module_path($this->moduleName, "Config/$file.php"), "$this->moduleNameLower.$file"
                );
            }
        }
    }

    /**
     * Register database migrations.
     *
     * @return void
     */
    protected function registerMigrations(): void
    {
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
    }
}
