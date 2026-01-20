<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
use App\Entity\Favorite;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class BookController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/api/books', name: 'get_books', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $books = $this->em->getRepository(Book::class)->findAll();
        $data = array_map(fn(Book $b) => [
            'id' => $b->getId(),
            'title' => $b->getTitle(),
            'description' => $b->getDescription(),
        ], $books);

        return $this->json($data);
    }

    #[Route('/api/books/{id}', name: 'get_book', methods: ['GET'])]
    public function getBook(int $id): JsonResponse
    {
        $book = $this->em->getRepository(Book::class)->find($id);
        if (!$book) return $this->json(['error' => 'Book not found'], 404);

        return $this->json([
            'id' => $book->getId(),
            'title' => $book->getTitle(),
            'description' => $book->getDescription(),
        ]);
    }

    #[Route('/api/books', name: 'add_book', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $title = $data['title'] ?? null;
        if (!$title) return $this->json(['error' => 'Title required'], 400);

        $book = new Book();
        $book->setTitle($title);
        $book->setDescription($data['description'] ?? '');
        $book->setPublishedYear($data['publishedYear'] ?? null);

        $this->em->persist($book);
        $this->em->flush();

        return $this->json(['id' => $book->getId()], 201);
    }

    #[Route('/api/books/{id}', name: 'delete_book', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(int $id): JsonResponse
    {
        $book = $this->em->getRepository(Book::class)->find($id);
        if (!$book) return $this->json(['error' => 'Book not found'], 404);

        $this->em->remove($book);
        $this->em->flush();

        return $this->json(['message' => 'Book deleted']);
    }

    #[Route('/api/favorites/{bookId}', name: 'add_favorite', methods: ['POST'])]
    #[IsGranted('ROLE_CLIENT')]
    public function addFavorite(int $bookId): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $book = $this->em->getRepository(Book::class)->find($bookId);
        if (!$book) return $this->json(['error' => 'Book not found'], 404);

        $favorite = new Favorite();
        $favorite->setUser($user)->setBook($book);

        $this->em->persist($favorite);
        $this->em->flush();

        return $this->json(['message' => 'Book added to favorites']);
    }

    #[Route('/api/favorites/{bookId}', name: 'remove_favorite', methods: ['DELETE'])]
    #[IsGranted('ROLE_CLIENT')]
    public function removeFavorite(int $bookId): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $favorite = $this->em->getRepository(Favorite::class)
            ->findOneBy(['user' => $user, 'book' => $bookId]);

        if (!$favorite) return $this->json(['error' => 'Favorite not found'], 404);

        $this->em->remove($favorite);
        $this->em->flush();

        return $this->json(['message' => 'Book removed from favorites']);
    }
}
