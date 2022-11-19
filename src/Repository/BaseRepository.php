<?php

declare(strict_types=1);

namespace App\Repository;

abstract class BaseRepository
{
    public function __construct(protected \PDO $database)
    {
    }

    protected function getDb(): \PDO
    {
        return $this->database;
    }

    protected function getResults(
        string $query,
        // int $page,
        // int $perPage,
        array $params,
        // int $total
    ): array {
        return [
            // 'pagination' => [
            //     'totalRows' => $total,
            //     'totalPages' => ceil($total / $perPage),
            //     'currentPage' => $page,
            //     'perPage' => $perPage,
            // ],
            'data' => $this->getResult(
                $query,
                // $page, $perPage, 
                $params
            ),
        ];
    }

    // protected function getResultsWithPagination(
    //     string $query,
    //     int $page,
    //     int $perPage,
    //     array $params,
    //     int $total
    // ): array {
    //     return [
    //         'pagination' => [
    //             'totalRows' => $total,
    //             'totalPages' => ceil($total / $perPage),
    //             'currentPage' => $page,
    //             'perPage' => $perPage,
    //         ],
    //         'data' => $this->getResultByPage($query, $page, $perPage, $params),
    //     ];
    // }

    /**
     * @param array<string> $params
     * @return array<float|int|string>
     */
    protected function getResult(
        string $query,
        // int $page,
        // int $perPage,
        array $params
    ): array {
        // $offset = ($page - 1) * $perPage;
        $query .= "";
        $statement = $this->database->prepare($query);
        $statement->execute($params);

        return (array) $statement->fetchAll();
    }
}
