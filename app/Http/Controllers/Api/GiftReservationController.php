<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreGiftReservationRequest;
use App\Http\Resources\GiftResource;
use App\Models\Gift;

class GiftReservationController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        return $this->respondOk(GiftResource::collection(Gift::with('user')->get()));
    }

    public function patch(Gift $gift, StoreGiftReservationRequest $request)
    {
        $this->authorize('updateReservation', $gift);

        $gift->reserved_by = $request->reserved_by === -1 ? null : $request->user()->id;
        $gift->save();

        $gift->user = $gift->user()->first();

        return $this->respondOk(new GiftResource($gift));
    }
}
