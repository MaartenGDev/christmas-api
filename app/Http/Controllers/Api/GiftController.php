<?php

namespace App\Http\Controllers\Api;

use App\Gift;
use App\Http\Requests\StoreGiftRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class GiftController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        return Gift::all();
    }

    public function store(StoreGiftRequest $request){
       return $request->user()->gifts()->create($request->all());
    }
}
