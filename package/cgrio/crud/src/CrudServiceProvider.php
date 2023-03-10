<?php

namespace Cgrio\Crud;

use Illuminate\Support\ServiceProvider;

class CrudServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function boot()
    {
        //
       $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadViewsFrom(__DIR__.'/views', 'cgrio');

        $this->publishes([
            __DIR__.'/views/cgrio' => base_path('resources/views/cgrio'),
        ]);

        $this->publishes([
            __DIR__.'/views/assets' => base_path('resources'),
        ]);

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function register()
    {

        $commands = [
            'Cgrio\Crud\Commands\CgrioCrud',
        ];


            $this->commands($commands);

            $this->app->make('Cgrio\Crud\Controllers\CrudController');
    }
}
