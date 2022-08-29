<?php

namespace App\Entity;

use App\Repository\GamesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GamesRepository::class)
 */
class Games
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $mark;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nom;

    /**
     * @ORM\Column(type="integer")
     */
    private $isbn;

    /**
     * @ORM\Column(type="integer")
     */
    private $age;   

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $img;

    /**
     * @ORM\Column(type="text")
     */
    private $txt;

    /**
     * @ORM\Column(type="integer")
     */
    private $note;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="games")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idcat;

    /**
     * @ORM\OneToMany(targetEntity=Swap::class, mappedBy="idgameuser")
     */
    private $swaps;

    /**
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="idgame")
     */
    private $comments;

  

    public function __construct()
    {
        $this->swaps = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMark(): ?string
    {
        return $this->mark;
    }

    public function setMark(string $mark): self
    {
        $this->mark = $mark;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getIsbn(): ?int
    {
        return $this->isbn;
    }

    public function setIsbn(int $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }     

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getTxt(): ?string
    {
        return $this->txt;
    }

    public function setTxt(string $txt): self
    {
        $this->txt = $txt;

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

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

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

    public function getIdcat(): ?Categories
    {
        return $this->idcat;
    }

    public function setIdcat(?Categories $idcat): self
    {
        $this->idcat = $idcat;

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
            $swap->setIdgameuser($this);
        }

        return $this;
    }

    public function removeSwap(Swap $swap): self
    {
        if ($this->swaps->removeElement($swap)) {
            // set the owning side to null (unless already changed)
            if ($swap->getIdgameuser() === $this) {
                $swap->setIdgameuser(null);
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
            $comment->setIdgame($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getIdgame() === $this) {
                $comment->setIdgame(null);
            }
        }

        return $this;
    }  
   
}
