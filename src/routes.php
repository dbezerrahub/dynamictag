<?php
Route::get('/dtag/wysiwyg/gif', 'Dob\DTag\DTagController@getWysiwygGif');
Route::get('/dtag/load/gif', ['as' => 'dtag.gif', 'uses' => 'Dob\DTag\DTagController@getLoadGif']);
Route::get('/dtag/js', 'Dob\DTag\DTagController@getJs');