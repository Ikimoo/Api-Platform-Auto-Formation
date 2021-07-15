<?php

namespace App\Handler;

use App\Entity\CheeseListing;
use App\Repository\CheeseListingRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;


final class TitleChangeHandler implements MessageHandlerInterface
{
    private $cheeseListingRepository;

    public function __construct(CheeseListingRepository $cheeseListingRepository)
    {
        $this->cheeseListingRepository = $cheeseListingRepository;
    }

    public function __invoke(CheeseListing $cheese)
    {
        $cheeseId = $this->cheeseListingRepository->find($cheese->getId());
        return $cheeseId->setTitle($cheese->getTitle());
    }
}