<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    CategoryController
};

Route::get('/', function () {
    return response()->json(['message' => 'success']);
});

Route::apiResource('categories', CategoryController::class);
