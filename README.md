# Mise en place d'une sécurité d'api avis clé d'api

1. Creation du projet
       ``` composer create-project laravel/laravel InitimeNoteApi```

3. Initialisation du depot git
   ```
     git init
     git add .
     git commit -m "Creation du projet et initialisation" ```

4. Creation des models et des fichiers de migrations
    - Model : User
    - Model : Note

5. Effectuer la migation
  ``` php artisan migrate ```

6. Faire un commit
```
    git add .
    git commit -m "Creation des models et des fichiers de migrations"
```

6. Creation des controllers
    - UserController
        ``` php artisan make:controller UserController --resource --model=User```
    - NoteController
        ``` php artisan make:controller NoteController --resource --model=Note```

7. Creation des requests
    - UserLoginRequest
        ``` php artisan make:request UserLoginRequest ```
    - UserRegisterRequest
        ``` php artisan make:request UserRegisterRequest ```
    - NoteRequest
        ``` php artisan make:request NoteRequest ```

7. Creation des fonctions de CRUD dans les controllers

8. Creation des routes
   ```
    - Route::resource('users', UserController::class);
    - Route::resource('notes', NoteController::class);
   ```

10. Changer le prefix des routes par defaut
    - dans le fichier : ``` app/Providers/RouteServiceProvider.php```

11. Faire un commit
    ```
     git add .
     git commit -m "Creation des controllers et des routes"
    ```
12. Premiere etape, protecter les routes avec laravel sanctum
    - Définir la durée de vie du token dans config/sanctum.php
    ```
        'expiration' => 60, // durée en minutes
    ```
    - Pour les cookies de session, définir la durée de vie du cookie dans config/session.php
    ```
        'lifetime' => 120, // durée en minutes
    ```

13. Limitation des requêtes avec le middleware "Throttle"
    - Appliquer le middleware "Throttle" sur les routes qui ont besoin de protection

## EXEMPLE 1
```
    Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
    });
```
## EXEMPLE 2
    Configurer les limites globales dans RouteServiceProvider
```
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
```
13. Faire un commit
    ```
        git add .
        git commit -m "Protection des routes avec laravel sanctum"
    ```
14. Ajouter la sécurité d'api avec clé d'api
    - php artisan make:middleware ApiKeyMiddleware
    - Dans app/Http/Middleware/ApiKeyMiddleware.php, ajoutez le code de restriction pour les routes :

    ```
        public function handle(Request $request, Closure $next)
        {
            $apiKey = $request->header('X-API-KEY');

            // Vérification de la clé API
            if (!$apiKey || $apiKey !== env('API_SECRET_KEY')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès refusé. Clé API manquante ou invalide.'
                ], 401);
            }

            return $next($request);
        }
    ```


    - dans le fichier : app/Http/Kernel.php : Ajout du middleware de clé API.
    ```
        protected $middlewareAliases = [
            // SPECIFIER LE MIDDLEWARE DE CLÉ API
            'api.key' => \App\Http\Middleware\ApiKeyMiddleware::class,
        ];
    ```
    - Dans le fichier : .env: definir la clé API : Exemple
    ```
        API_SECRET_KEY="MBALLAH#C0de@NotE!!APP_2024-By_Mounix&FoR\TCR"
    ```
    - Dans le fichier : api.php : Appliquer le middleware "ApiKeyMiddleware" sur les routes

    ```
        // Route protégée à la fois par clé d'API, laravel Sanctum et par le middleware Throttle
        Route::middleware(['api.key','auth:sanctum', 'throttle:60,1'])->group(function () {
            Route::get('/user', function (Request $request) {
                return $request->user();
            });
        });
    ```

15. Désactiver le mode debug pour la production
    - Dans .env, désactivez le mode debug (nécessaire pour la mise en production) 
    ```
        APP_DEBUG=false
    ```