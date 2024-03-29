<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ("sceneDataRecup")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ("sceneDataRecup")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ("sceneDataRecup")
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=Scene1::class, mappedBy="user")
     */
    private $scene1;

    public function __construct()
    {
        $this->scene1 = new ArrayCollection();
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
     * @return Collection<int, Scene1>
     */
    public function getScene1(): Collection
    {
        return $this->scene1;
    }

    public function addScene1(Scene1 $scene1): self
    {
        if (!$this->scene1->contains($scene1)) {
            $this->scene1[] = $scene1;
            $scene1->setUser($this);
        }

        return $this;
    }

    public function removeScene1(Scene1 $scene1): self
    {
        if ($this->scene1->removeElement($scene1)) {
            // set the owning side to null (unless already changed)
            if ($scene1->getUser() === $this) {
                $scene1->setUser(null);
            }
        }

        return $this;
    }
}
