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

        Route::macro('whitelist', function ($group = null) {
            return Macro::whitelist($this, $group);
        });

        $this->app['blade.compiler']->directive('routes', function ($group) {
           $group =  explode(', ', $group);

            $guard = isset($group[1]) ? ',' . trim($group[1]) : false;
            $group = trim($group[0]);

            return "<?php echo app('" . BladeRouteGenerator::class . "')->generate({$group}{$guard}); ?>";
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                CommandRouteGenerator::class,
            ]);
        }
    }
}
