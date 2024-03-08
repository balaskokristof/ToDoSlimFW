# ToDo Lista alkalmazás

Ez a projekt egy egyszerű ToDo lista alkalmazást valósít meg, amely lehetővé teszi, hogy teendőket hozz létre, modosítsad őket, és törölheted is őket.

## Telepítés

1. Klónozd le a GitHub repository-t:

   ```
   git clone https://github.com/balaskokristof/ToDoSlimFW
   ```

2. Navigálj a projekt könyvtárába:

   ```
   cd todo-lista
   ```

3. Telepítsd a szükséges függőségeket a Composer segítségével:

   ```
   composer install
   ```

4. Indítsd el a beépített PHP szerverrel:

   ```
   php -S localhost:8000 index.php
   ```

5. Nyisd meg a böngészőben a `http://localhost:8000` címet a ToDo alkalmazás elindításához.

## Használat

- A főoldalon lehetőség van ToDo-k hozzáadására, módosítására és törlésére.
- A ToDo-k listája a főoldalon jelenik meg dinamikusan.

## Technológiák

- PHP
- Slim Framework
- Eloquent ORM
- SQLite adatbázis
- jQuery
- Bootstrap

## Készítő

- Balaskő Kristóf

## Licenc

Ez a projekt nyílt forráskódú és a [MIT licenc](https://choosealicense.com/licenses/mit/) alatt áll.
