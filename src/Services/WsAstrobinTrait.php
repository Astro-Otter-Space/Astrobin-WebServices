<?php

declare(strict_types=1);

namespace AstrobinWs\Services;

use AstrobinWs\Exceptions\WsException;
use AstrobinWs\Exceptions\WsResponseException;
use AstrobinWs\Response\AstrobinResponse;
use AstrobinWs\Response\Image;

/**
 * Trait WsAstrobinTrait
 * @package AstrobinWs\Services
 */
trait WsAstrobinTrait
{

    /**
     * @param AstrobinResponse $entity
     *
     * @return AstrobinResponse|null
     * @throws WsException
     * @throws WsResponseException
     * @throws \JsonException
     * @throws \ReflectionException
     */
    protected function getImagesFromResource(AstrobinResponse $entity): ?AstrobinResponse
    {
        if (property_exists($entity, 'image')) {
            $imageId = substr($entity->image, strrpos($entity->image, '/') + 1);
            $image = $this->getWsImage($imageId);

            if ($image instanceof AstrobinResponse) {
                $entity->add($image);
            }
        } elseif (property_exists($entity, 'images') && 0 < count($entity->images)) {
            foreach ($entity->images as $imageUri) {
                $imageId = substr($imageUri, strrpos($imageUri, '/') + 1);
                $image = $this->getWsImage($imageId);
                if ($image instanceof AstrobinResponse) {
                    $entity->add($image);
                }
            }
        }

        return $entity;
    }

    /**
     * @param string $imageId
     *
     * @return AstrobinResponse|null
     * @throws WsException
     * @throws WsResponseException
     * @throws \JsonException
     * @throws \ReflectionException
     */
    private function getWsImage(string $imageId): ?AstrobinResponse
    {
        $imageWs = new GetImage($this->getApiKey(), $this->getApiSecret());
        /** @var Image|AstrobinResponse $image */
        return $imageWs->getById($imageId);
    }
}
