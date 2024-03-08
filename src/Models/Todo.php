<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $table = 'todos'; // SQLite adatbázis táblának a neve
    protected $fillable = ['category', 'description']; //a Todo adatszerkezete
}

//Forrás: https://www.slimframework.com/docs/v3/tutorial/first-app.html 2024.03.08 10:46 - Slim dokumentáció