<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreGiftRequest;
use App\Http\Resources\GiftResource;
use App\Jobs\AddImageToGift;
use App\Models\Gift;
use App\Services\ImageSearchService;
use Illuminate\Http\Request;

class UserGiftController extends ApiController
{
    /**
     * @var ImageSearchService
     */
    private $imageSearchService;

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
        $user = $request->user();

        $gift = $user->gifts()->create($request->except(['reserved_by']));
        $gift->user = $user;

        AddImageToGift::dispatch($gift);

        $gift->refresh();

        return $this->respondOk(new GiftResource($gift));
    }

    public function update(StoreGiftRequest $request, Gift $gift)
    {
        $this->authorize('update', $gift);

        $oldTitle = $gift->title;
        $gift->update($request->except(['reserved_by']));

        if ($request->title !== $oldTitle) {
            AddImageToGift::dispatch($gift);
        }

        return $this->respondOk(new GiftResource($gift));
    }

    public function destroy(Gift $gift)
    {
        $this->authorize('destroy', $gift);

        $gift->delete();

        return $this->respondOk(['The gift has been deleted']);
    }
}
