<?php

declare(strict_types=1);

// use App\Repository\NoteRepository;
use App\Repository\ProductRepository;
// use App\Repository\UserRepository;
use Psr\Container\ContainerInterface;

// $container['user_repository'] = static fn (ContainerInterface $container): UserRepository => new UserRepository($container->get('db'));

$container['product_repository'] = static fn (ContainerInterface $container): ProductRepository => new ProductRepository($container->get('db'));

// $container['note_repository'] = static fn (ContainerInterface $container): NoteRepository => new NoteRepository($container->get('db'));
