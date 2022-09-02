<?php

namespace App\Entity;

use App\Repository\ShapeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ShapeRepository::class)
 */
class Shape
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $etat;

    /**
     * @ORM\OneToMany(targetEntity=Swap::class, mappedBy="idshape")
     */
    private $swaps;

    public function __construct()
    {
        $this->swaps = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    // public function setId($id)
    // {
    //     $this->id = $id;

    //     return $this;
    // }


    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

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
            $swap->setIdshape($this);
        }

        return $this;
    }

    public function removeSwap(Swap $swap): self
    {
        if ($this->swaps->removeElement($swap)) {
            // set the owning side to null (unless already changed)
            if ($swap->getIdshape() === $this) {
                $swap->setIdshape(null);
            }
        }

        return $this;
    }
}
