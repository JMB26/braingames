<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentsRepository::class)
 */
class Comments
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;   
   
    /**
     * @ORM\Column(type="text")
     */
    private $txt;

    /**
     * @ORM\ManyToOne(targetEntity=Games::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idgame;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $iduser;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="writer")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idwriter;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIdgame(): ?Games
    {
        return $this->idgame;
    }

    public function setIdgame(?Games $idgame): self
    {
        $this->idgame = $idgame;

        return $this;
    }

    public function getIduser(): ?User
    {
        return $this->iduser;
    }

    public function setIduser(?User $iduser): self
    {
        $this->iduser = $iduser;

        return $this;
    }

    public function getIdwriter(): ?User
    {
        return $this->idwriter;
    }

    public function setIdwriter(?User $idwriter): self
    {
        $this->idwriter = $idwriter;

        return $this;
    }
}
