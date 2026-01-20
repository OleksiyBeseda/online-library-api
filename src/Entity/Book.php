<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name:"books")]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private int $id;

    #[ORM\Column(type:"string")]
    private string $title;

    #[ORM\Column(type:"text", nullable:true)]
    private ?string $description = null;

    #[ORM\Column(type:"integer", nullable:true)]
    private ?int $publishedYear = null;

    #[ORM\ManyToMany(targetEntity:"Author", inversedBy:"books")]
    #[ORM\JoinTable(name:"book_author")]
    private Collection $authors;

    #[ORM\ManyToMany(targetEntity:"Genre", inversedBy:"books")]
    #[ORM\JoinTable(name:"book_genre")]
    private Collection $genres;

    public function __construct() {
        $this->authors = new ArrayCollection();
        $this->genres = new ArrayCollection();
    }

    public function getId(): int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function setTitle(string $title): self { $this->title = $title; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): self { $this->description = $description; return $this; }
    public function getPublishedYear(): ?int { return $this->publishedYear; }
    public function setPublishedYear(?int $year): self { $this->publishedYear = $year; return $this; }
    public function getAuthors(): Collection { return $this->authors; }
    public function getGenres(): Collection { return $this->genres; }
}
