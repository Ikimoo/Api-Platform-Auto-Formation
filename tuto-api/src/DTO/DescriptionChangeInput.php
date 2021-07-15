<?php

namespace App\DTO;

use App\Entity\CheeseListing;

final class DescriptionChangeInput 
{
    /**
     * @var CheeseListing
     */
    public $cheese;
    public $description;

    public function __construct(CheeseListing $cheese)
    {
        $this->cheese = $cheese;
    }

    /**
     * Get the value of cheeseListing
     */
    public function getCheeseListing()
    {
        return $this->cheeseListing;
    }

    /**
     * Get the value of description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     */
    public function setDescription($description): self
    {
        $this->description = $description;

        return $this;
    }
}