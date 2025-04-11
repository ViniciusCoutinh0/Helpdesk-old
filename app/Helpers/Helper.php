<?php

use Pecee\SimpleRouter\SimpleRouter as Router;
use Pecee\Http\Url;
use Pecee\Http\Response;
use Pecee\Http\Request;
use App\Common\Session;

/**
 * Get url for a route by using either name/alias, class or method name.
 *
 * The name parameter supports the following values:
 * - Route name
 * - Controller/resource name (with or without method)
 * - Controller class name
 *
 * When searching for controller/resource by name, you can use this syntax "route.name@method".
 * You can also use the same syntax when searching for a specific controller-class "MyController@home".
 * If no arguments is specified, it will return the url for the current loaded route.
 *
 * @param string|null $name
 * @param string|array|null $parameters
 * @param array|null $getParams
 * @return \Pecee\Http\Url
 * @throws \InvalidArgumentException
 */
function url(?string $name = null, $parameters = null, ?array $getParams = null): Url
{
    return Router::getUrl($name, $parameters, $getParams);
}

/**
 * @return \Pecee\Http\Response
 */
function response(): Response
{
    return Router::response();
}

/**
 * @return \Pecee\Http\Request
 */
function request(): Request
{
    return Router::request();
}

/**
 * Get input class
 * @param string|null $index Parameter index name
 * @param string|mixed|null $defaultValue Default return value
 * @param array ...$methods Default methods
 * @return \Pecee\Http\Input\InputHandler|array|string|null
 */
function input($index = null, $defaultValue = null, ...$methods)
{
    if ($index !== null) {
        return request()->getInputHandler()->value($index, $defaultValue, ...$methods);
    }

    return request()->getInputHandler();
}

/**
 * @param string $url
 * @param int|null $code
 */
function redirect(string $url, ?int $code = null): void
{
    if ($code !== null) {
        response()->httpCode($code);
    }

    response()->redirect($url);
}

/**
 * Get current csrf-token
 * @return string|null
 */
function csrf_token(): ?string
{
    $baseVerifier = Router::router()->getCsrfVerifier();
    if ($baseVerifier !== null) {
        return $baseVerifier->getTokenProvider()->getToken();
    }

    return null;
}

/**
 * @return null|string
 */
function old(string $key): ?string
{
    if (input()->exists($key)) {
        return input()->post($key)->getValue();
    }

    return null;
}

/**
 * @param array $data
 * @return void
 */
function clearCache(array $data): void
{
    if (count($data)) {
        foreach ($data as $key => $value) {
            input()->post($key)->setValue('');
        }
    }
}

/**
 * @return string
 */
function defaultUrl(): string
{
    if (request()->getHost() != 'app.promofarma.int') {
        return env('CONFIG_APP_PRO_URL');
    }

    return env('CONFIG_APP_DEV_URL');
}

/**
 * @return string
 */
function asset(string $path = null): string
{
    if ($path) {
        return defaultUrl() . '/' . removeSlashFromStringBeginning($path);
    }
    return defaultUrl();
}

/**
 * @return string
 */
function pathOs(string $path, string $default = '/'): string
{
    return str_replace($default, DIRECTORY_SEPARATOR, $path);
}

/**
 * @return Session
 */
function Session(): Session
{
    return new Session();
}
/**
 * @param string $key
 * @return ?string
 */
function env(string $key): ?string
{
    if (array_key_exists($key, $_ENV)) {
        return $_ENV[$key];
    }
    return null;
}

/**
 * @return string
 */
function clearHtml(string $str): string
{
    return trim(strip_tags($str));
}

/**
 * @link https://gist.github.com/quantizer/5744907
 * @return string
 */
function clearEmoji(string $str): string
{
    $cleanText = "";

    $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
    $cleanText = preg_replace($regexEmoticons, '', $str);

    $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
    $cleanText = preg_replace($regexSymbols, '', $str);

    $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
    $cleanText = preg_replace($regexTransport, '', $str);

    return $cleanText;
}

function guidv4(string $data = null): string
{
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);

    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function removeSlashFromStringBeginning(string $path): string
{
    if (str_starts_with($path, '/')) {
        $path = ltrim($path, '/');
    }

    return $path;
}
