# Swagger 5.7 auto comments generator for Laravel

## About
The `swagger-doc-generators` package allows you to create comments from validation classes for swagger documentation.



## Installation
Require the `evolvo/swagger-doc-generators` package in your `composer.json` and update your dependencies:
```sh
$ composer require evolvo/swagger-doc-generators "1.0.8"
```

add 
```sh
Evolvo\SwaggerDocGenerators\SwaggerDocGeneratorsServiceProvider::class,
```
to config/app.php 'providers' array.

####If you don't have swagger config and view files:

Run:
```sh
php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
```

then add 
```sh
L5Swagger\L5SwaggerServiceProvider::class,
```
to config/app.php 'providers' array.

Add to app/Http/Controllers/Controller.php:
```php
/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="L5 OpenApi",
 *      description="L5 Swagger OpenApi description",
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 */
/*
 * @SWG\SecurityScheme(
 *   securityDefinition="passport",
 *   type="oauth2",
 *   tokenUrl="/oauth/token",
 *   flow="password",
 *   scopes={}
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
```

add to .env file:

`L5_SWAGGER_GENERATE_ALWAYS=TRUE`.

Default preview route is `http://your-address.com/api/documentation`, but you can change it in configuration.

## Usage
Run `php artisan comment {METHOD::route}` to generate comment for route. You can specify multiple routes.

Run `php artisan comment:controller {controller}` to generate comments for whole controller.


## Examples
For single route
`php artisan comment GET::api/clients`

For multiple routes
`php artisan comment GET::api/clients POST::api/clients PUT::api/clients/{client}`

For controller
`php artisan comment:controller ClientsController`


## Custom documentation style
Run `php artisan swagger-custom-style:apply` to apply custom style.
After that go to `config/l5-swagger.php` and change style in `css` array.

Example
![alt text](src/images/custom-style-example.png)

Run `php artisan swagger-custom-style:remove` to remove custom style.

## Links and examples
Swagger PHP: [here]

More about swagger for laravel: [l5-swagger].

L5-swagger documentation example: [pet store].

Pet store code example: [link].

Our company: http://evolvo.eu.

[here]: http://zircote.com/swagger-php/
[l5-swagger]: https://github.com/DarkaOnLine/L5-Swagger
[pet store]: https://petstore.swagger.io/
[link]: https://github.com/zircote/swagger-php/tree/master/Examples/petstore-3.0




