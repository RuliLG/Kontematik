<?php

namespace App\Providers;

use App\Policies\TextGenerationPolicy;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //

        Gate::define('can-generate', [TextGenerationPolicy::class, 'generate']);

        Blade::directive('trans', function ($expression) {
            dd("echo (new \App\Services\Translation())->getOrTranslate(${$expression});");
            return "<?php echo (new \App\Services\Translation())->getOrTranslate(${$expression}); ?>";
        });
    }
}
