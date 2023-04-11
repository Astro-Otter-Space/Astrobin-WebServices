<?php

declare(strict_types=1);

namespace AstrobinWs\Services;

use AstrobinWs\AbstractWebService;
use AstrobinWs\Exceptions\WsException;
use AstrobinWs\Exceptions\WsResponseException;
use AstrobinWs\Filters\ImageFilters;
use AstrobinWs\Filters\QueryFilters;
use AstrobinWs\Response\DTO\AstrobinError;
use AstrobinWs\Response\DTO\AstrobinResponse;
use AstrobinWs\Response\DTO\Image;
use AstrobinWs\Response\DTO\ListImages;
use AstrobinWs\Response\EntityFactory;
use DateTime;
use JsonException;
use MongoDB\Driver\Query;
use ReflectionException;

/**
 * Class GetImage
 *
 * @package Astrobin\Services
 */
class GetImage extends AbstractWebService implements WsInterface
{
    final public const END_POINT = 'image';

    protected function getEndPoint(): string
    {
        return self::END_POINT;
    }

    protected function getObjectEntity(): string
    {
        return Image::class;
    }

    protected function getCollectionEntity(): string
    {
        return ListImages::class;
    }

    /**
     * @deprecated
     *
     * Only for retro-compatibility with version 1.x
     * @throws WsException
     * @throws WsResponseException|JsonException
     */
    public function getImageById(string $id): ?AstrobinResponse
    {
        return $this->getById($id);
    }

    /**
     * Id of image can be a string or an int
     * @throws WsException
     * @throws WsResponseException|JsonException
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
     */
    public function getImagesBySubject(string $subjectId, int $limit): ?AstrobinResponse
    {
        if (parent::LIMIT_MAX < $limit) {
            return null;
        }

        $params = [ImageFilters::SUBJECTS_FILTER->value => $subjectId, QueryFilters::LIMIT->value => $limit];
        return $this->sendRequestAndBuildResponse($params);
    }

    /**
     * Get image|collection filtered by title term
     */
    public function getImagesByTitle(string $title, int $limit): ?AstrobinResponse
    {
        if (parent::LIMIT_MAX < $limit) {
            return null;
        }

        $params = [
            ImageFilters::TITLE_CONTAINS_FILTER->value => urlencode($title),
            QueryFilters::LIMIT->value => $limit
        ];
        return $this->sendRequestAndBuildResponse($params);
    }

    /**
     * Get image|collection filtered by description term
     * @throws JsonException
     * @throws ReflectionException
     */
    public function getImagesByDescription(string $description, int $limit): ?AstrobinResponse
    {
        if (parent::LIMIT_MAX < $limit) {
            return null;
        }

        $params = [
            ImageFilters::DESC_CONTAINS_FILTER->value => urlencode($description),
            QueryFilters::LIMIT->value => $limit
        ];
        return $this->sendRequestAndBuildResponse($params);
    }

    /**
     * Return a Collection per username
     */
    public function getImagesByUser(string $userName, int $limit): ?AstrobinResponse
    {
        if (parent::LIMIT_MAX < $limit) {
            return null;
        }

        $params = [
            ImageFilters::USER_FILTER->value => $userName,
            QueryFilters::LIMIT->value => $limit
        ];
        return $this->sendRequestAndBuildResponse($params);
    }


    /**
     * Get image filtered by range date
     * @throws WsException
     * @throws WsResponseException
     * @throws ReflectionException
     * @throws \Exception
     */
    public function getImagesByRangeDate(?string $dateFromStr, ?string $dateToStr): ?AstrobinResponse
    {
        if (false === strtotime($dateFromStr)) {
            throw new WsResponseException(sprintf(WsException::ERR_DATE_FORMAT, $dateFromStr), 500, null);
        }

        /** @var \DateTimeInterface $dateFrom */
        $dateFrom = DateTime::createFromFormat(QueryFilters::DATE_FORMAT->value, $dateFromStr);
        if ($dateFromStr !== $dateFrom->format(QueryFilters::DATE_FORMAT->value)) {
            throw new WsException(sprintf(WsException::ERR_DATE_FORMAT, $dateFromStr), 500, null);
        }

        if (array_sum($dateFrom->getLastErrors())) {
            throw new WsException(WsException::ERR_DATE . print_r($dateFrom->getLastErrors(), true), 500, null);
        }

        $dateTo = is_null($dateToStr) ? new DateTime('now') : new DateTime($dateToStr);

        $params = [
            'uploaded__gte' => urlencode($dateFrom->format('Y-m-d 00:00:00')),
            'uploaded__lt' => urlencode($dateTo->format('Y-m-d H:i:s')),
            QueryFilters::LIMIT->value => AbstractWebService::LIMIT_MAX
        ];

        return $this->sendRequestAndBuildResponse($params);
    }

    /**
     * Get image/
     * @throws JsonException
     * @throws ReflectionException
     */
    public function getImageBy(array $filters, int $limit): ?AstrobinResponse
    {
        if (parent::LIMIT_MAX < $limit) {
            return null;
        }

        $params = array_filter($filters, static fn($key) => in_array($key, array_column(ImageFilters::cases(), 'value'), true), ARRAY_FILTER_USE_KEY);
        $params = array_merge($params, [QueryFilters::LIMIT->value => $limit]);

        return $this->sendRequestAndBuildResponse($params);
    }

    private function sendRequestAndBuildResponse(array $params): ?AstrobinResponse
    {
        try {
            $response = $this->get(null, $params);
            if (is_null($response)) {
                return new AstrobinError(WsException::ERR_EMPTY);
            }
        } catch (WsException | JsonException $e) {
            return new AstrobinError($e->getMessage());
        }

        try {
            $AstrobinResponse = $this->buildResponse($response);
        } catch (JsonException $e) {
            $AstrobinResponse =  new AstrobinError($e->getMessage());
        }

        return $AstrobinResponse;
    }
}
