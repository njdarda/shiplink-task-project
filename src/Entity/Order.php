<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Controller\Api\RecreateOrderController;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 * @ApiResource(
 *     attributes={"security"="is_granted('ROLE_USER')"},
 *     collectionOperations={
 *         "get",
 *         "post",
 *     },
 *     itemOperations={
 *         "get"={"security"="is_granted('ROLE_ADMIN') or object.getOwner() == user"},
 *         "patch"={
 *             "security"="is_granted('ROLE_ADMIN') or object.getOwner() == user",
 *             "denormalizationContext"={"groups"={"order:input:patch"}},
 *          },
 *         "recreate"={
 *             "method"="GET",
 *             "path"="/orders/{id}/recreate",
 *             "controller"=RecreateOrderController::class,
 *             "openapi_context"={
 *                 "summary"="Creates a new Order based on a cancelled Order",
 *                 "description"="Creates a new Order based on a cancelled Order",
 *             }
 *         },
 *     },
 *     normalizationContext={"groups"={"order:output"}},
 *     denormalizationContext={"groups"={"order:input"}}
 * )
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"order:output"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"order:output"})
     */
    private $owner;

    /**
     * @ORM\Column(type="boolean", options={"default": 0})
     * @Groups({"order:output", "order:input", "order:input:patch"})
     */
    private $cancelled = false;

    /**
     * @ORM\ManyToMany(targetEntity=Product::class, mappedBy="owningOrder")
     * @Groups({"order:output", "order:input"})
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function isCancelled(): ?bool
    {
        return $this->cancelled;
    }

    public function setCancelled(bool $cancelled): self
    {
        $this->cancelled = $cancelled;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function setProducts(Collection $products): self {
        $this->products = $products;

        return $this;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addOwningOrder($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            $product->removeOwningOrder($this);
        }

        return $this;
    }
}
