<?php

declare(strict_types=1);

namespace AstrobinWs\Services;

use AstrobinWs\AbstractWebService;
use AstrobinWs\Exceptions\WsException;
use AstrobinWs\Exceptions\WsResponseException;
use AstrobinWs\Filters\AbstractFilters;
use AstrobinWs\Filters\ImageFilters;
use AstrobinWs\Response\AstrobinError;
use AstrobinWs\Response\AstrobinResponse;
use AstrobinWs\Response\Image;
use AstrobinWs\Response\ListImages;

/**
 * Class GetImage
 *
 * @package Astrobin\Services
 */
class GetImage extends AbstractWebService implements WsInterface
{
    public const END_POINT = 'image';

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
        return Image::class;
    }

    /**
     * @return string
     */
    protected function getCollectionEntity(): string
    {
        return ListImages::class;
    }

    /**
     * Only for retro-compatibility with version 1.x
     *
     * @param string $id
     *
     * @return AstrobinResponse|null
     * @throws WsException
     * @throws WsResponseException
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public function getImageById(string $id): ?AstrobinResponse
    {
        return $this->getById($id);
    }

    /**
     * Id of image can be a string or an int
     *
     * @param string|null $id
     *
     * @return AstrobinResponse
     * @throws WsException
     * @throws WsResponseException
     * @throws \ReflectionException
     * @throws \JsonException
     */
    public function getById(?string $id): ?AstrobinResponse
    {
        if (is_null($id)) {
            throw new WsResponseException(sprintf(WsException::EMPTY_ID, $id), 500, null);
        }

        try {
            $response = $this->get($id, null);
            $astrobinResponse = $this->buildResponse($response);
        } catch (WsException $e) {
            $astrobinResponse = new AstrobinError($e->getMessage());
        }

        return $astrobinResponse;
    }

    /**
     * Return a collection of Image() filtered by subject
     *
     * @param string $subjectId
     * @param int $limit
     *
     * @return ListImages|Image|null
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public function getImagesBySubject(string $subjectId, int $limit): ?AstrobinResponse
    {
        if (parent::LIMIT_MAX < $limit) {
            return null;
        }

        $params = [ImageFilters::SUBJECTS_FILTER => $subjectId, AbstractFilters::LIMIT => $limit];
        return $this->getAstrobinResponse($params);
    }

    /**
     * Get image|collection filtered by title term
     *
     * @param string $title
     * @param int $limit
     *
     * @return ListImages|Image|null
     * @throws WsException
     * @throws WsResponseException
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public function getImagesByTitle(string $title, int $limit): ?AstrobinResponse
    {
        if (parent::LIMIT_MAX < $limit) {
            return null;
        }

        $params = [ImageFilters::TITLE_CONTAINS_FILTER => urlencode($title), AbstractFilters::LIMIT => $limit];
        return $this->getAstrobinResponse($params);
    }


    /**
     * Get image|collection filtered by description term
     *
     * @param string $description
     * @param int $limit
     *
     * @return ListImages|Image|null
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public function getImagesByDescription(string $description, int $limit): ?AstrobinResponse
    {
        if (parent::LIMIT_MAX < $limit) {
            return null;
        }

        $params = [ImageFilters::DESC_CONTAINS_FILTER => urlencode($description), AbstractFilters::LIMIT => $limit];
        return $this->getAstrobinResponse($params);
    }


    /**
     * Return an Collection per user name
     *
     * @param string $userName
     * @param int $limit
     *
     * @return ListImages|Image|null
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public function getImagesByUser(string $userName, int $limit): ?AstrobinResponse
    {
        if (parent::LIMIT_MAX < $limit) {
            return null;
        }

        $params = [ImageFilters::USER_FILTER => $userName, AbstractFilters::LIMIT => $limit];
        return $this->getAstrobinResponse($params);
    }


    /**
     * Get image filtered by range date
     *
     * @param string|null $dateFromStr
     * @param string|null $dateToStr
     *
     * @return ListImages|Image|null
     * @throws WsException
     * @throws WsResponseException
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function getImagesByRangeDate(?string $dateFromStr, ?string $dateToStr): ?AstrobinResponse
    {
        if (is_null($dateToStr)) {
            /** @var \DateTimeInterface $dateTo */
            $dateTo = new \DateTime('now');
        } else {
            /** @var \DateTimeInterface $dateTo */
            $dateTo = new \DateTime($dateToStr);
        }

        if (false === strtotime($dateFromStr)) {
            throw new WsException(sprintf(WsException::ERR_DATE_FORMAT, $dateFromStr), 500, null);
        }

        /** @var \DateTimeInterface $dateFrom */
        $dateFrom = \DateTime::createFromFormat(AbstractFilters::DATE_FORMAT, $dateFromStr);
        if ($dateFromStr !== $dateFrom->format(AbstractFilters::DATE_FORMAT)) {
            throw new WsException(sprintf(WsException::ERR_DATE_FORMAT, $dateFromStr), 500, null);
        }

        if (array_sum($dateFrom->getLastErrors())) {
            throw new WsException(WsException::ERR_DATE . print_r($dateFrom->getLastErrors()), 500, null);
        }

        $params = [
            'uploaded__gte' => urlencode($dateFrom->format('Y-m-d 00:00:00')),
            'uploaded__lt' => urlencode($dateTo->format('Y-m-d H:i:s')),
            AbstractFilters::LIMIT => AbstractWebService::LIMIT_MAX
        ];

        return $this->getAstrobinResponse($params);
    }


    /**
     * Get image/
     * @param array $filters
     * @param int $limit
     *
     * @return AstrobinResponse|null
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public function getImageBy(array $filters, int $limit): ?AstrobinResponse
    {
        if (parent::LIMIT_MAX < $limit) {
            return null;
        }

        $params = array_filter($filters, static function ($key) {
            return true === in_array($key, array_values(ImageFilters::getFilters()), true);
        }, ARRAY_FILTER_USE_KEY);

        $params = array_merge($params, [AbstractFilters::LIMIT => $limit]);

        return $this->getAstrobinResponse($params);
    }

    /**
     * @param array $params
     *
     * @return AstrobinResponse|null
     * @throws \JsonException
     * @throws \ReflectionException
     */
    private function getAstrobinResponse(array $params): ?AstrobinResponse
    {
        try {
            $response = $this->get(null, $params);
            $AstrobinResponse = $this->buildResponse($response);
        } catch (WsException $e) {
            $AstrobinResponse =  new AstrobinError($e->getMessage());
        }

        return $AstrobinResponse;
    }
}
