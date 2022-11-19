<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;

final class ProductRepository extends BaseRepository
{

    public function checkAndGetProduct(int $productId): Product
    {
        $query = '
            SELECT * FROM products WHERE id = :id;
        ';
        $statement = $this->getDb()->prepare($query);
        $statement->bindParam('id', $productId);
        // $statement->bindParam('userId', $userId);
        $statement->execute();
        $product = $statement->fetchObject(Product::class);
        if (!$product) {
            throw new \App\Exception\Product('Product not found.', 404);
        }

        return $product;
    }

    /**
     * @return array<string>
     */
    public function getAllProducts(): array
    {
        $query = '
            SELECT * FROM products ORDER BY id asc
        ';
        $statement = $this->getDb()->prepare($query);
        $statement->execute();

        return (array) $statement->fetchAll();
    }
    // public function getQueryTasksByPage(): string
    // {
    //     return "
    //         SELECT *
    //         FROM `tasks`
    //         WHERE `userId` = :userId
    //         AND `name` LIKE CONCAT('%', :name, '%')
    //         AND `description` LIKE CONCAT('%', :description, '%')
    //         AND `status` LIKE CONCAT('%', :status, '%')
    //         ORDER BY `id`
    //     ";
    // }

    // /**
    //  * @return array<string>
    //  */
    // public function getTasksByPage(
    //     int $userId,
    //     int $page,
    //     int $perPage,
    //     ?string $name,
    //     ?string $description,
    //     ?string $status
    // ): array {
    //     $params = [
    //         'userId' => $userId,
    //         'name' => is_null($name) ? '' : $name,
    //         'description' => is_null($description) ? '' : $description,
    //         'status' => is_null($status) ? '' : $status,
    //     ];
    //     $query = $this->getQueryTasksByPage();
    //     $statement = $this->database->prepare($query);
    //     $statement->bindParam('userId', $params['userId']);
    //     $statement->bindParam('name', $params['name']);
    //     $statement->bindParam('description', $params['description']);
    //     $statement->bindParam('status', $params['status']);
    //     $statement->execute();
    //     $total = $statement->rowCount();

    //     return $this->getResultsWithPagination(
    //         $query,
    //         $page,
    //         $perPage,
    //         $params,
    //         $total
    //     );
    // }



    public function create(Product $product): Product
    {
        $query = '
            INSERT INTO products
                (title, description, image_name)
            VALUES
                (:title, :description, :imagename)
        ';
        $statement = $this->getDb()->prepare($query);
        $title = $product->getTitle();
        $desc = $product->getDescription();
        $imagename = $product->getImageName();
        // $userId = $product->getUserId();
        $statement->bindParam('title', $title);
        $statement->bindParam('description', $desc);
        $statement->bindParam('imagename', $imagename);
        // $statement->bindParam('userId', $userId);
        $statement->execute();

        $productId = (int) $this->database->lastInsertId();

        return $this->checkAndGetProduct($productId);
    }

    public function update(Product $product): Product
    {
        $query = '
            UPDATE products
            SET title = :title, description = :description
            WHERE id = :id
        ';
        $statement = $this->getDb()->prepare($query);
        $id = $product->getId();
        $title = $product->getTitle();
        $desc = $product->getDescription();
        // $status = $task->getStatus();
        // $userId = $task->getUserId();
        $statement->bindParam('id', $id);
        $statement->bindParam('title', $title);
        $statement->bindParam('description', $desc);
        // $statement->bindParam('status', $status);
        // $statement->bindParam('userId', $userId);
        $statement->execute();

        return $this->checkAndGetProduct((int) $id);
    }

    public function updateImageName(Product $product): Product
    {
        $query = '
            UPDATE products
            SET image_name = :image_name
            WHERE id = :id
        ';
        $statement = $this->getDb()->prepare($query);
        $id = $product->getId();
        $imagename = $product->getImageName();
        // $desc = $product->getDescription();
        // $status = $task->getStatus();
        // $userId = $task->getUserId();
        $statement->bindParam('id', $id);
        $statement->bindParam('image_name', $imagename);
        // $statement->bindParam('description', $desc);
        // $statement->bindParam('status', $status);
        // $statement->bindParam('userId', $userId);
        $statement->execute();

        return $this->checkAndGetProduct((int) $id);
    }

    public function delete(int $productId): void
    {
        $query = '
            DELETE FROM products WHERE id = :id
        ';
        $statement = $this->getDb()->prepare($query);
        $statement->bindParam('id', $productId);
        // $statement->bindParam('userId', $userId);
        $statement->execute();
    }
}
