<?php

declare(strict_types=1);

namespace App\Entity;

final class Product
{
    private int $id;

    private string $title;

    private ?string $description;

    private ?string $imagename;

    // private int $status;

    // private int $userId;

    public function toJson(): object
    {
        return json_decode((string) json_encode(get_object_vars($this)), false);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function updateTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function updateDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imagename;
    }

    public function updateImageName(?string $imagename): self
    {
        $this->imagename = $imagename;

        return $this;
    }

    // public function getStatus(): int
    // {
    //     return $this->status;
    // }

    // public function updateStatus(int $status): self
    // {
    //     $this->status = $status;

    //     return $this;
    // }

    // public function getUserId(): int
    // {
    //     return $this->userId;
    // }

    // public function updateUserId(?int $userId): self
    // {
    //     $this->userId = $userId;

    //     return $this;
    // }
}
