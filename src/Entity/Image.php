<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
   
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $img;

    /**
     * @ORM\ManyToOne(targetEntity=Swap::class, inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idswap;

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getIdswap(): ?Swap
    {
        return $this->idswap;
    }

    public function setIdswap(?Swap $idswap): self
    {
        $this->idswap = $idswap;

        return $this;
    }
}
