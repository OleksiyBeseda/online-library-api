<?php

namespace App\Controller;

use App\Service\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/books', name: 'api_books_')]
class BookController extends AbstractController
{
    public function __construct(
        private BookService $bookService
    ) {}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $books = $this->bookService->getAllBooks();
        return $this->json($books, Response::HTTP_OK, [], ['groups' => 'book:read']);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $book = $this->bookService->getBookById($id);
        if (!$book) {
            return $this->json(['message' => 'Book not found'], Response::HTTP_NOT_FOUND);
        }
        return $this->json($book, Response::HTTP_OK, [], ['groups' => 'book:read']);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return $this->json(['message' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $book = $this->bookService->createBook($data);
            return $this->json($book, Response::HTTP_CREATED, [], ['groups' => 'book:read']);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $book = $this->bookService->getBookById($id);
        if (!$book) {
            return $this->json(['message' => 'Book not found'], Response::HTTP_NOT_FOUND);
        }

        $this->bookService->deleteBook($book);
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
