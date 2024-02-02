<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\SlugTrait;
use App\Repository\ProductsRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductsRepository::class)]
class Products
{

    use CreatedAtTrait;
    use SlugTrait;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    // On ajoute la propriété Assert pour que le champ ne soit pas vide et avec des contraintes
    #[Assert\NotBlank(message: 'Le nom du produit ne peut pas être vide')]
    #[Assert\Length(
        min: 3,
        max: 180,
        minMessage: 'Le nom du produit doit contenir au moins {{ limit }} caractères',
        maxMessage: 'Le nom du produit doit contenir au maximum {{ limit }} caractères'
    )]
    private ?string $name = null;
    

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
      // On ajoute la propriété Assert pour que le champ ne soit pas vide et avec des contraintes
    #[Assert\Positive(message: 'Le prix ne peut pas être négatif')]
    private ?float $price = null;

    #[ORM\Column]
    private ?int $stock = null;
    // On ajoute la propriété Assert pour que le champ ne soit pas vide et avec des contraintes
   

// On ajoute la propriété current_timestamp pour que la date soit automatiquement ajoutée

    // #[ORM\Column]
    // private ?\DateTimeImmutable $created_at = null;

    // #[ORM\Column(type: 'datetime_immutable', 
    // options: ['default' => 'CURRENT_TIMESTAMP']
    // )]
    // private $created_at;


    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categories $categories = null;


//On ajoute la propriété cascade pour que les images soient automatiquement ajoutées
    #[ORM\OneToMany(mappedBy: 'products', targetEntity: Images::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $images;

    #[ORM\OneToMany(mappedBy: 'products', targetEntity: OrdersDetails::class)]
    private Collection $ordersDetails;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->ordersDetails = new ArrayCollection();
       // On ajoute la propriété current_timestamp pour que la date soit automatiquement ajoutée 
        // car sinon on a une erreur dans le formulaire d'inscription
        $this->created_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    // public function getCreatedAt(): ?\DateTimeImmutable
    // {
    //     return $this->created_at;
    // }

    // public function setCreatedAt(\DateTimeImmutable $created_at): static
    // {
    //     $this->created_at = $created_at;

    //     return $this;
    // }

    public function getCategories(): ?Categories
    {
        return $this->categories;
    }

    public function setCategories(?Categories $categories): static
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return Collection<int, Images>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setProducts($this);
        }

        return $this;
    }

    public function removeImage(Images $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProducts() === $this) {
                $image->setProducts(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OrdersDetails>
     */
    public function getOrdersDetails(): Collection
    {
        return $this->ordersDetails;
    }

    public function addOrdersDetail(OrdersDetails $ordersDetail): static
    {
        if (!$this->ordersDetails->contains($ordersDetail)) {
            $this->ordersDetails->add($ordersDetail);
            $ordersDetail->setProducts($this);
        }

        return $this;
    }

    public function removeOrdersDetail(OrdersDetails $ordersDetail): static
    {
        if ($this->ordersDetails->removeElement($ordersDetail)) {
            // set the owning side to null (unless already changed)
            if ($ordersDetail->getProducts() === $this) {
                $ordersDetail->setProducts(null);
            }
        }

        return $this;
    }
}
