<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trait\CreatedAtTrait;
use App\Repository\OrdersRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: OrdersRepository::class)]
class Orders
{

    // On ajoute le trait CreatedAtTrait pour que la date soit automatiquement ajoutée
    use CreatedAtTrait;


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    // On ajoute la propriété unique:true pour que la référence soit unique

    #[ORM\Column(length: 20, unique: true)]
    private ?string $reference = null;

    // On ajoute la propriété current_timestamp pour que la date soit automatiquement ajoutée

    // #[ORM\Column]
    // private ?\DateTimeImmutable $created_at = null;

    // #[ORM\Column(
    //     type: 'datetime_immutable',
    //     options: ['default' => 'CURRENT_TIMESTAMP']
    // )]
    // private $created_at;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?Coupons $coupons = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $users = null;

    #[ORM\OneToMany(mappedBy: 'orders', targetEntity: OrdersDetails::class, orphanRemoval: true)]
    private Collection $ordersDetails;

    public function __construct()
    {
        $this->ordersDetails = new ArrayCollection();
         // On ajoute la propriété current_timestamp pour que la date soit automatiquement ajoutée 
        // car sinon on a une erreur dans le formulaire d'inscription
        $this->created_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

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

    public function getCoupons(): ?Coupons
    {
        return $this->coupons;
    }

    public function setCoupons(?Coupons $coupons): static
    {
        $this->coupons = $coupons;

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): static
    {
        $this->users = $users;

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
            $ordersDetail->setOrders($this);
        }

        return $this;
    }

    public function removeOrdersDetail(OrdersDetails $ordersDetail): static
    {
        if ($this->ordersDetails->removeElement($ordersDetail)) {
            // set the owning side to null (unless already changed)
            if ($ordersDetail->getOrders() === $this) {
                $ordersDetail->setOrders(null);
            }
        }

        return $this;
    }
}
