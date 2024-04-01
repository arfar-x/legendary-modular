<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Nwidart\Modules\Laravel\Module;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database by running each module's seeder.
     *
     * @throws Exception
     */
    public function run(): void
    {
        $modulesPrioritized = [];

        // Prepare all enabled modules to run their seeders.
        foreach ($this->getAllModules() as $module) {
            $path = "modules/{$module}";
            $namespace = "\\Modules\\{$module}";
            $moduleObj = new Module(app(), $module, $path);
            if ($moduleObj->isEnabled()) {
                $seederClass = "$namespace\\Database\\Seeders\\{$moduleObj}DatabaseSeeder";
                $seederPath = "$path/Database/Seeders/{$moduleObj}DatabaseSeeder.php";
                if (file_exists($seederPath)) {
                    $modulesPrioritized[$module] = [
                        "$module::class" => $seederClass,
                        "$module::path" => $seederPath
                    ];
                }
            }
        }

        // We sort the modules' seeders based on their priorities.
        uasort($modulesPrioritized, function ($a, $b) {
            $moduleAName = explode('::', array_keys($a)[1])[0];
            $moduleBName = explode('::', array_keys($b)[1])[0];
            $a = new Module(app(), $moduleAName, "modules/{$moduleAName}");
            $b = new Module(app(), $moduleBName, "modules/{$moduleBName}");
            if ((int)$a->getPriority() == (int)$b->getPriority()) return 0;
            return ((int)$a->getPriority() < (int)$b->getPriority()) ? -1 : 1;
        });

        // Run seeders for all sorted modules.
        foreach ($modulesPrioritized as $name => $module) {
            $namespace = "\\Modules\\{$name}";
            $seederClass = "$namespace\\Database\\Seeders\\{$name}DatabaseSeeder";
            try {
                Artisan::call('db:seed', [
                    '--class' => $seederClass
                ]);
            } catch (Exception $e) {
                throw new Exception(
                    "Module '$name' seeding error: {$e->getMessage()}",
                    (int)$e->getCode(),
                    $e->getPrevious()
                );
            }
        }
    }

    /**
     * List all modules within 'modules/' directory.
     *
     * @return array
     */
    protected function getAllModules(): array
    {
        $modules = [];
        if ($handle = opendir(config('modules.paths.modules'))) {
            while (false !== ($module = readdir($handle))) {
                if ($module != "." && $module != "..") {
                    $modules[] = $module;
                }
            }
            closedir($handle);
        }

        return $modules;
    }
}
