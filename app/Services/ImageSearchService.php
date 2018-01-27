<?php

namespace App\Services;


use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
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

    public function __construct(Client $httpClient, TranslateService $translateService)
    {
        $this->translateService = $translateService;
        $this->httpClient = $httpClient;
    }

    public function search($text)
    {
        $englishText = $this->translateService->translate($text);
        $endpoint = config('unsplash.endpoint') . '/search/photos?orientation=landscape&query=' . $englishText;

        $imagesSortedByLiked = collect(json_decode($this->httpClient->request('GET', $endpoint, [
            'headers' => [
                'Accept'     => 'application/json',
                'Authorization' => 'Client-ID ' . config('unsplash.application_token')
            ]
        ])->getBody())->results)
            ->sortBy('likes');

        return $imagesSortedByLiked->first();
    }

    public function searchAndPersist($text, $previousFilename = null)
    {
        $imageDetails = $this->search($text);
        if(is_null($imageDetails)) return null;

        $imageBlob = file_get_contents($imageDetails->links->download);

        $filename = !is_null($previousFilename)
            ? $this->getFilenameWithStoragePrefix($previousFilename)
            : 'gift-images/' . Uuid::uuid4()->toString() . '.jpg';

        Storage::disk('public')->put($filename, $imageBlob);

        return 'storage/' . $filename;
    }

    private function getFilenameWithStoragePrefix($filename){
        $prefix = 'storage/';
        if(strpos($filename, $prefix) !== 0) return $filename;

        return substr($filename, strlen($prefix));
    }
}