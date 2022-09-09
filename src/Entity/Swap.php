<?php

namespace App\Entity;

use App\Repository\SwapRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SwapRepository::class)
 */
class Swap
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $swapuser;
   
    /**
     * @ORM\Column(type="boolean")
     */
    private $swapbuyer;
    
    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="idswap")
     */
    private $images;

    /**
     * @ORM\ManyToOne(targetEntity=Shape::class, inversedBy="swaps", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idshape;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="swaps", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $iduser;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="swapbuy")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idbuyer;

    /**
     * @ORM\ManyToOne(targetEntity=Games::class, inversedBy="swaps", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idgameuser;

    /**
     * @ORM\ManyToOne(targetEntity=Games::class, inversedBy="swaps")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idgamebuyer;    

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isSwapuser(): ?bool
    {
        return $this->swapuser;
    }

    public function setSwapuser(bool $swapuser): self
    {
        $this->swapuser = $swapuser;

        return $this;
    }

    public function isSwapbuyer(): ?bool
    {
        return $this->swapbuyer;
    }

    public function setSwapbuyer(bool $swapbuyer): self
    {
        $this->swapbuyer = $swapbuyer;

        return $this;
    }
   
    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setIdswap($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getIdswap() === $this) {
                $image->setIdswap(null);
            }
        }

        return $this;
    }

    public function getIdshape(): ?Shape
    {        
        return $this->idshape;
    }

    public function setIdshape(?Shape $idshape): self
    {
        $this->idshape = $idshape;

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

    public function getIdbuyer(): ?User
    {
        return $this->idbuyer;
    }

    public function setIdbuyer(?User $idbuyer): self
    {
        $this->idbuyer = $idbuyer;

        return $this;
    }

    public function getIdgameuser(): ?Games
    {
        return $this->idgameuser;
    }

    public function setIdgameuser(?Games $idgameuser): self
    {
        $this->idgameuser = $idgameuser;

        return $this;
    }

    public function getIdgamebuyer(): ?Games
    {
        return $this->idgamebuyer;
    }

    public function setIdgamebuyer(?Games $idgamebuyer): self
    {
        $this->idgamebuyer = $idgamebuyer;

        return $this;
    }
}
