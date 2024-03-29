<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\Scene1Repository;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=Scene1Repository::class)
 * @Vich\Uploadable
 */
class Scene1
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ("sceneDataRecup")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups ("sceneDataRecup")
     */
    private $color;

    /**
     * @ORM\Column(type="integer")
     * @Groups ("sceneDataRecup")
     */
    private $weight;

    /**
     * @ORM\Column(type="integer")
     * @Groups ("sceneDataRecup")
     */
    private $numLine;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="scene1")
     * @ORM\JoinColumn(nullable=false)
     * @Groups ("sceneDataRecup")
     */
    private $user;

// --------- VICH UPLOADER-----------------

    /**
     * @Vich\UploadableField(mapping="sceneImages", fileNameProperty="imageName")
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageName;

    /**
    * @ORM\Column(type="datetime_immutable", nullable=true)
    */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getColor(): ?int
    {
        return $this->color;
    }

    public function setColor(int $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getNumLine(): ?int
    {
        return $this->numLine;
    }

    public function setNumLine(int $numLine): self
    {
        $this->numLine = $numLine;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't b    e called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
