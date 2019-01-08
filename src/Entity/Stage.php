<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StageRepository")
 * @Vich\Uploadable
 */
class Stage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 6,
     *      max = 255,
     *      minMessage = "Designation must be at least {{ limit }} characters long",
     *      maxMessage = "Designation cannot be longer than {{ limit }} characters"
     * )
     */
    private $designation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Image name cannot be longer than {{ limit }} characters"
     * )
     */
    private $imageName;
	
	/**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="stage_directory", fileNameProperty="imageName")
     * @Assert\File()
     * @Assert\NotBlank()
     * @Assert\Image(
     *   mimeTypes = {
     *     "image/png",
     *     "image/jpeg",
     *     "image/jpg"
     *   },
     *   mimeTypesMessage = "Invalid mime type. Allowed types [png, jpeg, jpg]"
     * )
     * 
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     * @Assert\DateTime
     * @var string A "Y-m-d H:i:s" formatted value
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Qualification", inversedBy="stages")
     */
    private $qualifications;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 100,
     *      min = 10,
     *      maxMessage = "Friendly url cannot be longer than {{ limit }} characters",
     *      minMessage = "Friendly url must be at least {{ limit }} characters long"
     * )
     */
    private $friendlyUrl;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 100,
     *      min = 20,
     *      maxMessage = "Meta description cannot be longer than {{ limit }} characters",
     *      minMessage = "Meta description must be at least {{ limit }} characters long"
     * )
     */
    private $metaDescription;

    public function __construct()
    {
        $this->qualifications = new ArrayCollection();
    }
	
	public function __toString()
    {
        return $this->designation;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
	
	/**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|Qualification[]
     */
    public function getQualifications(): Collection
    {
        return $this->qualifications;
    }

    public function addQualification(Qualification $qualification): self
    {
        if (!$this->qualifications->contains($qualification)) {
            $this->qualifications[] = $qualification;
        }

        return $this;
    }

    public function removeQualification(Qualification $qualification): self
    {
        if ($this->qualifications->contains($qualification)) {
            $this->qualifications->removeElement($qualification);
        }

        return $this;
    }

    public function getFriendlyUrl(): ?string
    {
        return $this->friendlyUrl;
    }

    public function setFriendlyUrl(string $friendlyUrl): self
    {
        $this->friendlyUrl = $friendlyUrl;

        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(string $metaDescription): self
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }
}
