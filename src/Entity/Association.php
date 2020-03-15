<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AssociationRepository")
 */
class Association
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AssociationHourly", mappedBy="association")
     */
    private $associationHourlies;

    public function __construct()
    {
        $this->associationHourlies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|AssociationHourly[]
     */
    public function getAssociationHourlies(): Collection
    {
        return $this->associationHourlies;
    }

    public function addAssociationHourly(AssociationHourly $associationHourly): self
    {
        if (!$this->associationHourlies->contains($associationHourly)) {
            $this->associationHourlies[] = $associationHourly;
            $associationHourly->setAssociation($this);
        }

        return $this;
    }

    public function removeAssociationHourly(AssociationHourly $associationHourly): self
    {
        if ($this->associationHourlies->contains($associationHourly)) {
            $this->associationHourlies->removeElement($associationHourly);
            // set the owning side to null (unless already changed)
            if ($associationHourly->getAssociation() === $this) {
                $associationHourly->setAssociation(null);
            }
        }

        return $this;
    }
}
