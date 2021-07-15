<?php

namespace App\Serializer;

use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use App\Entity\CheeseListing;
use App\DTO\DescriptionChangeInput;
use Symfony\Component\HttpFoundation\Request;
use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;

final class CheeseListingContextBuilder implements SerializerContextBuilderInterface
{
    private const PATCH_DESCRIPTION_ITEM_OPERATION_NAME = 'descriptionChange';

    private $decorated;

    public function __construct(SerializerContextBuilderInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    /**
     * {@inheritdoc}
     */
    public function createFromRequest(Request $request, bool $normalization, ?array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);

        if ($normalization || !is_a($context['resource_class'] ?? null, CheeseListing::class, true)) {
            return $context;
        }

        switch ($context['item_operation_name'] ?? null) {
            case self::PATCH_DESCRIPTION_ITEM_OPERATION_NAME:
                $cheese = $request->attributes->get('data');

                if (!$cheese instanceof CheeseListing) {
                    throw new \LogicException(sprintf('Expected data to be an instance of "%s".', CheeseListing::class));
                }

                $context[AbstractNormalizer::DEFAULT_CONSTRUCTOR_ARGUMENTS][DescriptionChangeInput::class] = [
                    'cheese' => $cheese,
                ];
                break;
            default:
                break;
        }

        return $context;
    }
}