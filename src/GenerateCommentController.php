<?php

namespace Evolvo\SwaggerDocGenerators;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

class GenerateCommentController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'comment:controller {controller}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create comments for whole controller.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $controller = $this->argument('controller');

        try {
            $controllerPath = app_path("Http/Controllers/".$controller.".php");
            $controllerFile = file_get_contents($controllerPath);
            preg_match_all("/(public|public static) function(.*\()/", $controllerFile, $functions);
            $functions = $functions[2];
            $functions = array_map(function($value) {
                return substr($value, 1, -1);
            }, $functions);

            $calls = [];
            foreach (Route::getRoutes() as $index => $route) {
                $action = $route->action["controller"] ?? null;
                if(!is_null($action)) {
                    $controllerPath = explode('@', $action)[0];
                    $controllerName = explode("\\", $controllerPath);
                    $controllerName = $controllerName[count($controllerName) - 1];

                    $routeFunction = explode('@', $action)[1];

                    $controller = explode('/', $controller);
                    $controller = end($controller);

                    if($controllerName == $controller && in_array($routeFunction, $functions)) {
                        $calls[] = $route->methods[0]."::".$route->uri;
                        $functions = array_diff($functions, [$routeFunction]);
                    }
                }
            }
            Artisan::call("comment", ["calls" => $calls]);
            $this->info("Controller commented successfully.");
        } catch (\Exception $e) {
            $this->error("Error. Something went wrong.");
        }
    }
}
