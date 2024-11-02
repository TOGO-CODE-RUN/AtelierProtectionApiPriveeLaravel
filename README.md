# Mise en place d'une sécurité d'api avis clé d'api

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

7. Creation des requests
    - UserRequest
        # php artisan make:request UserRequest
    - NoteRequest
        # php artisan make:request NoteRequest

7. Creation des fonctions de CRUD dans les controllers

8. Creation des routes
    - Route::resource('users', UserController::class);
    - Route::resource('notes', NoteController::class);

9. Changer le prefix des routes par defaut
    - dans le fichier : app/Providers/RouteServiceProvider.php

10. Faire un commit
    # git add .
    # git commit -m "Creation des controllers et des routes"

11. Premiere etape, protecter les routes avec laravel sanctum
    - Definir la durée de vie du token dans le fichier : config/sanctum.php 'expiration' => 60 par exemple
    - Et pour ceux qui utilise les cookies de session, definir la durée de vie du cookie dans le fichier : config/session.php 'lifetime' => 120 par exemple

12. Limiter les requêtes avec le Middleware "Throttle"
    - Appliquer le middleware "Throttle" sur les routes qui ont besoin de protection

    # EXEMPLE 1
    Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
    });

    # EXEMPLE 2
    Configurer les limites globales dans RouteServiceProvider

    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }

13. Faire un commit
    # git add .
    # git commit -m "Protection des routes avec laravel sanctum"

13. Creation de la route pour la connexion











<!-- # ETAPE 2 : Installation des dependances et publication des fichiers de configuration pour laravel sanctum

1. Installation des dependances
    # composer require tymon/jwt-auth

2. Publication des fichiers de configuration
    # php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
    # php artisan jwt:secret

3. Editer la durée de la session de l'utilisateur
    - dans le fichier : config/jwt.php

 -->
