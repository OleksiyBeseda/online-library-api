<?php

namespace App\Service;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Genre;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;

class BookService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private BookRepository $bookRepository,
        private AuthorRepository $authorRepository,
        private GenreRepository $genreRepository
    ) {}

    public function getAllBooks(): array
    {
        return $this->bookRepository->findAll();
    }

    public function getBookById(int $id): ?Book
    {
        return $this->bookRepository->find($id);
    }

    public function createBook(array $data): Book
    {
        if (empty($data['authors'])) {
            throw new \InvalidArgumentException('A book must have at least one author.');
        }

        if (empty($data['genres'])) {
            throw new \InvalidArgumentException('A book must have at least one genre.');
        }

        $book = new Book();
        $book->setTitle($data['title']);
        $book->setDescription($data['description'] ?? null);
        $book->setPublishedYear($data['publishedYear'] ?? null);
        $book->setIsbn($data['isbn'] ?? null);

        if (isset($data['authors'])) {
            foreach ($data['authors'] as $authorName) {
                $author = $this->authorRepository->findOneBy(['name' => $authorName]);
                if (!$author) {
                    $author = new Author();
                    $author->setName($authorName);
                    $this->entityManager->persist($author);
                }
                $book->addAuthor($author);
            }
        }

        if (isset($data['genres'])) {
            foreach ($data['genres'] as $genreName) {
                $genre = $this->genreRepository->findOneBy(['name' => $genreName]);
                if (!$genre) {
                    $genre = new Genre();
                    $genre->setName($genreName);
                    $this->entityManager->persist($genre);
                }
                $book->addGenre($genre);
            }
        }

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return $book;
    }

    public function deleteBook(Book $book): void
    {
        $this->entityManager->remove($book);
        $this->entityManager->flush();
    }
}
