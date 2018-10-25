<?php

namespace Evolvo\LaravelCodeGenerators;

use App\Console\Commands\ApplyCustomDocumentationStyle;
use App\Console\Commands\GenerateComment;
use App\Console\Commands\GenereateCommentController;
use App\Console\Commands\RemoveCustomDocumentationStyle;
use Illuminate\Support\ServiceProvider;

class SwaggerDocGeneratorsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ApplyCustomDocumentationStyle::class,
                RemoveCustomDocumentationStyle::class,
                GenerateComment::class,
                GenereateCommentController::class
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
