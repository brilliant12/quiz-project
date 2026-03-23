<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CaptchaController;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\QuizController;
use App\Models\User;
use Illuminate\Support\Facades\Redis;
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
use App\Http\Controllers\UserController;

Route::get('/users', [UserController::class, 'index']);
Route::get('/', function () {
    return view('welcome');
});

Route::get('gallery',[GalleryController::class,'viewd']);
Route::post('save-gallery',[GalleryController::class,'store'])->name('galleries.store');
Route::get('/galleries/fetch', [GalleryController::class, 'fetchGalleries'])->name('galleries.fetch');
Route::post('delete-gellery',[GalleryController::class,'delete']);
Route::post('update-status',[GalleryController::class,'update_status']);










Route::get('/captcha', [CaptchaController::class, 'generateCaptcha']);
Route::post('/your-form-handler', [CaptchaController::class, 'handleForm']);
Route::get('/captcha-form', function () {
    return view('captcha');
});
Route::match(['GET','POST'],'login',[AuthController::class,'login']);




Route::get('/form', [ExcelController::class, 'showForm']);
Route::post('/save-to-excel', [ExcelController::class, 'saveToExcel'])->name('saveExcel');

Route::get('login/google', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('login/google/callback', function () {
    $googleUser = Socialite::driver('google')->user();

    // Find or create user
    $authUser = User::where('google_id', $googleUser->id)->first();
    
    if (!$authUser) {
        $authUser = User::create([
            'name' => $googleUser->name,
            'email' => $googleUser->email,
            'google_id' => $googleUser->id,
            // Add other fields as necessary
        ]);
    }

    Auth::login($authUser, true);

    return redirect()->to('/home'); // Change this to your desired route
});

Route::get('math-quiz',[QuizController::class,'quiz']);
Route::get('get-quiz',[QuizController::class,'Getquiz']);
Route::get('chage-quizLabel',[QuizController::class,'QuizLabel']);
Route::get('skip-quiz',[QuizController::class,'skipQuiz']);

Route::get('redis',function(){

// Set a value
Redis::set('name', 'ChatGPT');

// Get a value
$name = Redis::get('name');
echo $name;


});



// Route::get('/users', function () {
    
//     $cachedUsers = Redis::get('users_5000');

//     if ($cachedUsers) {
//         $users = json_decode($cachedUsers, true); // decode cached JSON
//     } else {
//         // Fetch from database if not cached
//         $users = User::limit(5000)->get();
//         Redis::set('users_5000', $users->toJson()); // store in Redis
//     }

//     // Return as JSON (or you can loop and echo)
//     return ($users);
// });