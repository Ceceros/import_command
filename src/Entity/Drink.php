<?php

namespace App\Entity;

use App\Repository\DrinkRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DrinkRepository::class)]
class Drink
{
    #[ORM\Id]
    #[ORM\Column(unique:true)]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $CategoryName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Sku = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Description = null;

    #[ORM\Column(nullable: true)]
    private ?float $Price = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Link = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Image = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Brand = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $Rating = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Caffeine = null;

    #[ORM\Column(nullable: true)]
    private ?int $Count = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $Flavored = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $Seasonal = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Instock = null;

    #[ORM\Column(nullable:true)]
    private ?int $Facebook = null;

    #[ORM\Column(nullable: true)]
    private ?int $IsKCup = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Shortdesc = null;

    public function __construct(int $id, string $CategoryName, string $Sku, string $Name, string $Description, float $Price, 
    string $Link, string $Image, string $Brand, int $Rating, string $Caffeine, int $Count, string $Flavored, string $Seasonal, 
    string $Instock, int $Facebook, int $IsKCup, string $Shortdesc)
    {
        $this->id= $id; 
        $this->CategoryName= $CategoryName; 
        $this->Sku= $Sku; 
        $this->Name= $Name; 
        $this->Description= $Description; 
        $this->Price= $Price; 
        $this->Link= $Link; 
        $this->Image= $Image; 
        $this->Brand= $Brand; 
        $this->Rating= $Rating; 
        $this->Caffeine= $Caffeine;
        $this->Count= $Count; 
        $this->Flavored= $Flavored; 
        $this->Seasonal= $Seasonal; 
        $this->Instock= $Instock; 
        $this->Facebook= $Facebook; 
        $this->IsKCup= $IsKCup; 
        $this->Shortdesc= $Shortdesc; 
 
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategoryName(): ?string
    {
        return $this->CategoryName;
    }

    public function setCategoryName(?string $CategoryName): static
    {
        $this->CategoryName = $CategoryName;

        return $this;
    }

    public function getSku(): ?string
    {
        return $this->Sku;
    }

    public function setSku(?string $Sku): static
    {
        $this->Sku = $Sku;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): static
    {
        $this->Name = $Name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): static
    {
        $this->Description = $Description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->Price;
    }

    public function setPrice(?float $Price): static
    {
        $this->Price = $Price;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->Link;
    }

    public function setLink(?string $Link): static
    {
        $this->Link = $Link;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(?string $Image): static
    {
        $this->Image = $Image;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->Brand;
    }

    public function setBrand(string $Brand): static
    {
        $this->Brand = $Brand;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->Rating;
    }

    public function setRating(?int $Rating): static
    {
        $this->Rating = $Rating;

        return $this;
    }

    public function getCaffeine(): ?string
    {
        return $this->Caffeine;
    }

    public function setCaffeine(?string $Caffeine): static
    {
        $this->Caffeine = $Caffeine;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->Count;
    }


    public function getFlavored(): ?string
    {
        return $this->Flavored;
    }

    public function setFlavored(?string $Flavored): static
    {
        $this->Flavored = $Flavored;

        return $this;
    }

    public function getSeasonal(): ?string
    {
        return $this->Seasonal;
    }

    public function setSeasonal(string $Seasonal): static
    {
        $this->Seasonal = $Seasonal;

        return $this;
    }

    public function getInstock(): ?string
    {
        return $this->Instock;
    }

    public function setInstock(string $Instock): static
    {
        $this->Instock = $Instock;

        return $this;
    }

    public function getFacebook(): ?int
    {
        return $this->Facebook;
    }

    public function setFacebook(?int $Facebook): static
    {
        $this->Facebook = $Facebook;

        return $this;
    }

    public function getIsKCup(): ?int
    {
        return $this->IsKCup;
    }

    public function setIsKCup(int $IsKCup): static
    {
        $this->IsKCup = $IsKCup;

        return $this;
    }

    public function getShortdesc(): ?string
    {
        return $this->Shortdesc;
    }

    public function setShortdesc(?string $Shortdesc): static
    {
        $this->Shortdesc = $Shortdesc;

        return $this;
    }
}
