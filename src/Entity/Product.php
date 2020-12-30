<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Le produit doit avoir une dénomination" 
     * )
     * @Assert\Length(
     *      min = 3,
     *      max = 255,
     *      minMessage = "Le nom du produit doit avoir {{ limit }} caractères minimum",
     *      maxMessage = "Le nom du produit doit avoir {{ limit }} caractères maximum"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(
     *      message = "Il doit avoir une quantité définit"
     * )
     * @Assert\Positive(
     *      message = "La quantité doit être un nombre supérieur à 0"
     * )
     */
    private $quantity;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\Type(\DateTime::class)
     */
    private $dlc;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(
     *      message = "La contenance doit être indiqué"
     * )
     * @Assert\Positive(
     *      message = "La contenance doit être un nombre supérieur à 0"
     * )
     */
    private $capacity;

    /**
     * @ORM\Column(type="string", length=3)
     * @Assert\NotBlank(
     *      message = "L'unité de mesure doit être indiqué"
     * )
     * @Assert\Length(
     *      min = 1,
     *      max = 3,
     *      minMessage = "L'unité de mesure doit avoir {{ limit }} caractères minimum",
     *      maxMessage = "L'unité de mesure doit avoir {{ limit }} caractères maximum"
     * )
     */
    private $unit_measure_capacity;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;


    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;




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

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getDlc(): ?\DateTimeInterface
    {
        return $this->dlc;
    }

    public function setDlc(?\DateTimeInterface $dlc): self
    {
        $this->dlc = $dlc;

        return $this;
    }


    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getUnitMeasureCapacity(): ?string
    {
        return $this->unit_measure_capacity;
    }

    public function setUnitMeasureCapacity(string $unit_measure_capacity): self
    {
        $this->unit_measure_capacity = $unit_measure_capacity;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }



}
