<?php

namespace App\Services;


use Stichoza\GoogleTranslate\TranslateClient;

class TranslateService
{
    protected $client;

    public function __construct()
    {
        $this->client = new TranslateClient();
    }

    public function translate($text, $language = 'en')
    {
        $this->client->setTarget($language);

        return $this->client->translate($text);
    }
}