<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RecipeRepository::class)
 */
class Recipe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $nb_person;

    /**
     * @ORM\Column(type="integer")
     */
    private $preparation_time;

    /**
     * @ORM\Column(type="integer")
     */
    private $cooking_time;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;

    /**
     * @ORM\OneToMany(targetEntity=IngredientRecipe::class, mappedBy="recipe", orphanRemoval=true, cascade={"persist","remove"})
     */
    private $ingredients;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\ManyToMany(targetEntity=CategoryRecipe::class, inversedBy="recipes", cascade={"persist"})
     */
    private $categoryRecipes;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="recipes")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=PreparationRecipe::class, mappedBy="recipe", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $preparations;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->preparation = new ArrayCollection();
        $this->categoryRecipes = new ArrayCollection();
        $this->preparations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbPerson(): ?int
    {
        return $this->nb_person;
    }

    public function setNbPerson(int $nb_person): self
    {
        $this->nb_person = $nb_person;

        return $this;
    }

    public function getPreparationTime(): ?int
    {
        return $this->preparation_time;
    }

    public function setPreparationTime(int $preparation_time): self
    {
        $this->preparation_time = $preparation_time;

        return $this;
    }

    public function getCookingTime(): ?int
    {
        return $this->cooking_time;
    }

    public function setCookingTime(int $cooking_time): self
    {
        $this->cooking_time = $cooking_time;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    /**
     * @return Collection|IngredientRecipe[]
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(IngredientRecipe $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
            $ingredient->setRecipe($this);
        }

        return $this;
    }

    public function removeIngredient(IngredientRecipe $ingredient): self
    {
        if ($this->ingredients->removeElement($ingredient)) {
            // set the owning side to null (unless already changed)
            if ($ingredient->getRecipe() === $this) {
                $ingredient->setRecipe(null);
            }
        }

        return $this;
    }


    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return Collection|CategoryRecipe[]
     */
    public function getCategoryRecipes(): Collection
    {
        return $this->categoryRecipes;
    }

    public function addCategoryRecipe(CategoryRecipe $categoryRecipe): self
    {
        if (!$this->categoryRecipes->contains($categoryRecipe)) {
            $this->categoryRecipes[] = $categoryRecipe;
            $categoryRecipe->addRecipe($this);
        }

        return $this;
    }

    public function removeCategoryRecipe(CategoryRecipe $categoryRecipe): self
    {
        if ($this->categoryRecipes->removeElement($categoryRecipe)) {
            $categoryRecipe->removeRecipe($this);
        }

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
     * @return Collection|PreparationRecipe[]
     */
    public function getPreparations(): Collection
    {
        return $this->preparations;
    }

    public function addPreparation(PreparationRecipe $preparation): self
    {
        if (!$this->preparations->contains($preparation)) {
            $this->preparations[] = $preparation;
            $preparation->setRecipe($this);
        }

        return $this;
    }

    public function removePreparation(PreparationRecipe $preparation): self
    {
        if ($this->preparations->removeElement($preparation)) {
            // set the owning side to null (unless already changed)
            if ($preparation->getRecipe() === $this) {
                $preparation->setRecipe(null);
            }
        }

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
}
