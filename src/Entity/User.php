<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $typname;

    /**
     * @ORM\Column(type="integer")
     */
    private $note;

    /**
     * @ORM\Column(type="boolean")
     */
    private $news;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $token;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=Swap::class, mappedBy="iduser")
     */
    private $swaps;

    /**
     * @ORM\OneToMany(targetEntity=Swap::class, mappedBy="idbuyer")
     */
    private $swapbuy;

    /**
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="iduser")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="idwriter")
     */
    private $writer;

    public function __construct()
    {
        $this->swaps = new ArrayCollection();
        $this->swapbuy = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->writer = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTypname(): ?string
    {
        return $this->typname;
    }

    public function setTypname(string $typname): self
    {
        $this->typname = $typname;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function isNews(): ?bool
    {
        return $this->news;
    }

    public function setNews(bool $news): self
    {
        $this->news = $news;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Swap>
     */
    public function getSwaps(): Collection
    {
        return $this->swaps;
    }

    public function addSwap(Swap $swap): self
    {
        if (!$this->swaps->contains($swap)) {
            $this->swaps[] = $swap;
            $swap->setIduser($this);
        }

        return $this;
    }

    public function removeSwap(Swap $swap): self
    {
        if ($this->swaps->removeElement($swap)) {
            // set the owning side to null (unless already changed)
            if ($swap->getIduser() === $this) {
                $swap->setIduser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Swap>
     */
    public function getSwapbuy(): Collection
    {
        return $this->swapbuy;
    }

    public function addSwapbuy(Swap $swapbuy): self
    {
        if (!$this->swapbuy->contains($swapbuy)) {
            $this->swapbuy[] = $swapbuy;
            $swapbuy->setIdbuyer($this);
        }

        return $this;
    }

    public function removeSwapbuy(Swap $swapbuy): self
    {
        if ($this->swapbuy->removeElement($swapbuy)) {
            // set the owning side to null (unless already changed)
            if ($swapbuy->getIdbuyer() === $this) {
                $swapbuy->setIdbuyer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comments>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setIduser($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getIduser() === $this) {
                $comment->setIduser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comments>
     */
    public function getWriter(): Collection
    {
        return $this->writer;
    }

    public function addWriter(Comments $writer): self
    {
        if (!$this->writer->contains($writer)) {
            $this->writer[] = $writer;
            $writer->setIdwriter($this);
        }

        return $this;
    }

    public function removeWriter(Comments $writer): self
    {
        if ($this->writer->removeElement($writer)) {
            // set the owning side to null (unless already changed)
            if ($writer->getIdwriter() === $this) {
                $writer->setIdwriter(null);
            }
        }

        return $this;
    }
}
