<?php
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\API\AuthController;

Route::get('auth', [AuthController::class, 'redirectToAuth']);
Route::get('auth/callback', [AuthController::class, 'handleAuthCallback']);

// login and register
// Route::post('register', [AuthController::class,'Register']);
// Route::post('login', [AuthController::class,'login']);

// Route::group(['middleware' => ['auth:sanctum', 'throttle:none']],  function () {
//     // testing routes below
//     Route::get('check', [AuthController::class,'check']);
//     //logout
//     Route::post('/logout', [AuthController::class, 'logout']);
// });

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });













// // Route::get('auth', function () {
//     // return Socialite::driver('github')->redirect();
// // });
// // Route::get('auth/callback', function () {
//     $githubUser = Socialite::driver('github')->user();
 
//     $user = User::where('github_id', $githubUser->id)->first();
 
//     if ($user) {
//         $user->update([
//             'github_token' => $githubUser->token,
//             'github_refresh_token' => $githubUser->refreshToken,
//         ]);
//     } else {
//         $user = User::create([
//             'name' => $githubUser->name,
//             'email' => $githubUser->email,
//             'github_id' => $githubUser->id,
//             'github_token' => $githubUser->token,
//             'github_refresh_token' => $githubUser->refreshToken,
//         ]);

//         return response()->json([
//             'user' => $user,
//             'access_token' => $user->createToken('github-token')->plainTextToken,
//             'token_type' => 'Bearer',
//         ]);
//     }
 
//     // Auth::login($user);
 
//     // return redirect('/dashboard');
// // });
