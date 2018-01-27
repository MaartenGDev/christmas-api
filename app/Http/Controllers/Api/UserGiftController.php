<?php

namespace App\Http\Controllers\Api;

use App\Gift;
use App\Http\Controllers\ApiController;
use App\Http\Requests\StoreGiftRequest;
use App\Http\Resources\GiftResource;
use App\Services\ImageSearchService;
use App\Services\TranslateService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserGiftController extends ApiController
{
    /**
     * @var ImageSearchService
     */
    private $imageSearchService ;

    public function __construct(ImageSearchService $imageSearchService)
    {
        $this->middleware('auth:api');
        $this->imageSearchService = $imageSearchService;
    }

    public function index(Request $request)
    {
        $giftsForUser = GiftResource::collection($request->user()->gifts()->with('user')->orderBy('updated_at', 'desc')->get());

        return $this->respondOk($giftsForUser);
    }

    public function store(StoreGiftRequest $request)
    {
        $fields = array_merge($request->except(['reserved_by']), [
           'image' => $this->imageSearchService->searchAndPersist($request->title)
        ]);

        $gift = $request->user()->gifts()->create($fields);

        $gift->user = $request->user()->first();

        return $this->respondOk(new GiftResource($gift));
    }

    public function update(StoreGiftRequest $request, Gift $gift)
    {
       $this->authorize('update', $gift);

        $fields = array_merge($request->except(['reserved_by']), [
            'image' => $this->imageSearchService->searchAndPersist($request->title, $gift->image)
        ]);

        $gift->update($fields);

        return $this->respondOk(new GiftResource($gift));
    }

    public function destroy(Gift $gift)
    {
        $this->authorize('destroy', $gift);

        $gift->delete();

        return $this->respondOk(['The gift has been deleted']);
    }
}
