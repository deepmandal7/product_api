<?php

declare(strict_types=1);

namespace App\Service\Product;

use App\Entity\Product;

final class ProductService extends Base
{
    /**
     * @return array<string>
     */
    // public function getTasksByPage(
    //     int $userId,
    //     int $page,
    //     int $perPage,
    //     ?string $name,
    //     ?string $description,
    //     ?string $status
    // ): array {
    //     if ($page < 1) {
    //         $page = 1;
    //     }
    //     if ($perPage < 1) {
    //         $perPage = self::DEFAULT_PER_PAGE_PAGINATION;
    //     }

    //     return $this->getTaskRepository()->getTasksByPage(
    //         $userId,
    //         $page,
    //         $perPage,
    //         $name,
    //         $description,
    //         $status
    //     );
    // }

    /**
     * @return array<string>
     */
    public function getAllProducts(): array
    {
        $products = $this->getProductRepository()->getAllProducts();
        $productsArray = [];
        foreach ($products as $value) {
            $obj = (object) [
                'id' => $value['id'],
                'title' => $value['title'],
                'description' => $value['description'],
                'image_name' => $value['image_name'],
            ];
            if ($value['image_name']) {
                $filename = __DIR__ . '/../../../uploads/' . $value['image_name'];
                $fp = fopen($filename, "r");
                if (file_exists($filename)) {
                    $contents = fread($fp, filesize($filename));
                    $byte_array = unpack('C*', $contents);
                    $obj->image = $byte_array;
                }
            }
            array_push($productsArray, $obj);
        }
        return $productsArray;
    }

    public function getOne(int $productId): object
    {
        // if (self::isRedisEnabled() === true) {
        //     $task = $this->getTaskFromCache($taskId, $userId);
        // } else {
        $product = $this->getProductFromDb($productId)->toJson();
        // }

        $filename = __DIR__ . '/../../../uploads/' . $product->image_name;
        if ($filename) {
            $fp = fopen($filename, "r");
            $contents = fread($fp, filesize($filename));
            $byte_array = unpack('C*', $contents);
            $product->image = $byte_array;
        }

        return $product;
    }

    /**
     * @param array<string> $input
     */
    public function create(array $input): object
    {
        $data = json_decode((string) json_encode($input), false);
        if (!isset($data->title)) {
            throw new \App\Exception\Product('The field "title" is required.', 400);
        }

        $myproduct = new Product();
        $myproduct->updateTitle(self::validateProductTitle($data->title));
        $description = isset($data->description) ? $data->description : null;
        $myproduct->updateDescription($description);
        $imagename = isset($data->image_name) ? $data->image_name : null;
        $myproduct->updateImageName($imagename);
        // $status = 0;
        // if (isset($data->status)) {
        //     $status = self::validateTaskStatus($data->status);
        // }
        // $mytask->updateStatus($status);
        // $userId = null;
        // if (isset($data->decoded) && isset($data->decoded->sub)) {
        //     $userId = (int) $data->decoded->sub;
        // }
        // $myproduct->updateUserId($userId);
        /** @var Product $product */
        $product = $this->getProductRepository()->create($myproduct);
        // if (self::isRedisEnabled() === true) {
        //     $this->saveInCache(
        //         $task->getId(),
        //         $task->getUserId(),
        //         $task->toJson()
        //     );
        // }

        return $product->toJson();
    }

    /**
     * @param array<string> $input
     */
    public function update(array $input, int $productId): object
    {
        $data = $this->validateProduct($input, $productId);
        /** @var Task $task */
        $product = $this->getProductRepository()->update($data);
        // if (self::isRedisEnabled() === true) {
        //     $this->saveInCache(
        //         $task->getId(),
        //         $data->getUserId(),
        //         $task->toJson()
        //     );
        // }

        return $product->toJson();
    }


    public function updateImage(array $input, int $productId): object
    {
        $data = $this->validateProduct($input, $productId);
        /** @var Task $task */
        $product = $this->getProductRepository()->updateImageName($data);
        // if (self::isRedisEnabled() === true) {
        //     $this->saveInCache(
        //         $task->getId(),
        //         $data->getUserId(),
        //         $task->toJson()
        //     );
        // }

        return $product->toJson();
    }

    public function delete(int $productId): object
    {
        $product = $this->getProductFromDb($productId)->toJson();
        $this->getProductRepository()->delete($productId);
        // if (self::isRedisEnabled() === true) {
        //     $this->deleteFromCache($taskId, $userId);
        // }

        return $product;
    }

    private function validateProduct(array $input, int $productId): Product
    {
        $product = $this->getProductFromDb($productId, (int) $input['decoded']->sub);
        $data = json_decode((string) json_encode($input), false);
        if (isset($data->image_name)) {
            $product->updateImageName($data->image_name);
        } else {
            if (!isset($data->title)) {
                throw new \App\Exception\Product('Enter the data to update the product.', 400);
            }
            if (isset($data->title)) {
                $product->updateTitle(self::validateProductTitle($data->title));
            }
            if (isset($data->description)) {
                $product->updateDescription($data->description);
            }
        }

        // if (isset($data->status)) {
        //     $task->updateStatus(self::validateTaskStatus($data->status));
        // }
        // $userId = null;
        // if (isset($data->decoded) && isset($data->decoded->sub)) {
        //     $userId = (int) $data->decoded->sub;
        // }
        // $task->updateUserId($userId);

        return $product;
    }
}
