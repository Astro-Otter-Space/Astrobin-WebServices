<?php

declare(strict_types=1);

namespace AstrobinWs\Services;

use AstrobinWs\Response\AstrobinResponse;
use AstrobinWs\AbstractWebService;
use AstrobinWs\Exceptions\WsException;
use AstrobinWs\Exceptions\WsResponseException;
use AstrobinWs\Response\Image;
use AstrobinWs\Response\ListImages;
use AstrobinWs\Response\ListToday;
use AstrobinWs\Response\Today;
use http\Client\Response;

/**
 * Class getTodayImage
 * @package AppBundle\Astrobin\Services
 */
class GetTodayImage extends AbstractWebService implements WsInterface
{

    private const END_POINT = 'imageoftheday';

    public const FORMAT_DATE_ASTROBIN = "Y-m-d";

    /**
     * @return string
     */
    protected function getEndPoint(): string
    {
        return self::END_POINT;
    }

    /**
     * @return string
     */
    protected function getObjectEntity(): string
    {
        return Today::class;
    }

    /**
     * @return string|null
     */
    protected function getCollectionEntity(): ?string
    {
        return ListToday::class;
    }

    /**
     * Method not allowed
     *
     * @param string|null $id
     *
     * @return AstrobinResponse|null
     */
    public function getById(?string $id): ?AstrobinResponse
    {
        return null;
    }


    /**
     * @param int|null $offset
     * @param int|null $limit
     *
     * @return AstrobinResponse|null
     * @throws WsException
     * @throws WsResponseException
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public function getDayImage(?int $offset, ?int $limit): ?AstrobinResponse
    {
        if (is_null($limit)) {
            $limit = 1;
        }
        if (is_null($offset)) {
            $offset = parent::LIMIT_MAX;
        }

        $params = [
            'limit' => $limit,
            'offset' => $offset
        ];

        $response = $this->get(null, $params);
        /** @var Today|ListToday|AstrobinResponse $today */
        $today = $this->buildResponse($response);

        if (is_null($today)) {
            throw new WsResponseException(WsException::RESP_EMPTY, 500, null);
        }

        // For Image of the day
        if (is_null($offset)) {
            $today = new \DateTime('now');
            // If it is not today, take yesterday image
            $params['offset'] = (($today->format(self::FORMAT_DATE_ASTROBIN) === $today->date)) ?: 1;
        }

        if ($today instanceof ListToday) {
            foreach ($today as $day) {
                var_dump($day->date);
                /** @var Image $imageOfDay */
                $imageOfDay = $this->getImageToToday($day);
                $day->add($imageOfDay);
            }
        } elseif ($today instanceof Today) {
            /** @var Image $imageOfDay */
            $imageOfDay = $this->getImageToToday($today);
            if (!is_null($imageOfDay)) {
                $today->add($imageOfDay);
            }
        }

        return $today;
    }

    /**
     * @param Today $today
     *
     * @return AstrobinResponse|null
     * @throws WsException
     * @throws WsResponseException
     * @throws \JsonException
     * @throws \ReflectionException
     */
    private function getImageToToday(Today $today): ?AstrobinResponse
    {
        $imageId = substr($today->image, strrpos($today->image, '/') + 1);
        $imageWs = new GetImage();
        /** @var Image|AstrobinResponse $image */
        $image = $imageWs->getById($imageId);

        if ($image instanceof AstrobinResponse) {
            return $image;
        }
        return null;
    }

    /**
     * @return Today|AstrobinResponse|null
     * @throws WsException
     * @throws WsResponseException
     * @throws \ReflectionException
     * @throws \JsonException
     */
    public function getTodayImage(): ?AstrobinResponse
    {
        return $this->getDayImage(0, 1);
    }
}
