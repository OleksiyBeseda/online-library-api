
---

# 3️⃣ `src/Entity/User.php`

```php
<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: "users")]
class User implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private int $id;

    #[ORM\Column(type:"string", unique:true)]
    private string $email;

    #[ORM\Column(type:"string")]
    private string $password;

    #[ORM\Column(type:"string")]
    private string $role = 'CLIENT';

    public function getId(): int { return $this->id; }
    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): self { $this->email = $email; return $this; }
    public function getPassword(): string { return $this->password; }
    public function setPassword(string $password): self { $this->password = $password; return $this; }
    public function getRole(): string { return $this->role; }
    public function setRole(string $role): self { $this->role = $role; return $this; }

    // UserInterface
    public function getRoles(): array { return [$this->role]; }
    public function getSalt(): ?string { return null; }
    public function getUsername(): string { return $this->email; }
    public function eraseCredentials(): void {}
}
