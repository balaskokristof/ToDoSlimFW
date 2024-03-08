<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Models\Todo;

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
        $table->string('category');
        $table->text('description');
        $table->timestamps();
    });
}

// Slim alkalmazás létrehozása
$app = AppFactory::create();

// GET kérés, a ToDo-k lekérése
$app->get('/api/todos', function (Request $request, Response $response, $args) {
    $todos = Todo::all();
    return $response->withJson($todos);
});

// POST kérés, új ToDo létrehozása
$app->post('/api/todos', function (Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $todo = new Todo;
    $todo->category = $data['category'] ?? '';
    $todo->description = $data['description'] ?? '';
    $todo->save();
    return $response->withJson(['message' => 'Todo sikeresen hozzáadva!']);
});

// PUT kérés, ToDo módosítása
$app->put('/api/todos/{id}', function (Request $request, Response $response, $args) {
    $id = $args['id'];
    $data = $request->getParsedBody();
    $todo = Todo::find($id);
    if (!$todo) {
        return $response->withStatus(404)->withJson(['error' => 'Todo nem található']);
    }
    $todo->category = $data['category'] ?? $todo->category;
    $todo->description = $data['description'] ?? $todo->description;
    $todo->save();
    return $response->withJson(['message' => 'Todo sikeresen módosítva!']);
});

// DELETE kérés, ToDo törlése
$app->delete('/api/todos/{id}', function (Request $request, Response $response, $args) {
    $id = $args['id'];
    $todo = Todo::find($id);
    if (!$todo) {
        return $response->withStatus(404)->withJson(['error' => 'Todo nem található']);
    }
    $todo->delete();
    return $response->withJson(['message' => 'Todo sikeresen törölve!']);
});

//A Slim futtatása
$app->run();
//Forrás: https://akrabat.com/receiving-input-into-a-slim-4-application/ 2024.03.08 11:26 - Rob Allen
//Forrás: https://laravel.com/docs/5.0/schema - 2024.03.08 - 9:17 - Laravel
//Forrás: https://www.slimframework.com/docs/v4/objects/application.html - 2024.03.08 - 8:07 - Slim Dokumentáció
