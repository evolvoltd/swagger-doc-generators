<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ApplyCustomDocumentationStyle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swagger-custom-style:apply';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Apply custom documentation style.';

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

        if(preg_match("/css/", $configFile) || preg_match("/custom-style/", $viewFile)) {
            $this->error("Custom style config is already specified. If you want to remove it use: php artisan swagger-custom-style:remove.");
        } else {
            $returnPosition = strpos($configFile, "return [");
            $styleConfigPosition = $returnPosition + 8;                             // 'return [' have 8 chars
            $newData = substr_replace($configFile, $this->styleConfig(), $styleConfigPosition, 0);
            file_put_contents($configFilePath, $newData);

            $styleEndPosition = strpos($viewFile, "</style>");
            $customStylePosition = $styleEndPosition + 8;
            $newStyle = substr_replace($viewFile, file_get_contents(app_path("Console/Commands/Templates/custom-style.tpl")), $customStylePosition, 0);
            file_put_contents($viewFilePath, $newStyle);

            $viewFile = $newStyle;

            $jsEndPosition = strpos($viewFile, "window.ui = ui;");
            $customJsPosition = $jsEndPosition + 15;
            $newJs = substr_replace($viewFile, file_get_contents(app_path("Console/Commands/Templates/custom-style-js.tpl")), $customJsPosition, 0);
            file_put_contents($viewFilePath, $newJs);

            $this->info("Custom style config added to config/l5-swagger.php file.");
        }
    }

    private function styleConfig()
    {
        return "
    // Custom css
    'css' => [
        'logo' => 'https://i.evolvo.eu/static/logo-2x.png',
        'top_bar_color' => '#212121',
        'body_color' => '#303030',
        'main_color' => '#424242',
        'get' => '#61affe',
        'post' => '#49cc90',
        'put' => '#fca130',
        'delete' => '#f93e3e',
        'head' => '#6b5b95',
        'patch' => \"#50e3c2\",
        'options' => '#DBB1CD'
    ],
    // Custom css end";
    }
}
