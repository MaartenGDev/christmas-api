<?php

namespace App\Http\Controllers\Api;

use App\Gift;
use App\Http\Controllers\ApiController;
use App\Http\Requests\StoreGiftReservationRequest;
use App\Http\Resources\GiftResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
