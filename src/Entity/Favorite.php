<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name:"favorites")]
class Favorite
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity:"User")]
    #[ORM\JoinColumn(name:"user_id", referencedColumnName:"id")]
    private User $user;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity:"Book")]
    #[ORM\JoinColumn(name:"book_id", referencedColumnName:"id")]
    private Book $book;

    public function getUser(): User { return $this->user; }
    public function setUser(User $user): self { $this->user = $user; return $this; }
    public function getBook(): Book { return $this->book; }
    public function setBook(Book $book): self { $this->book = $book; return $this; }
}
