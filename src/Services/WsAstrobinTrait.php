<?php

declare(strict_types=1);

namespace AstrobinWs\Services;

use AstrobinWs\Exceptions\WsException;
use AstrobinWs\Exceptions\WsResponseException;
use AstrobinWs\Response\DTO\AstrobinResponse;

/**
 * Trait WsAstrobinTrait
 * @package AstrobinWs\Services
 */
trait WsAstrobinTrait
{
    /**
     * @throws WsException
     * @throws WsResponseException
     * @throws \JsonException
     * @throws \ReflectionException
     */
    protected function getImagesFromResource(AstrobinResponse $entity): ?AstrobinResponse
    {
        if (property_exists($entity, 'image')) {
            $imageId = substr((string) $entity->image, strrpos((string) $entity->image, '/') + 1);
            $image = $this->getWsImage($imageId);

            if ($image instanceof AstrobinResponse) {
                $entity->add($image);
            }
        } elseif (property_exists($entity, 'images') && 0 < count($entity->images)) {
            foreach ($entity->images as $imageUri) {
                $imageId = substr((string) $imageUri, strrpos((string)$imageUri, '/') + 1);
                $image = $this->getWsImage($imageId);
                if ($image instanceof AstrobinResponse) {
                    $entity->add($image);
                }
            }
        }

        return $entity;
    }

    /**
     * @throws WsException
     * @throws WsResponseException
     * @throws \JsonException
     * @throws \ReflectionException
     */
    private function getWsImage(string $imageId): ?AstrobinResponse
    {
        return (new GetImage($this->getApiKey(), $this->getApiSecret()))->getById($imageId);
    }
}
