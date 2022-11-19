<?php

declare(strict_types=1);

namespace App\Service\Product;

use App\Entity\Product;
use App\Repository\ProductRepository;
// use App\Service\BaseService;
// use App\Service\RedisService;
use Respect\Validation\Validator as v;

abstract class Base
// extends BaseService
{
    // private const REDIS_KEY = 'task:%s:user:%s';

    public function __construct(
        protected ProductRepository $productRepository,
        // protected RedisService $redisService
    ) {
    }

    protected function getProductRepository(): ProductRepository
    {
        return $this->productRepository;
    }

    protected static function validateProductTitle(string $title): string
    {
        if (!v::length(1, 100)->validate($title)) {
            throw new \App\Exception\Product('Invalid title.', 400);
        }

        return $title;
    }

    protected function getProductFromDb(int $productId): Product
    {
        return $this->getProductRepository()->checkAndGetProduct($productId);
    }

    // protected static function validateTaskStatus(int $status): int
    // {
    //     if (!v::numeric()->between(0, 1)->validate($status)) {
    //         throw new \App\Exception\Task('Invalid status', 400);
    //     }

    //     return $status;
    // }

    // protected function getTaskFromCache(int $taskId, int $userId): object
    // {
    //     $redisKey = sprintf(self::REDIS_KEY, $taskId, $userId);
    //     $key = $this->redisService->generateKey($redisKey);
    //     if ($this->redisService->exists($key)) {
    //         $task = $this->redisService->get($key);
    //     } else {
    //         $task = $this->getTaskFromDb($taskId, $userId)->toJson();
    //         $this->redisService->setex($key, $task);
    //     }

    //     return $task;
    // }



    // protected function saveInCache(int $taskId, int $userId, object $task): void
    // {
    //     $redisKey = sprintf(self::REDIS_KEY, $taskId, $userId);
    //     $key = $this->redisService->generateKey($redisKey);
    //     $this->redisService->setex($key, $task);
    // }

    // protected function deleteFromCache(int $taskId, int $userId): void
    // {
    //     $redisKey = sprintf(self::REDIS_KEY, $taskId, $userId);
    //     $key = $this->redisService->generateKey($redisKey);
    //     $this->redisService->del([$key]);
    // }
}
