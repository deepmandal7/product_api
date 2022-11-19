<?php

declare(strict_types=1);

use App\Handler\ApiError;
use App\Service\RedisService;
use Psr\Container\ContainerInterface;

$container['db'] = static function (ContainerInterface $container): PDO {
    $database = $container->get('settings')['db'];
    $dsn = sprintf(
        'pgsql:host=%s;port=%s;dbname=%s;',
        $database['host'],
        $database['port'],
        $database['name'],
    );
    $pdo = new PDO($dsn, $database['user'], $database['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    return $pdo;
};

$container['errorHandler'] = static fn (): ApiError => new ApiError();

// $container['redis_service'] = static function ($container): RedisService {
//     $redis = $container->get('settings')['redis'];

//     return new RedisService(new \Predis\Client($redis['url']));
// };

$container['notFoundHandler'] = static function () {
    return static function ($request, $response): void {
        throw new \App\Exception\NotFound('Route Not Found.', 404);
    };
};

$container['upload_directory'] = __DIR__ . '/../../uploads';
