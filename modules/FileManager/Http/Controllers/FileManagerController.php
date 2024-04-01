<?php

namespace Modules\FileManager\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Modules\Core\Utils\Client\MessageTemplate;
use Modules\FileManager\Http\Requests\StoreFileRequest;
use Modules\FileManager\Http\Resources\FileResource;
use Modules\FileManager\Models\File;
use Modules\FileManager\Services\FileService;
use Symfony\Component\HttpFoundation\Response as ResponseSymfony;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileManagerController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(File::class, 'file');
    }

    /**
     * Get the list of resource methods which do not have model parameters.
     *
     * @return array
     */
    protected function resourceMethodsWithoutModels(): array
    {
        return ['store', 'show'];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreFileRequest $request
     * @return JsonResponse
     */
    public function store(StoreFileRequest $request): JsonResponse
    {
        $fileService = new FileService();

        $payload = $request->validated();

        $file = $fileService->storeFile($payload['file'], Arr::except($payload, 'file'));

        if ($file) {
            return MessageTemplate::created(new FileResource($file), 'core::common.store.success');
        }

        return MessageTemplate::serverError($file, 'core::common.store.failed');
    }

    /**
     * Show the specified resource.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $fileService = new FileService();
        $file = $fileService->show($id);
        $url = $fileService->tempUrl($file);

        return MessageTemplate::json([
            'url' => $url
        ], 'core::common.show.success');
    }

    /**
     * Show the specified resource.
     *
     * @param string $slug
     * @return JsonResponse
     */
    public function showBySlug(string $slug): JsonResponse
    {
        $fileService = new FileService();
        $file = $fileService->showBySlug($slug);
        $url = $fileService->tempUrl($file);

        return MessageTemplate::json([
            'url' => $url
        ], 'core::common.show.success');
    }

    /**
     * @param Request $request
     * @param string $scope
     * @return JsonResponse
     */
    public function getByScope(Request $request, string $scope): JsonResponse
    {
        $fileService = new FileService();
        $result = $fileService->getByScope($scope, $request->all());

        return MessageTemplate::json($result, 'core::common.show.success');
    }

    /**
     * Show the specified file with temporary URL.
     *
     * @param string $id
     * @return StreamedResponse|JsonResponse
     */
    public function showTemp(string $id): StreamedResponse|JsonResponse
    {
        $fileService = new FileService();
        $filePath = $fileService->showAsTempUrl($id);

        return MessageTemplate::file($filePath);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param File $file
     * @return JsonResponse
     */
    public function destroy(File $file): JsonResponse
    {
        $fileService = new FileService();

        $result = $fileService->destroy($file);

        if ($result) {
            return MessageTemplate::bool(true, 'core::common.destroy.success');
        }

        return MessageTemplate::bool(false, 'core::common.destroy.failed', status: ResponseSymfony::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $slug
     * @return JsonResponse
     */
    public function destroyBySlug(string $slug): JsonResponse
    {
        $fileService = new FileService();

        $result = $fileService->destroyBySlug($slug);

        if ($result) {
            return MessageTemplate::bool(true, 'core::common.destroy.success');
        }

        return MessageTemplate::bool(false, 'core::common.destroy.failed', status: ResponseSymfony::HTTP_INTERNAL_SERVER_ERROR);
    }
}
