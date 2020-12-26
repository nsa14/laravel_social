<?php


//composer create-project --prefer-dist laravel/laravel social_counter
//composer require barryvdh/laravel-debugbar --dev
//composer require hekmatinasser/verta

//php artisan make:controller AdminController
//php artisan migrate:make create_users_table ––create=users

//download and install nodejs in windows
//composer require laravel/ui
//php artisan ui:auth
//php artisan ui bootstrap --auth
//npm intsall
//npm run dev

//php artisan storage:link


//php artisan make:model Instagram -m


//php artisan schedule:run



use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () { 

    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/', 'AdminController@index')->name('index');
    Route::get('/', [
        'as' => 'index', 
        'uses' => 'AdminController@index'
    ])->middleware('auth');
    
    Route::get('/instagram-list', [
        'as' => 'instagram-list', 
        'uses' => 'AdminController@instagramList'
    ]);
    
    Route::get('/instagram-add', [
        'as' => 'instagram-add', 
        'uses' => 'AdminController@instagramAdd'
    ]);
    
    Route::post('/instagram-insert', [
        'as' => 'instagram-insert', 
        'uses' => 'AdminController@instagramInsert'
    ]);
    
    Route::get('/instagram-updating', [
        'as' => 'instagram-updating', 
        'uses' => 'AdminController@instagramUpdating'
    ]);
    
    //original is post method
    Route::post('/instagram-updatingProcess', [
        'as' => 'instagram-updatingProcess', 
        'uses' => 'AdminController@instagramUpdatingProcess'
    ]);

    Route::get('/instagram-profile/{id}', [
        'as' => 'instagram-profile', 
        'uses' => 'AdminController@instagramProfile'
    ]);


    Route::get('/likee', [
        'as' => 'likee', 
        'uses' => 'AdminController@likee'
    ]);

    Route::get('/likee-list', [
        'as' => 'likee-list', 
        'uses' => 'AdminController@likeeList'
    ]);

    Route::get('/likee-insert-show', [
        'as' => 'likee-insert-show', 
        'uses' => 'AdminController@likeeInsertShow'
    ]);

    Route::post('/likee-insert', [
        'as' => 'likee-insert', 
        'uses' => 'AdminController@likeeInsert'
    ]);


    
    Route::get('/alexa-check-show', [
        'as' => 'alexa-check-show', 
        'uses' => 'AdminController@alexaCheckShow'
    ]);

    Route::post('/alexa-insert', [
        'as' => 'alexa-insert', 
        'uses' => 'AdminController@alexaInsert'
    ]);

    Route::get('/domain-list', [
        'as' => 'domain-list', 
        'uses' => 'AdminController@domainList'
    ]);    


    Route::get('/authority', [
        'as' => 'authority', 
        'uses' => 'AdminController@authority'
    ]);
    Route::post('/authority-checker', [
        'as' => 'authority-checker', 
        'uses' => 'AdminController@authorityChecker'
    ]);

    Route::get('/moz-authority', [
        'as' => 'moz-authority', 
        'uses' => 'AdminController@mozAuthority'
    ]); 


    Route::get('/sapp-insert-show', [
        'as' => 'sapp-insert-show', 
        'uses' => 'AdminController@sappInsertShow'
    ]);

    Route::post('/sapp-get-insert', [
        'as' => 'sapp-get-insert', 
        'uses' => 'AdminController@sapp_get_insert'
    ]); 





    //==============
    Route::get('/test-javad', [
        'as' => 'test-javad', 
        'uses' => 'AdminController@testJavad'
    ]); 

 //============== test sorosh get channel count member
 Route::get('/test-sapp', [
    'as' => 'test-sapp', 
    'uses' => 'AdminController@get_sapp_data'
]); 
    




    //-----------------  instagram oreizi
    Route::get('/instagram-oreizi', [
        'as' => 'instagram-oreizi', 
        'uses' => 'AdminController@instagramOreizi'
    ]); 

    Route::get('/instagramOreiziAutomaticly', [
        'as' => 'instagramOreiziAutomaticly', 
        'uses' => 'AdminController@instagramOreiziAutomaticly'
    ]); 
    




    Route::get('/al', [
        'as' => 'al', 
        'uses' => 'AdminController@alexaCheckBatchWithSchedule'
    ]); 


    Route::get('/robot_authorityCheckSchedule', [
        'as' => 'robot_authorityCheckSchedule', 
        'uses' => 'AdminController@robot_authorityCheckSchedule'
    ]); 

    Route::get('/test-curl-raw', [
        'as' => 'test-curl-raw', 
        'uses' => 'AdminController@testCurlRaw'
    ]);
    
    
    
    

});



