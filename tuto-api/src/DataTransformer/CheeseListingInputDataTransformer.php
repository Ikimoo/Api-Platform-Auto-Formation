<?php

namespace App\DataTransformer;

use App\Entity\CheeseListing;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\DTO\CheeseInput;

class CheeseListingInputDataTransformer implements DataTransformerInterface
{
    public function transform($data, string $to, array $context = [])
    {
        $existingCheeseListing = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE];
        $existingCheeseListing->setTitle($data->text);
        $existingCheeseListing->setDescription($data->text);
        return $existingCheeseListing;
    }

    
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return array_key_exists("collection_operation_name", $context) && $context["collection_operation_name"] === "twoInOne" && $data instanceof CheeseListing && CheeseListing::class === $to;
    }
}