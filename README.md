# Mise en place d'une sécurité d'api avis clé d'api

# ETAPE 1 : Creation du projet laravel et initialisation

1. Creation du projet
    # composer create-project laravel/laravel InitimeNoteApi

2. Initialisation du depot git
    # git init
    # git add .
    # git commit -m "Creation du projet et initialisation"

3. Creation des models et des fichiers de migrations
    - Model : User
    - Model : Note

4. Effectuer la migation
    # php artisan migrate

5. Faire un commit
    # git add .
    # git commit -m "Creation des models et des fichiers de migrations"

6. Creation des controllers
    - UserController
        # php artisan make:controller UserController --resource --model=User
    - NoteController
        # php artisan make:controller NoteController --resource --model=Note

7. Creation des fonctions de CRUD dans les controllers

8. Creation des routes
    - Route::resource('users', UserController::class);
    - Route::resource('notes', NoteController::class);
9. Faire un commit
    # git add .
    # git commit -m "Creation des controllers et des routes"

# ETAPE 2 : Installation des dependances et publication des fichiers de configuration

3. Installation des dependances
    # composer require laravel/sanctum
4. Publication des fichiers de configuration
    # php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
