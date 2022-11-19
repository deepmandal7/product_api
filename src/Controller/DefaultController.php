<?php

declare(strict_types=1);

namespace App\Controller;

use Slim\Http\Request;
use Slim\Http\Response;

// function debug_to_console($data)
// {
//     $output = $data;
//     if (is_array($output))
//         $output = implode(',', $output);

//     echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
// }

final class DefaultController extends BaseController
{
    private const API_VERSION = '0.0.1';

    public function getHelp(Request $request, Response $response): Response
    {
        $url = $this->container->get('settings')['app']['domain'];
        $endpoints = [
            'products' => $url . '/api/v1/products',
            // 'users' => $url . '/api/v1/users',
            // 'notes' => $url . '/api/v1/notes',
            // 'docs' => $url . '/docs/index.html',
            'status' => $url . '/status',
            'this help' => $url . '',
        ];
        $message = [
            'endpoints' => $endpoints,
            'version' => self::API_VERSION,
            'timestamp' => time(),
        ];

        return $this->jsonResponse($response, 'success', $message, 200);
    }

    public function getStatus(Request $request, Response $response): Response
    {
        $status = [
            'stats' => $this->getDbStats(),
            'PostgreSql' => 'OK',
            // 'Redis' => $this->checkRedisConnection(),
            'version' => self::API_VERSION,
            'timestamp' => time(),
        ];
        // debug_to_console($status);

        return $this->jsonResponse($response, 'success', $status, 200);
    }

    /**
     * @return array<int>
     */
    private function getDbStats(): array
    {
        $productService = $this->container->get('product_service');
        // $userService = $this->container->get('find_user_service');
        // $noteService = $this->container->get('find_note_service');
        return [
            'products' => count($productService->getAllProducts()),
            // 'users' => count($userService->getAll()),
            // 'notes' => count($noteService->getAll()),
        ];
    }

    private function checkRedisConnection(): string
    {
        $redis = 'Disabled';
        if (self::isRedisEnabled() === true) {
            $redisService = $this->container->get('redis_service');
            $key = $redisService->generateKey('test:status');
            $redisService->set($key, new \stdClass());
            $redis = 'OK';
        }

        return $redis;
    }
}
