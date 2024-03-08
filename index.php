<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Illuminate\Database\Capsule\Manager as Capsule;

require __DIR__ . '/vendor/autoload.php';

// Adatbázis inicializálása
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'sqlite',
    'database' => __DIR__ . '/database.sqlite',
    'prefix' => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

//todos tábla létrehozása
if (!Capsule::schema()->hasTable('todos')) {
    Capsule::schema()->create('todos', function ($table) {
        $table->increments('id');
        $table->string('title');
        $table->text('description')->nullable();
        $table->boolean('completed')->default(false);
        $table->timestamps();
    });
}

//Forrás: https://laravel.com/docs/5.0/schema - 2024.03.08 - 9:17 - Laravel

//Létrehozzuk az alkalmazást
$app = AppFactory::create();

//Definiáljuk a route-okat
$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello, World!");
    return $response;
});

//A Slim futtatása
$app->run();

//Forrás: https://www.slimframework.com/docs/v4/objects/application.html - 2024.03.08 - 8:07 - Slim Dokumentáció
