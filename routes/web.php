<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Route::group(['prefix' => LaravelLocalization::setLocale()], function()
{
    /** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/
    Route::get('/', function(){
        return view('welcome');
    });

    Route::get('test',function(){
        return View::make('test');
    });

    Route::resource('papers', 'PaperController');

    Route::group(['middleware' => ['checkChannel','delegate.auth:delegate']], function()
    {
        Route::get('/delegate-papers', function (){
            $papers = auth('delegate')->user()->papers;
            return view('papers.delegate-papers', compact('papers'));
        })->name('delegate-papers');

        Route::get('/delegate-submit', function (){
            return view('papers.delegate-submit');
        })->name('delegate-submit');

        Route::get('/delegate-papers/{paperId}', function (){
//            $paper = \App\Paper::find(request()->paperId);
            $paperId = request()->paperId;
            return view('papers.delegate-submit', compact('paperId'));
        })->name('delegate-paper');
    });

});
