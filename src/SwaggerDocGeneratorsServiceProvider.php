<?php

namespace Evolvo\SwaggerDocGenerators;

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
                GenerateCommentController::class
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
