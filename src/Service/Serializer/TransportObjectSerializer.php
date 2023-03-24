<?php
namespace App\Service\Serializer;

use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\UidNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class TransportObjectSerializer implements SerializerInterface
{
    private const DATETIME_FORMAT = 'Y-m-d';

    private SerializerInterface $serializer;

    public function __construct()
    {
        $objectNormalizer = new ObjectNormalizer(
            null,
            new CamelCaseToSnakeCaseNameConverter(),
            null,
            new ReflectionExtractor()
        );

        $normalizers = [
            new DateTimeNormalizer(
                defaultContext: [DateTimeNormalizer::FORMAT_KEY => self::DATETIME_FORMAT]
            ),
            new UidNormalizer(),
            new ArrayDenormalizer(),
            $objectNormalizer
        ];

        $serializers = [
            new JsonEncoder()
        ];

        $this->serializer = new Serializer($normalizers, $serializers);
    }

    public static function getDateTimeFormat(): string
    {
        return self::DATETIME_FORMAT;
    }

    public function serialize(mixed $data, string $format, array $context = []): string
    {
        return $this->serializer->serialize(
            $data,
            $format,
            $context
        );
    }

    public function deserialize(mixed $data, string $type, string $format, array $context = []): mixed
    {
        return $this->serializer->deserialize(
            $data,
            $type,
            $format,
            $context
        );
    }
}