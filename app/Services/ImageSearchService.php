<?php

namespace App\Services;


use GuzzleHttp\Client;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Monolog\Logger;
use Ramsey\Uuid\Uuid;

class ImageSearchService
{
    /**
     * @var TranslateService
     */
    private $translateService;
    /**
     * @var Client
     */
    private $httpClient;

    public function __construct(TranslateService $translateService)
    {
        $this->translateService = $translateService;
        $this->httpClient = new Client([
            'headers' => [
                'Accept'     => 'application/json',
                'Authorization' => 'Client-ID ' . config('unsplash.application_token')
            ]
        ]);
    }

    public function search($text)
    {
        $englishText = $this->translateService->translate($text);
        $endpoint = config('unsplash.endpoint') . '/search/photos?orientation=landscape&query=' . $englishText;

        $imagesSortedByLiked = collect(json_decode($this->httpClient->request('GET', $endpoint)
            ->getBody())->results);

        return $imagesSortedByLiked->first();
    }

    public function searchAndPersist($text, $previousFilename = null)
    {
        $imageDetails = $this->search($text);
        if(is_null($imageDetails)) return null;

        $imageBlob = file_get_contents($imageDetails->links->download);

        $downloadedImage = Image::make($imageBlob);
        $downloadedImage->resize(1080, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        $downloadedImage->stream();

        $this->deleteIfExists($previousFilename);

        $filename = 'gift-images/' . Uuid::uuid4()->toString() . '.jpg';

        Storage::disk('s3')->put($filename, $downloadedImage);

        return Storage::url($filename);
    }

    private function deleteIfExists($filename){
        if(is_null($filename)) return;

        $filenameWithoutStoragePrefix = $this->removeStoragePrefix($filename);

        Storage::disk('s3')->delete($filenameWithoutStoragePrefix);
    }

    private function removeStoragePrefix($filename){
        $storagePrefix = 'storage/';
        if(substr($filename, 0, strlen($storagePrefix)) !== $storagePrefix) return $filename;

        return substr($filename, strlen($storagePrefix));
    }
}