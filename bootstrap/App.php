<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use App\RouteSubPath;
use App\Artia\Token\Token;
use App\Http\Middleware\CsrfVerifier;
use Pecee\SimpleRouter\SimpleRouter as Route;

date_default_timezone_set('America/Sao_Paulo');

try {
    $dotEnv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotEnv->load();

    if (isset($_SERVER['REQUEST_METHOD']) === false) {
        Token::authentication();
        exit;
    }

    Route::addEventHandler(
        (new RouteSubPath(env('CONFIG_APP_PATH')))->handler()
    );

    Route::csrfVerifier(new CsrfVerifier);
    Route::setDefaultNamespace('\App\Http\Controllers');

    require __DIR__ . '/../routers/web.php';

    Route::start();
} catch (\Exception $exception) {
    echo sprintf('[%s] - %s - [%s]', $exception->getLine(), $exception->getMessage(), $exception->getFile());
    return;
}
