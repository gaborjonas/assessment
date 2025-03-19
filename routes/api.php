<?php

use App\Http\Actions\DecodeAction;
use App\Http\Actions\EncodeAction;
use Illuminate\Support\Facades\Route;

Route::post('/encode', EncodeAction::class);
Route::post('/decode', DecodeAction::class);
