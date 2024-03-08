<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Illuminate\Database\Capsule\Manager as Capsule;

header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('HTTP/1.1 200 OK');
    exit();
}

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

// Todo osztály definiálása
// Todo osztály definiálása
class Todo extends \Illuminate\Database\Eloquent\Model
{
}

// GET kérés, a ToDo-k lekérése
$app->get('/api/todos', function (Request $request, Response $response, $args) {
    $todos = Todo::all()->toArray(); // Eloquent gyűjtemény konvertálása tömbbé
    $response->getBody()->write(json_encode($todos)); // JSON írása
    return $response->withHeader('Content-Type', 'application/json');
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
