<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(
     *      message = "Entrez une adresse mail"
     * )
     * @Assert\Email(
     *      mode = "strict",
     *      message = "L'adresse email '{{ value }}' n'est pas une adresse valide"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *      message = "Entrez un prénom"
     * )
     * @Assert\Length(
     *      min = 2,
     *      minMessage = "Le prénom doit contenir {{ limit }} caractères minimum",
     * )
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *      message = "Entrez un nom"
     * )
     * @Assert\Length(
     *      min = 2,
     *      minMessage = "Le nom doit contenir {{ limit }} caractères minimum",
     * )
     */
    private $lastname;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="user", orphanRemoval=true)
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity=Log::class, mappedBy="user", orphanRemoval=true)
     */
    private $logs;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\OneToMany(targetEntity=Category::class, mappedBy="user", orphanRemoval=true)
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity=PictureStock::class, mappedBy="user")
     */
    private $pictureStocks;

    /**
     * @ORM\OneToMany(targetEntity=CategoryRecipe::class, mappedBy="user")
     */
    private $categoryRecipes;

    /**
     * @ORM\OneToMany(targetEntity=Recipe::class, mappedBy="user")
     */
    private $recipes;


    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->logs = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->pictureStocks = new ArrayCollection();
        $this->categoryRecipes = new ArrayCollection();
        $this->recipes = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
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
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setUser($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getUser() === $this) {
                $product->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Log[]
     */
    public function getLogs(): Collection
    {
        return $this->logs;
    }

    public function addLog(Log $log): self
    {
        if (!$this->logs->contains($log)) {
            $this->logs[] = $log;
            $log->setUser($this);
        }

        return $this;
    }

    public function removeLog(Log $log): self
    {
        if ($this->logs->removeElement($log)) {
            // set the owning side to null (unless already changed)
            if ($log->getUser() === $this) {
                $log->setUser(null);
            }
        }

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function addRoles(string $roles): self
    {
        if(!in_array($roles, $this->roles)) {
            $this->roles[] = $roles;
        }

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setUser($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getUser() === $this) {
                $category->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PictureStock[]
     */
    public function getPictureStocks(): Collection
    {
        return $this->pictureStocks;
    }

    public function addPictureStock(PictureStock $pictureStock): self
    {
        if (!$this->pictureStocks->contains($pictureStock)) {
            $this->pictureStocks[] = $pictureStock;
            $pictureStock->setUser($this);
        }

        return $this;
    }

    public function removePictureStock(PictureStock $pictureStock): self
    {
        if ($this->pictureStocks->removeElement($pictureStock)) {
            // set the owning side to null (unless already changed)
            if ($pictureStock->getUser() === $this) {
                $pictureStock->setUser(null);
            }
        }

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
            $categoryRecipe->setUser($this);
        }

        return $this;
    }

    public function removeCategoryRecipe(CategoryRecipe $categoryRecipe): self
    {
        if ($this->categoryRecipes->removeElement($categoryRecipe)) {
            // set the owning side to null (unless already changed)
            if ($categoryRecipe->getUser() === $this) {
                $categoryRecipe->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Recipe[]
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipe $recipe): self
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes[] = $recipe;
            $recipe->setUser($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): self
    {
        if ($this->recipes->removeElement($recipe)) {
            // set the owning side to null (unless already changed)
            if ($recipe->getUser() === $this) {
                $recipe->setUser(null);
            }
        }

        return $this;
    }
}
