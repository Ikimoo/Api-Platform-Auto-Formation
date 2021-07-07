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
        "get" => [
            "path" => "/cheeses-everywheeeeere",
            "openapi_context" => [
                "summary" => "Get a collection of cheeses for our pretty eyes to see",
                "description" => "THROOOOOWWWW THE CHEEEEEEESEEE !"
            ]
        ],
        "post"
    ],
    itemOperations: [
        "get" => [
            "normalization_context" => [
                "groups" => [
                    "cheese_listing:read", "cheese_listing:item:get"
                ]
            ]
        ],
        "put",
        "patch" => [
            "normalization_context" => [
                    "validation_groups" => ["cheese:name:patch"]
            ]
            ],
        "twoInOne" => [
            "method" => "PATCH",
            "path" => "/patch/twoInOne",
            "denormalization_context" => [
                "groups" => [
                    "patch:change"
                ]
                ],
                "openapi_context" => [
                    "summary" => "twoInOne will change the title and the description in one go !",
                    "description" => "Just enter a value to see !"
                ]
        ]
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
        Groups(["cheese_listing:read", "cheese_listing:write", "user:read", "user:write", "patch:change"]),
        Assert\NotBlank(),
        Assert\Length(min: 2, max: 50, maxMessage: "Describe your cheese in 50 characters or less")
    ]
    #[Assert\Length(min: 2, max:30, groups: ["cheese:name:patch"])]
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    #[
        Groups(["cheese_listing:read", "cheese_listing:write", "user:read", "user:write", "patch:change"]),
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

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(["patch:change"])]
    private $twoInOne;

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

    public function getTwoInOne(): ?string
    {
        return $this->twoInOne;
    }

    public function setTwoInOne(?string $twoInOne): self
    {
        $this->twoInOne = $twoInOne;

        return $this;
    }
}
