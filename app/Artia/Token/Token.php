<?php

namespace App\Artia\Token;

use Exception;
use App\Artia\Api;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use App\Artia\Builder\MutationBuilder;

class Token
{
    /**
     * @var string
     */
    private static string $dir = '/storage/token';

    /**
     * @var string
     */
    private static string $file = 'token.json';

    /**
     * @return void
     */
    public static function authentication(): void
    {
        try {
            $cache = self::getCacheTokenFile();

            $now = date_create();
            $last = date_create($cache->date);

            $now->modify('-30 minutes');

            if ($now < $last) return;

            self::writeCacheFile([
                'app'   => 'helpdesk',
                'token' => self::createToken(),
                'date'  => date('Y-m-d H:i:s'),
                'next'  => date('Y-m-d H:i:s', strtotime('+60 minutes')),
            ]);
        } catch (Exception $e) {
            $logger = new Logger('token');

            $logger->pushHandler(new StreamHandler(
                __DIR__ . sprintf('%s/auth.txt', env('CONFIG_PATH_LOG'))
            ), Logger::ALERT);

            $logger->alert($e->getMessage());
        }
    }

    /**
     * @return object
     */
    public static function getCacheTokenFile(): object
    {
        $dir = self::resolveDirectory();

        if (file_exists($dir) === false) {
            self::writeCacheFile([
                'app'   => 'helpdesk',
                'token' => null,
                'date'  => '2000-01-01 00:00:00',
                'next'  => '2000-01-01 00:00:00',
            ]);
        }

        $content = file_get_contents($dir);
        $content = json_decode($content);

        return $content;
    }

    /**
     * @return string
     * @throws Exception
     */
    private static function createToken(): string
    {
        return (new Api)
            ->name('authenticationByEmail')
            ->arguments([
                'email' => env('CONFIG_API_EMAIL'),
                'password' => env('CONFIG_API_PASSWORD'),
            ])
            ->body(['token'])
            ->build(new MutationBuilder)
            ->call()
            ->data
            ->authenticationByEmail
            ->token;
    }

    /**
     * @return string
     */
    private static function resolveDirectory(): string
    {
        return __DIR__ . sprintf('/../../../%s/%s', self::$dir, self::$file);
    }

    /**
     * @param array $args
     * @return void
     */
    private static function writeCacheFile(array $args): void
    {
        $fopen = fopen(self::resolveDirectory(), 'w+');

        fwrite($fopen, json_encode($args));

        fclose($fopen);
    }
}
