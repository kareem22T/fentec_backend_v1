<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Exports\DynamicExport;
use Maatwebsite\Excel\Facades\Excel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('exportTable', function ($expression) {
            list($table, $headings) = array_pad(explode(',', $expression), 2, 'false');
            $exportRoute = "<?php echo route('dynamic.export', ['table' => trim($table, \"'\"), 'headings' => trim($headings)]); ?>";
            return "<a href=\"{$exportRoute}\" style=\"font-size: 18px; font-weight: bold; color: #ff7300;\">Export {$table}</a>";
        });
    }
}
