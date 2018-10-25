<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RemoveCustomDocumentationStyle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swagger-custom-style:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove custom documentation style.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $configFilePath = config_path("l5-swagger.php");
        $configFile = file_get_contents($configFilePath);
        $viewFilePath = resource_path("views/vendor/l5-swagger/index.blade.php");
        $viewFile = file_get_contents($viewFilePath);

        if(!preg_match("/css/", $configFile) || !preg_match("/custom-style/", $viewFile)) {
            $this->error("There is nothing to remove. Apply custom documentation with: php artisan swagger-custom-style:apply.");
        } else {
            $removeConfig = preg_replace("/[\/][\/] Custom css(\n.*)*.Custom css end/", "", $configFile);
            file_put_contents($configFilePath, $removeConfig);

            $removeStyle = preg_replace("/{{-- Custom Style --}}(\r\n.*)*{{-- Custom Style end --}}/", "", $viewFile);
            file_put_contents($viewFilePath, $removeStyle);

            $viewFile = $removeStyle;

            $removeJs = preg_replace("/[\/][\/] # Custom JS(\r\n.*)*# Custom JS end/", "", $viewFile);
            file_put_contents($viewFilePath, $removeJs);
            $this->info("Custom documentation style removed.");
        }
    }
}
