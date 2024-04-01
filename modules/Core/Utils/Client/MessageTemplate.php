<?php

namespace Modules\Core\Utils\Client;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response as ResponseSymfony;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MessageTemplate
{
    /**
     * Indicates whether the response message should be translated.
     *
     * @var bool
     */
    static public bool $translate = true;

    /**
     * Return message template for a general json structure.
     *
     * @param bool|array|Collection $data
     * @param string $message
     * @param array $meta
     * @param int $status
     * @return JsonResponse
     */
    public static function json(
        mixed  $data = [],
        string $message = '',
        array  $meta = [],
        int    $status = ResponseSymfony::HTTP_OK
    ): JsonResponse
    {
        if ($data instanceof Collection) {
            $data = $data->toArray();
        }

        if ($data instanceof LengthAwarePaginator) {
            $data = $data->toArray();
            $meta = [
                'total' => $data['total'],
                'per_page' => $data['per_page'],
                'from' => $data['from'],
                'to' => $data['to'],
                'current_page' => $data['current_page'],
                'last_page' => $data['last_page'],
            ];
            $data = $data['data'];
        }

        return Response::json([
            'message' => static::$translate ? __($message) : $message,
            'data' => $data,
            'meta' => $meta
        ], $status);
    }

    /**
     * Return message template in a paginated structure.
     *
     * @param bool|array|Collection $data
     * @param string $message
     * @param array $meta
     * @param int $status
     * @return JsonResponse
     */
    public static function paginate(
        mixed  $data = [],
        string $message = '',
        array  $meta = [],
        int    $status = ResponseSymfony::HTTP_OK
    ): JsonResponse
    {
        if (!$data instanceof LengthAwarePaginator) {
            $data = new LengthAwarePaginator($data, count($data), config('core.filter.paginate_per_page'));
        }

        return static::json($data, $message, $meta, $status);
    }

    /**
     * Return message template for boolean structure.
     *
     * @param bool|array|Collection $data
     * @param string $message
     * @param array $meta
     * @param int $status
     * @return JsonResponse
     */
    public static function bool(
        mixed  $data = [],
        string $message = '',
        array  $meta = [],
        int    $status = ResponseSymfony::HTTP_OK
    ): JsonResponse
    {
        return static::json(['result' => $data], $message, $meta, $status);
    }

    /**
     * Return message template for info structure.
     *
     * @param bool|array|Collection $data
     * @param string $message
     * @param array $meta
     * @param int $status
     * @return JsonResponse
     */
    public static function info(
        mixed  $data = [],
        string $message = '',
        array  $meta = [],
        int    $status = ResponseSymfony::HTTP_OK
    ): JsonResponse
    {
        return static::json($data, $message, $meta, $status);
    }

    /**
     * Return message template for success structure.
     *
     * @param bool|array|Collection $data
     * @param string $message
     * @param array $meta
     * @param int $status
     * @return JsonResponse
     */
    public static function success(
        mixed  $data = [],
        string $message = '',
        array  $meta = [],
        int    $status = ResponseSymfony::HTTP_OK
    ): JsonResponse
    {
        return static::json($data, $message, $meta, $status);
    }

    /**
     * Return message template for success structure.
     *
     * @param bool|array|Collection $data
     * @param string $message
     * @param array $meta
     * @param int $status
     * @return JsonResponse
     */
    public static function created(
        mixed  $data = [],
        string $message = '',
        array  $meta = [],
        int    $status = ResponseSymfony::HTTP_CREATED
    ): JsonResponse
    {
        return static::json($data, $message, $meta, $status);
    }

    /**
     * Return message template for bad-request structure.
     *
     * @param bool|array|Collection $data
     * @param string $message
     * @param array $meta
     * @param int $status
     * @return JsonResponse
     */
    public static function badRequest(
        mixed  $data = [],
        string $message = '',
        array  $meta = [],
        int    $status = ResponseSymfony::HTTP_BAD_REQUEST
    ): JsonResponse
    {
        return static::json($data, $message, $meta, $status);
    }

    /**
     * Return message template for bad-request structure.
     *
     * @param bool|array|Collection $data
     * @param string $message
     * @param array $meta
     * @param int $status
     * @return JsonResponse
     */
    public static function serverError(
        mixed  $data = [],
        string $message = '',
        array  $meta = [],
        int    $status = ResponseSymfony::HTTP_INTERNAL_SERVER_ERROR
    ): JsonResponse
    {
        return static::json($data, $message, $meta, $status);
    }

    /**
     * Return message template for not-found structure.
     *
     * @param bool|array|Collection $data
     * @param string $message
     * @param array $meta
     * @param int $status
     * @return JsonResponse
     */
    public static function notFound(
        mixed  $data = [],
        string $message = 'Not found',
        array  $meta = [],
        int    $status = ResponseSymfony::HTTP_NOT_FOUND
    ): JsonResponse
    {
        return static::json($data, $message, $meta, $status);
    }

    /**
     * Return a downloadable file.
     *
     * @param string|null $path
     * @param array $headers
     * @return JsonResponse|StreamedResponse
     */
    public static function file(
        string|null $path,
        array  $headers = []
    ): StreamedResponse|JsonResponse
    {
        if (!Storage::disk('local')->exists((string)$path) || !$path) {
            return static::notFound();
        }

        return Storage::disk('local')->download($path, headers: $headers);
    }
}
