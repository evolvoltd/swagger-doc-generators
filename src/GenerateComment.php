<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Faker\Factory;

class GenerateComment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'comment {calls*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create comment for methods.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $calls = $this->argument('calls');

        for($i = 0; $i < count($calls); $i++) {
            $ex = explode('::', $calls[$i]);
            if(count($ex) != 2) {
                $this->error('Route should be like this: POST::api/clients');
                exit();
            } else {
                $calls[$i] = ["method" => $ex[0], "url" => $ex[1]];
            }
        }
        $urls = array_column($calls, "url");

        $routes = [];
        $filteredUrls = [];
        $k = 0;

        foreach (Route::getRoutes() as $index => $route) {
            if($k < count($urls)) {
                if(in_array($route->uri, $urls) && in_array($calls[$k]["method"], $route->methods)) {
                    $action = $route->action["controller"] ?? null;
                    $routes[$k]["methods"] = $route->methods;
                    $routes[$k]["uri"] = $route->uri ?? null;
                    $routes[$k]["controller"] = !is_null($action) ? explode('@', $action)[0] : null;
                    $routes[$k]["function"] = !is_null($action) ? explode('@', $action)[1] : null;

                    $filteredUrls[] = $route->uri;
                    $k++;
                }
            }
        }

        if(count(array_intersect($filteredUrls, $urls)) != count($urls) || empty($urls)) {
            $this->error('Route not found.');
        } else {
            foreach ($routes as $route) {
                $classFileName = str_replace("\\", "/", $route["controller"].".php");
                $classFile = file_get_contents(base_path($classFileName));
                $routeFunction = $route["function"];
                $routeUri = $route["uri"];
                $routeMethod = $route["methods"][0];

                $functionPosition = strpos($classFile, "function ".$routeFunction."(");
                $commentPosition = substr($classFile, $functionPosition - 7, 15) == "public function" ? 12 : 19;
                $commentPosition = $functionPosition - $commentPosition;

                preg_match_all("/App\\\Http\\\Requests.*/", $classFile, $allRequests);
                $allRequests = $allRequests[0];
                $requests = [];
                for($i = 0; $i < count($allRequests); $i++) {
                    $allRequests[$i] = substr_replace(trim($allRequests[$i]), "", -1);
                    $ex = explode('\\', $allRequests[$i]);
                    $requests[] = end($ex);
                }

                preg_match("/function $routeFunction(| )\((.*?)\)/", $classFile, $parameters);
                $parameters = explode(" ", $parameters[2]);
                $parameters = array_filter($parameters, function($str) {
                    return substr($str, 0, 1) != "$";
                });
                $validationFile = $this->validationFile($allRequests, $requests, $parameters);

                if($validationFile != "\\") {
                    $validationFile = new $validationFile;
                    $rules = $validationFile->rules();
                } else {
                    $rules = [];
                }

                preg_match("/\{(.*?)\}/", $routeUri, $path);
                $path = empty($path) ? null : $path[1];

                $dataWithComment = substr_replace($classFile, $this->template
                (
                    $routeUri,
                    $rules,
                    $path,
                    $routeMethod
                ),
                    $commentPosition, 0);
                file_put_contents($classFileName, $dataWithComment);
            }
            $this->info("Comment created successfully.");
        }

    }

    private function validationFile($allRequests, $requests, $parameters)
    {
        $i = 0;
        $validationFile = null;
        foreach ($requests as $request) {
            foreach ($parameters as $parameter) {
                if($request == $parameter) {
                    $validationFile = $allRequests[$i];
                }
            }
            $i++;
        }
        return "\\".$validationFile;
    }


    /*
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     * TEMPLATE
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     */
    private function template($mainPath, $rules = [], $path = null, $method)
    {
        $parameters = $method == "GET" ? $this->queryParameters($rules, $path) : $this->bodyParameters($rules, $path);
        return
    "
    /**
    *   @OA\\".$method."(
    *       path=\"/$mainPath\",
    *       tags={\"$mainPath\"},
    *       security={
    *           {\"passport\": {}},
    *       },
    *       summary=\"Short summary\",
    *       description=\"Description\","
    .$parameters. "
    *       @OA\Response(
    *           response=200,
    *           description=\"successful operation\"
    *       ),
    *       @OA\Response(response=400, description=\"Bad request\"),
    *       @OA\Response(response=404, description=\"Resource Not Found\")
    *   )
    */";
    }

    private function queryParameters($rules, $path)
    {
        $rulesKeys = array_keys($rules);
        $comment = "";

        if(!is_null($path)) {
            $comment .= "
    *       @OA\Parameter(
    *           name=\"$path\",
    *           description=\"$path-description\",
    *           required=true,
    *           in=\"path\",
    *           @OA\Schema(
    *               type=\"integer|string\"
    *           ),
    *       ),";
        }

        foreach ($rulesKeys as $rule) {
            $exRule = explode('|', $rules[$rule]);
            $isRequired = in_array("required", $exRule) ? "true" : "false";
            $type = $this->defineType($exRule);

            preg_match("/in:.*/", $rules[$rule], $in);
            $in = $this->inItems($in);
            if(!is_null($in)) {
                $in = implode(",", $in);
                $in = "
    *               @OA\Items(
    *                   enum={{$in}},
    *               ),";
            } else { $in = ""; }

            $comment .= "
    *       @OA\Parameter(
    *           name=\"$rule\",
    *           description=\"$rule-description\",
    *           required=".$isRequired.",
    *           in=\"query\",
    *           @OA\Schema(
    *               type=\"$type\",$in
    *           ),
    *       ),";
        }

        return $comment;
    }

    private function bodyParameters($rules, $path)
    {
        $rulesKeys = array_keys($rules);
        $properties = "";
        $required = [];
        $formData = false;
        $faker = Factory::create();
        foreach ($rulesKeys as $rule) {

            $exRule = explode('|', $rules[$rule]);
            $type = $this->defineType($exRule);
            $example = $this->makeExample($type, $faker);

            preg_match("/in:.*/", $rules[$rule], $in);
            $in = $this->inItems($in);
            if(!is_null($in)) {
                $example = $in[0];
                $in = implode(",", $in);
                $in = "*\t\t\t\t\t\titems={".$in."},\n\t";
            } else { $in = ""; }

            $isRequired = in_array("required", $exRule) ? true : false;
            $properties .= "
    *                   @OA\Property(
    *                       property=\"$rule\",
    *                       description=\"$rule - description\",
    *                       type=\"$type\",
    *                       example={$example},
    "
    .$in.
    "*                   ),";
            if($isRequired) {
                $required[] = '"'.$rule.'"';
            }

            if($type == "file") { $formData = true; }
        }

        if($formData) {
            $requestType = "multipart/form-data";
        } else {
            $requestType = "application/json";
        }

        if(!is_null($path)) {
            $path = "
    *       @OA\Parameter(
    *           name=\"$path\",
    *           description=\"$path-description\",
    *           required=true,
    *           in=\"path\",
    *           @OA\Schema(
    *               type=\"integer|string\"
    *           ),
    *       ),";
        } else { $path = ""; }

        $comment = "";
        $isBodyRequired = count($required) != 0 ? "true" : "false";
        $required = implode(",", $required);
        $comment .= "
    *       @OA\RequestBody(
    *           description=\"Request body description\",
    *           required={$isBodyRequired},
    *           @OA\MediaType(
    *           mediaType=\"$requestType\",
    *               @OA\Schema(
    *                   required={{$required}},"
                        .$properties."
    *               )
    *           )
    *       ),
    *       {$path}
        ";

        return $comment;
    }

    private function defineType($exRules)
    {
        $availableTypes = ["integer", "string", "date", "boolean", "email", "nullable", "array", "file"];
        $types = array_intersect($exRules, $availableTypes);
        if(empty($types)) {
            return "string";
        } else {
            return implode(', ', $types);
        }
    }

    private function inItems(array $rule)
    {
        if(!empty($rule)) {
            $rule = str_replace(['in:', ' '], '', $rule);
            $rules = explode("," , $rule[0]);
            $rules = array_map(function($rule) {
                return '"'.$rule.'"';
            }, $rules);
            return $rules;
        } else {
            return null;
        }

    }

    private function makeExample($type, $faker)
    {
        switch ($type) {
            case "integer":
                return $faker->numberBetween(1, 100);
            case "string":
                return '"'.$faker->word.'"';
            case "date":
                return '"'.$faker->date().'"';
            case "boolean":
                return $faker->boolean == true ? "true" : "false";
            case "email":
                return '"'.$faker->email.'"';
            case "file":
                return '"folder/'.$faker->fileExtension.'.file"';
            case "phone":
                return '"'.$faker->phoneNumber.'"';
            case "nullable":
                return null;
            default:
                return '""';
        }
    }

}
