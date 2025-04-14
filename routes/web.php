<?php

use Illuminate\Support\Facades\Artisan;
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

// Route::get('/artisan-commands/{type}/{key}', function ($type, $key) {
//     try {
//         if($key == 'Jr6iiDR2Jd30')
//         {
//             if($type == 'migrate')
//             {
//                 Artisan::call('migrate');
//             }
//             elseif($type == 'remigrate')
//             {
//                 Artisan::call('migrate:fresh --seed');

//             }
//             elseif($type == 'cache')
//             {
//             }
//             elseif($type == 'keyGenerate')
//             {
//                 Artisan::call('key:generate');
//             }
//             elseif($type == 'passportKeyGenerate')
//             {
//                 // Artisan::call('passport:keys');
//                 Artisan::call('passport:install --force');
//                 // Artisan::call('passport:client --personal');
//             }

//             Artisan::call('cache:clear');
//             Artisan::call('config:cache');
//             Artisan::call('route:cache');
//             Artisan::call('view:clear');
//             echo 'Done';
//         }
//     } catch (\Throwable $th) {
//         echo 'Encountered Error: '.$th->getMessage();
//     }
// });
Route::get('/{any}', function () {
    return view('app');
})->where('any', '^(?!api).*$');