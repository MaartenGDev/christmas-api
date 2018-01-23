<?php

namespace App\Http\Controllers\Api;

use App\Gift;
use App\Http\Controllers\ApiController;
use App\Http\Requests\StoreGiftRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class GiftController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request){
        return $this->respondOk($request->user()->gifts()->get());
    }

    public function store(StoreGiftRequest $request){
       return $this->respondOk($request->user()->gifts()->create($request->all()));
    }

    public function update(StoreGiftRequest $request, Gift $gift)
    {
        $gift->update($request->all());

        return $this->respondOk($gift);
    }
}
