<?php

declare(strict_types=1);

namespace App\Controller\Product;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;

final class UpdateImage extends Base
{
    /**
     * @param array<string> $args
     */
    public function __invoke(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $input = (array) $request->getParsedBody();
        $directory = $this->container->get('upload_directory');
        $uploadedFiles = $request->getUploadedFiles();
        $uploadedFile = $uploadedFiles['image'];
        $filename = null;
        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $filename = $this->moveUploadedFile($directory, $uploadedFile);
            $response->write('uploaded ' . $filename . '<br/>');
        }
        $input['image_name'] = $filename;
        $productImage = $this->getProductService()->updateImage($input, (int) $args['id']);

        return $this->jsonResponse($response, 'success', $productImage, 200);
    }

    private function moveUploadedFile($directory, UploadedFile $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }
}
