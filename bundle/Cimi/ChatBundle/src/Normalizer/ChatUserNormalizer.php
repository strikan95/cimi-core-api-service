<?php

namespace Cimi\ChatBundle\Normalizer;

use Cimi\ChatBundle\Entity\ChatUserInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ChatUserNormalizer implements NormalizerInterface
{

    /** @param ChatUserInterface $object */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        return [
            'user_id' => $object->getId(),
            'name' => $object->getName()
        ];
    }

    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return $data instanceof ChatUserInterface;
    }
}