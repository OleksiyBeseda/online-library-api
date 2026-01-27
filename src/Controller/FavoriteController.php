<?php

namespace App\Controller;

use App\Service\BookService;
use App\Service\FavoriteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/favorites', name: 'api_favorites_')]
class FavoriteController extends AbstractController
{
    public function __construct(
        private FavoriteService $favoriteService,
        private BookService $bookService
    ) {}

    #[Route('/{id}', name: 'add', methods: ['POST'])]
    public function add(int $id): JsonResponse
    {
        $book = $this->bookService->getBookById($id);
        if (!$book) {
            return $this->json(['message' => 'Book not found'], Response::HTTP_NOT_FOUND);
        }

        $user = $this->getUser();
        $this->favoriteService->addFavorite($user, $book);

        return $this->json(['message' => 'Book added to favorites'], Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'remove', methods: ['DELETE'])]
    public function remove(int $id): JsonResponse
    {
        $book = $this->bookService->getBookById($id);
        if (!$book) {
            return $this->json(['message' => 'Book not found'], Response::HTTP_NOT_FOUND);
        }

        $user = $this->getUser();
        $this->favoriteService->removeFavorite($user, $book);

        return $this->json(['message' => 'Book removed from favorites'], Response::HTTP_OK);
    }
}
