<?php

declare(strict_types=1);

namespace App\Controller\Product;

use Slim\Http\Request;
use Slim\Http\Response;

final class GetOne extends Base
{
    /**
     * @param array<string> $args
     */
    public function __invoke(
        Request $request,
        Response $response,
        array $args
    ): Response {
        // $input = (array) $request->getParsedBody();
        $productId = (int) $args['id'];
        // $userId = $this->getAndValidateUserId($input);
        $product = $this->getProductService()->getOne($productId);
        return $this->jsonResponse($response, 'success', $product, 200);
    }
}
