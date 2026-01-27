<?php

namespace App\Service;

use App\Repository\BookRepository;
use League\Csv\Writer;

class ExportService
{
    public function __construct(
        private BookRepository $bookRepository
    ) {}

    public function exportBooksToCsv(): string
    {
        $books = $this->bookRepository->findAll();
        $csv = Writer::createFromString('');

        $csv->insertOne(['ID', 'Title', 'Authors', 'Genres', 'Published Year', 'ISBN']);

        foreach ($books as $book) {
            $authors = implode(', ', array_map(fn($a) => $a->getName(), $book->getAuthors()->toArray()));
            $genres = implode(', ', array_map(fn($g) => $g->getName(), $book->getGenres()->toArray()));

            $csv->insertOne([
                $book->getId(),
                $book->getTitle(),
                $authors,
                $genres,
                $book->getPublishedYear(),
                $book->getIsbn(),
            ]);
        }

        return $csv->toString();
    }
}
