<?php

namespace AstrobinWs\Response;

use AstrobinWs\Response\DTO\AstrobinResponse;

final class EntityFactory
{
    private mixed $rawResponse;

    private ?string $entity;
    private ?string $collectionEntity;

    /**
     * @throws \JsonException
     */
    public function __construct(public string $guzzleResponse)
    {
        $this->rawResponse = json_decode($this->guzzleResponse,false, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @param string|null $entity
     * @return EntityFactory
     */
    public function setEntity(?string $entity): self
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @param string|null $collectionEntity
     * @return EntityFactory
     */
    public function setCollectionEntity(?string $collectionEntity): self
    {
        $this->collectionEntity = $collectionEntity;
        return $this;
    }

    /**
     * @TODO : use Symfony serializer component
     * @return AstrobinResponse
     */
    public function buildResponse(): AstrobinResponse
    {
        if (property_exists($this->rawResponse, "objects") && 0 < (is_countable($this->rawResponse->objects) ? count($this->rawResponse->objects) : 0)) {
            $listObjects = $this->rawResponse->objects;
            if (1 < (is_countable($listObjects) ? count($listObjects) : 0)) {
                /** @var AstrobinResponse $astrobinResponse */
                $astrobinResponse = new $this->collectionEntity;
                foreach ($listObjects as $object) {
                    $entity = new $this->entity;
                    $entity->fromObj($object);
                    $astrobinResponse->add($entity);
                }
            } else {
                /** @var AstrobinResponse $astrobinResponse */
                $astrobinResponse = new $this->entity;
                $astrobinResponse->fromObj(reset($listObjects));
            }
        } else {
            $astrobinResponse = new $this->entity;
            $astrobinResponse->fromObj($this->rawResponse);
        }

        return $astrobinResponse;
    }
}