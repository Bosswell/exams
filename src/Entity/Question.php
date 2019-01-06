<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 * @Vich\Uploadable
 */
class Question
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $query;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $answerA;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $answerB;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $answerC;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $answerD;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(
     *      min = 1,
     *      max = 4,
     *      minMessage = "You must enter [1, 2, 3, 4] values",
     *      maxMessage = "You must enter [1, 2, 3, 4] values"
     * )
     */
    private $correct;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Image name cannot be longer than {{ limit }} characters"
     * )
     */
    private $imageName;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     * @Assert\DateTime
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Qualification", inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $qualification;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     */
    private $updatedAt;
	
	/**
     * @var integer
     */
    private $user_answer;
	
	/**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="exam_question_directory", fileNameProperty="imageName")
     * @Assert\File()
     * @Assert\Image(
     *   mimeTypes = {
     *     "image/png",
     *     "image/jpeg",
     *     "image/jpg"
     *   },
     *   mimeTypesMessage = "Invalid mime type. Allowed types [png, jpeg, jpg]"
     * )
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "Session name cannot be longer than {{ limit }} characters"
     * )
     */
    private $session;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     * @Assert\Length(
     *      max = 4,
     *      maxMessage = "Year cannot be longer than {{ limit }} characters"
     * )
     */
    private $year;

    public function __construct()
    {
        $now = new \DateTime('now');
        $this->createdAt = $now;
        $this->updatedAt = $now;
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
	
	public function setUserAnswer(int $user_answer) : self
                               {
                                   $this->user_answer = $user_answer;
                           
                                   return $this;
                               }

    public function getUserAnswer() : int
    {
        return $this->user_answer;
    }

    public function getQuery(): ?string
    {
        return $this->query;
    }

    public function setQuery(string $query): self
    {
        $this->query = $query;

        return $this;
    }

    public function getAnswerA(): ?string
    {
        return $this->answerA;
    }

    public function setAnswerA(string $answerA): self
    {
        $this->answerA = $answerA;

        return $this;
    }

    public function getAnswerB(): ?string
    {
        return $this->answerB;
    }

    public function setAnswerB(string $answerB): self
    {
        $this->answerB = $answerB;

        return $this;
    }

    public function getAnswerC(): ?string
    {
        return $this->answerC;
    }

    public function setAnswerC(string $answerC): self
    {
        $this->answerC = $answerC;

        return $this;
    }

    public function getAnswerD(): ?string
    {
        return $this->answerD;
    }

    public function setAnswerD(string $answerD): self
    {
        $this->answerD = $answerD;

        return $this;
    }

    public function getCorrect(): ?int
    {
        return $this->correct;
    }

    public function setCorrect(int $correct): self
    {
        $this->correct = $correct;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getQualification(): ?Qualification
    {
        return $this->qualification;
    }

    public function setQualification(?Qualification $qualification): self
    {
        $this->qualification = $qualification;

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

    public function getSession(): ?string
    {
        return $this->session;
    }

    public function setSession(?string $session): self
    {
        $this->session = $session;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(?string $year): self
    {
        $this->year = $year;

        return $this;
    }
}
