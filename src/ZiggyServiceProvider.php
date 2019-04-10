<?php

namespace Tightenco\Ziggy;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class ZiggyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Route::macro('blacklist', function ($group = null) {
            return Macro::blacklist($this, $group);
        });

        Route::macro('whitelist', function ($group = null, $guard = null) {
            return Macro::whitelist($this, $group);
        });

        $this->app['blade.compiler']->directive('routes', function ($group, $guard) {
            return "<?php echo app('" . BladeRouteGenerator::class . "')->generate({$group}, {$guard}); ?>";
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                CommandRouteGenerator::class,
            ]);
        }
    }
}
