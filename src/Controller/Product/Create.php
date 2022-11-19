<?php

declare(strict_types=1);

namespace App\Controller\Product;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;

final class Create extends Base
{
    public function __invoke(Request $request, Response $response): Response
    {
        $directory = $this->container->get('upload_directory');
        $uploadedFiles = $request->getUploadedFiles();
        $uploadedFile = $uploadedFiles['image'];
        $filename = null;
        var_dump("hereuploadedFile");
        var_dump($uploadedFile);
        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $filename = $this->moveUploadedFile($directory, $uploadedFile);
            $response->write('uploaded ' . $filename . '<br/>');
        }
        $input = (array) $request->getParsedBody();
        $input['image_name'] = $filename;
        var_dump($input);
        $product = $this->getProductService()->create($input);

        return $this->jsonResponse($response, 'success', $product, 201);
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
