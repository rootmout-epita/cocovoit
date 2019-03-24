<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PreferenceRepository")
 */
class Preference
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $icone_path;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserPreference", mappedBy="preference")
     */
    private $userPreferences;

    public function __construct()
    {
        $this->userPreferences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIconePath(): ?string
    {
        return $this->icone_path;
    }

    public function setIconePath(?string $icone_path): self
    {
        $this->icone_path = $icone_path;

        return $this;
    }

    /**
     * @return Collection|UserPreference[]
     */
    public function getUserPreferences(): Collection
    {
        return $this->userPreferences;
    }

    public function addUserPreference(UserPreference $userPreference): self
    {
        if (!$this->userPreferences->contains($userPreference)) {
            $this->userPreferences[] = $userPreference;
            $userPreference->setPreference($this);
        }

        return $this;
    }

    public function removeUserPreference(UserPreference $userPreference): self
    {
        if ($this->userPreferences->contains($userPreference)) {
            $this->userPreferences->removeElement($userPreference);
            // set the owning side to null (unless already changed)
            if ($userPreference->getPreference() === $this) {
                $userPreference->setPreference(null);
            }
        }

        return $this;
    }
}
