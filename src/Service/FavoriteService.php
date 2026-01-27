<?php

namespace App\Service;

use App\Entity\Book;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class FavoriteService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function addFavorite(User $user, Book $book): void
    {
        $user->addFavorite($book);
        $this->entityManager->flush();
    }

    public function removeFavorite(User $user, Book $book): void
    {
        $user->removeFavorite($book);
        $this->entityManager->flush();
    }
}
