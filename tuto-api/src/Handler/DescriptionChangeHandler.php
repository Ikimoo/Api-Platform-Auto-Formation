<?php

namespace App\Handler;

use App\DTO\DescriptionChangeInput;
use App\Repository\CheeseListingRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class DescriptionChangeHandler implements MessageHandlerInterface
{
    private $cheeseListingRepository;
    
    public function __construct(CheeseListingRepository $cheeseListingRepository)
    {
        $this->cheeseListingRepository = $cheeseListingRepository;
    }
    
    public function __invoke(DescriptionChangeInput $descriptionChange)
    {
        $cheeseId = $this->cheeseListingRepository->find($descriptionChange->cheese->getId());
        return $cheeseId->setDescription($descriptionChange->getDescription());
    }
}