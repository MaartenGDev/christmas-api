<?php

namespace App\Services;


use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslateService
{
    protected $client;

    public function __construct()
    {
        $this->client = new GoogleTranslate();
    }

    public function translate($text, $language = 'en')
    {
        $this->client->setSource("nl");
        $this->client->setTarget($language);

        return $this->client->translate($text);
    }
}
