<?php

use Illuminate\Support\Facades\Route;



Route::resource('chat',"ChatController");

Route::get('settings/chat', "ChatSettingsController@index")->name("settings.chat.index");

Route::post('settings/chat', "ChatSettingsController@store")->name("settings.chat.store");