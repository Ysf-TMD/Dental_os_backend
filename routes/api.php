<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — v1
|--------------------------------------------------------------------------
| Each module registers its own routes in app/Modules/<Module>/Routes/api.php
| They are auto-discovered and loaded under the /api/v1 prefix.
*/

Route::prefix('v1')->group(function () {
    foreach (glob(app_path('Modules/*/Routes/api.php')) as $moduleRoutes) {
        require $moduleRoutes;
    }
});
