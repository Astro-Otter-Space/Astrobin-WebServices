<?php

namespace AstrobinWs\Services;

use AstrobinWs\AbstractWebService;
use AstrobinWs\Exceptions\WsException;
use AstrobinWs\Exceptions\WsResponseException;
use AstrobinWs\Filters\QueryFilters;
use AstrobinWs\Response\DTO\AstrobinResponse;
use AstrobinWs\Response\DTO\ListToday;
use AstrobinWs\Response\DTO\Today;

/**
 * Class getTodayImage
 * @package AppBundle\Astrobin\Services
 */
class GetTodayImage extends AbstractWebService implements WsInterface
{
    use WsAstrobinTrait;

    final public const END_POINT = 'imageoftheday';

    protected function getEndPoint(): string
    {
        return self::END_POINT;
    }

    protected function getObjectEntity(): string
    {
        return Today::class;
    }


    protected function getCollectionEntity(): ?string
    {
        return ListToday::class;
    }

    /**
     * Method not allowed
     */
    public function getById(?string $id): ?AstrobinResponse
    {
        return null;
    }

    /**
     * Get image of today
     * @throws WsException
     * @throws WsResponseException
     * @throws \ReflectionException
     * @throws \JsonException
     */
    public function getTodayImage(): ?AstrobinResponse
    {
        return $this->getDayImage(0, 1);
    }

    /**
     * Get image of specific day
     * @throws WsException
     * @throws WsResponseException
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public function getDayImage(?int $offset, ?int $limit): ?AstrobinResponse
    {
        if (is_null($offset)) {
            $offset = 0;
        }

        if (is_null($limit)) {
            $limit = 1;
        }

        $params = [
            QueryFilters::LIMIT->value => $limit,
            QueryFilters::OFFSET->value => $offset
        ];

        $entity = $this->sendRequestAndBuildResponse(null, $params);

        if ($entity instanceof Today) {
            $entity = $this->getImagesFromResource($entity);
        } elseif ($entity instanceof ListToday) {
            /** @var Today $day */
            foreach ($entity->listToday as $day) {
                $this->getImagesFromResource($day);
            }
        }

        return $entity;
    }
}
