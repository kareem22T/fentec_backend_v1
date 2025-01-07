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
            list($table, $headings, $conditions) = array_pad(explode(',', $expression), 3, 'null');

            // Convert headings and conditions to valid JSON strings
            $headings = trim($headings) === 'true' ? 'true' : 'false';
            $conditions = $conditions;

            $exportRoute = "<?php echo route('dynamic.export', [
        'table' => trim($table, \"'\"),
        'headings' => $headings,
        'conditions' => $conditions
    ]); ?>";

            return "<a href=\"{$exportRoute}\" style=\"font-size: 18px; font-weight: bold; color: #ff7300;\">Export {$table}</a>";
        });
    }
}
