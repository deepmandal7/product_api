<?php

declare(strict_types=1);

namespace App\Controller\Product;

use Slim\Http\Request;
use Slim\Http\Response;

final class GetAll extends Base
{
    public function __invoke(Request $request, Response $response): Response
    {
        $input = (array) $request->getParsedBody();
        // $userId = $this->getAndValidateUserId($input);
        // $page = $request->getQueryParam('page', null);
        // $perPage = $request->getQueryParam('perPage', null);
        // $name = $request->getQueryParam('name', null);
        // $description = $request->getQueryParam('description', null);
        // $status = $request->getQueryParam('status', null);

        $products = $this->getProductService()->getAllProducts(
            // $userId,
            // (int) $page,
            // (int) $perPage,
            // $name,
            // $description,
            // $status
        );

        return $this->jsonResponse($response, 'success', $products, 200);
    }
}
