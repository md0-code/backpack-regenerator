<?php

namespace MD0\BackpackReGenerator;

use Illuminate\Support\ServiceProvider;

class BackpackReGeneratorServiceProvider extends ServiceProvider
{
    protected $vendorName = 'md0';
    protected $packageName = 'backpack-regenerator';
    protected $commands = [];

    public function __construct($app)
    {
        $this->app = $app;
        $this->path = __DIR__ . '/..';
    }

    public function boot(): void
    {
        if ($this->packageDirectoryExistsAndIsNotEmpty('resources/lang')) {
            $this->loadJsonTranslationsFrom($this->packageLangsPath(), $this->vendorNameDotPackageName());
            $this->loadJsonTranslationsFrom($this->publishedLangsPath(), $this->vendorNameDotPackageName());
        }

        if ($this->packageDirectoryExistsAndIsNotEmpty('resources/views')) {
            $this->viewsPath = $this->packageViewsPath();
        }
        if (file_exists($this->publishedViewsPath())) {
            $this->viewsPath = $this->publishedViewsPath();
        }
        $this->loadViewsFrom($this->viewsPath, $this->vendorNameDotPackageName());

        if ($this->packageDirectoryExistsAndIsNotEmpty('database/migrations')) {
            $this->loadMigrationsFrom($this->packageMigrationsPath());
        }

        if ($this->packageDirectoryExistsAndIsNotEmpty('routes')) {
            if (file_exists($this->publishedRoutesFile())) $this->loadRoutesFrom($this->publishedRoutesFile());
            else $this->loadRoutesFrom($this->packageRoutesFile());
        }

        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    public function register(): void
    {
        if ($this->packageDirectoryExistsAndIsNotEmpty('config')) {
            $this->mergeConfigFrom($this->packageConfigFile(), $this->vendorNameDotPackageName());
        }
    }

    protected function bootForConsole(): void
    {
        if ($this->packageDirectoryExistsAndIsNotEmpty('config')) {
            $this->publishes([
                $this->packageConfigFile() => $this->publishedConfigFile(),
            ], 'config');
        }

        if ($this->packageDirectoryExistsAndIsNotEmpty('resources/views')) {
            $this->publishes([
                $this->packageViewsPath() => $this->publishedViewsPath(),
            ], 'views');
        }

        if ($this->packageDirectoryExistsAndIsNotEmpty('resources/assets')) {
            $this->publishes([
                $this->packageAssetsPath() => $this->publishedAssetsPath(),
            ], 'assets');
        }

        if ($this->packageDirectoryExistsAndIsNotEmpty('resources/lang')) {
            $this->publishes([
                $this->packageLangsPath() => $this->publishedLangsPath(),
            ], 'lang');
        }

        if ($this->packageDirectoryExistsAndIsNotEmpty('routes')) {
            $this->publishes([
                $this->packageRoutesFile() => $this->publishedRoutesFile(),
            ], 'routes');
        }

        if (!empty($this->commands)) {
            $this->commands($this->commands);
        }
    }

    protected function vendorNameDotPackageName()
    {
        return $this->vendorName . '.' . $this->packageName;
    }

    protected function vendorNameSlashPackageName()
    {
        return $this->vendorName . '/' . $this->packageName;
    }

    protected function packageViewsPath()
    {
        return $this->path . '/resources/views';
    }

    protected function packageLangsPath()
    {
        return $this->path . '/resources/lang';
    }

    protected function packageAssetsPath()
    {
        return $this->path . '/resources/assets';
    }

    protected function packageMigrationsPath()
    {
        return $this->path . '/database/migrations';
    }

    protected function packageConfigFile()
    {
        return $this->path . '/config/' . $this->packageName . '.php';
    }

    protected function packageRoutesFile()
    {
        return $this->path . '/routes/' . $this->packageName . '.php';
    }

    protected function packageHelpersFile()
    {
        return $this->path . '/bootstrap/helpers.php';
    }




    protected function publishedViewsPath()
    {
        return base_path('resources/views/vendor/' . $this->vendorName . '/' . $this->packageName);
    }

    protected function publishedConfigFile()
    {
        return config_path($this->vendorNameSlashPackageName() . '.php');
    }

    protected function publishedAssetsPath()
    {
        return public_path('vendor/' . $this->vendorNameSlashPackageName());
    }

    protected function publishedLangsPath()
    {
        return resource_path('lang/vendor/' . $this->packageName);
    }

    protected function publishedRoutesFile()
    {
        return base_path('routes/' . $this->packageName . '.php');
    }




    protected function packageDirectoryExistsAndIsNotEmpty($name)
    {
        // check if directory exists
        if (!is_dir($this->path . '/' . $name)) {
            return false;
        }

        // check if directory has files
        foreach (scandir($this->path . '/' . $name) as $file) {
            if ($file != '.' && $file != '..' && $file != '.gitkeep') {
                return true;
            }
        }

        return false;
    }
}
