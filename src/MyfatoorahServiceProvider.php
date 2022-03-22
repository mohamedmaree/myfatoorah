<?php

namespace maree\myfatoorah;

use Illuminate\Support\ServiceProvider;

class MyfatoorahServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->publishes([
            __DIR__.'/config/myfatoorah.php' => config_path('myfatoorah.php'),
        ],'myfatoorah');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/myfatoorah.php', 'myfatoorah'
        );
    }
}
