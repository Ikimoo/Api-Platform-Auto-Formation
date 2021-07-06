<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use App\Repository\CheeseListingRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;

/**
 * @ORM\Entity(repositoryClass=CheeseListingRepository::class)
 */
#[ApiResource(
    collectionOperations: [
        "get", "post"
    ],
    itemOperations: [
        "get" => [
            "normalization_context" => [
                "groups" => [
                    "cheese_listing:read", "cheese_listing:item:get"
                ]
            ]
        ],
        "put"
    ],
    paginationItemsPerPage: 10,
    shortName: "cheeses",
    normalizationContext: [
        "groups" => ["cheese_listing:read"]
    ],
    denormalizationContext: [
        "groups" => ["cheese_listing:write"]
    ],

)]
#[ApiFilter(BooleanFilter::class, properties: ["isPublished"])]
#[ApiFilter(SearchFilter::class, properties: ["title" => "partial", "description" => "partial"])]
#[ApiFilter(RangeFilter::class, properties: ["price"])]
#[ApiFilter(PropertyFilter::class)]
class CheeseListing
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[
        Groups(["cheese_listing:read", "cheese_listing:write", "user:read", "user:write"]),
        Assert\NotBlank(),
        Assert\Length(min: 2, max: 50, maxMessage: "Describe your cheese in 50 characters or less")
    ]
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    #[
        Groups(["cheese_listing:read", "cheese_listing:write", "user:read", "user:write"]),
    ]
    private $description;

    /**
     * The price of this delicious cheese, in cents
     * @ORM\Column(type="integer")
     */
    #[
        Groups(["cheese_listing:read", "cheese_listing:write", "user:read", "user:write"]),
        Assert\NotBlank(),
    ]
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    #[
        Groups(["cheese_listing:read"]),
    ]
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished = false;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="cheeseListings", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    #[
        Groups(["cheese_listing:read", "cheese_listing:write", "user:write"]),
        Assert\Valid()
    ]
    private $owner;

    public function __construct(string $title = null)
    {
        $this->title = $title;
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

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

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
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
}
