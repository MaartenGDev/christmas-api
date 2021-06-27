<?php

namespace App\Jobs;

use App\Models\Gift;
use App\Services\ImageSearchService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AddImageToGift implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $gift;

    public function __construct(Gift $gift)
    {
        $this->gift = $gift;
    }


    public function handle(ImageSearchService $imageSearchService)
    {
        $this->gift->update([
            'image' =>  $imageSearchService->searchAndPersist($this->gift->title)
        ]);
    }
}
