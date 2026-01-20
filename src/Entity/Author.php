<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Book;

#[ORM\Entity]
#[ORM\Table(name: "authors")]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 255)]
    private string $name;

    // Связь ManyToMany с книгами (Book)
    #[ORM\ManyToMany(targetEntity: Book::class, mappedBy: "authors")]
    private Collection $books;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    // ------------------------------
    // Геттеры и сеттеры
    // ------------------------------

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Collection|Book[]
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books[] = $book;
            $book->addAuthor($this); // синхронизация обратной связи
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->books->removeElement($book)) {
            $book->removeAuthor($this); // синхронизация обратной связи
        }

        return $this;
    }
}
