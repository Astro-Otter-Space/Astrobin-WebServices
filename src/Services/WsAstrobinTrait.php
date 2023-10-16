<?php

namespace AstrobinWs\Services;

use AstrobinWs\Exceptions\WsException;
use AstrobinWs\Exceptions\WsResponseException;
use AstrobinWs\Response\DTO\AstrobinError;
use AstrobinWs\Response\DTO\AstrobinResponse;
use AstrobinWs\Response\DTO\Collection\ListImages;
use AstrobinWs\Response\DTO\Item\Image;

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
            $entity->image = ($image instanceof Image) ? $image : $imageId;

        } elseif (property_exists($entity, 'images') && 0 < count($entity->images)) {
            $listImages = new ListImages();
            foreach ($entity->images as $imageUri) {
                $imageId = substr((string) $imageUri, strrpos((string)$imageUri, '/') + 1);
                $astrobinImage = $this->getWsImage($imageId);
                if ($astrobinImage instanceof AstrobinResponse) {
                    $listImages->add($astrobinImage);
                }
            }

            $entity->images = $listImages;
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

    /**
     * @throws \JsonException
     */
    protected function sendRequestAndBuildResponse(?string $id, ?array $params): ?AstrobinResponse
    {
        try {
            $response = $this->get($id, $params);
            if (is_null($response)) {
                return new AstrobinError(WsException::ERR_EMPTY);
            }

            $AstrobinResponse = $this->buildResponse($response);
        } catch (WsException | JsonException $e) {
            $AstrobinResponse = new AstrobinError($e->getMessage());
        }

        return $AstrobinResponse;
    }
}
