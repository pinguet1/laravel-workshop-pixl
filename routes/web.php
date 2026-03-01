<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    return view('welcome');
});

Route::get('/dev/login', function() {
    //$user = User::inRandomOrder()->first();
    $user = User::first('id', 5);

    Auth::login($user);
    request()->session()->regenerate();

    return redirect()->route('profile.show', $user->profile);
})->name('login');

Route::get('dev/logout', function(){
   Auth::logout();
   request()->session()->invalidate();
   request()->session()->regenerate();

   return redirect()->intended('/feed');
});

Route::middleware(['auth'])->group(function(){
    Route::get('/home', [PostController::class, 'index'])->name('post.index');
});

Route::get('/feed', function(){

    $feedItems = json_decode(json_encode([
        [
            'postedDateTime'=> '3h',
            'content' => <<<str
                <p>
                    I made this! <a href="#">#myartwork</a> <a href="#">#pixl</a>
                </p>
                <img src="/images/simon-chilling.png" alt="" />
            str,

            'likeCount' => 23,
            'replyCount' => 45,
            'repostCount' => 141,

            'profile'=>[
                'avatar'=> '/images/michael.png',
                'displayName'=>'Michael',
                'handle'=>'@michjj'

            ],
            'replies' => [
                [
                    'postedDateTime'=> '1h',
                    'content' => '<p>Heh —this looks just like me!</p>',
                    'likeCount' => 52,
                    'replyCount' => 12,
                    'repostCount' => 200,

                    'profile'=>[
                        'avatar'=> '/images/simon-chilling.png',
                        'displayName'=>'Simon',
                        'handle'=>'@simonswiss'

                    ],
                ]
            ]
        ]
    ]));


    return view('feed', compact('feedItems'));
});
Route::get('/profile', function(){


    $feedItems = json_decode(json_encode([
        [
            'postedDateTime'=> '3h',
            'content' => <<<str
                <p>
                    I made this! <a href="#">#myartwork</a> <a href="#">#pixl</a>
                </p>
                <img src="/images/simon-chilling.png" alt="" />
            str,

            'likeCount' => 23,
            'replyCount' => 45,
            'repostCount' => 141,

            'profile'=>[
                'avatar'=> '/images/michael.png',
                'displayName'=>'Michael',
                'handle'=>'@michjj'

            ],
            'replies' => [
                [
                    'postedDateTime'=> '1h',
                    'content' => '<p>Heh —this looks just like me!</p>',
                    'likeCount' => 52,
                    'replyCount' => 12,
                    'repostCount' => 200,

                    'profile'=>[
                        'avatar'=> '/images/simon-chilling.png',
                        'displayName'=>'Simon',
                        'handle'=>'@simonswiss'

                    ],
            ]
        ]
    ]]));

    return view('profile', compact('feedItems'));
});

Route::get('/{profile:handle}', [ProfileController::class, 'show'])
    ->name('profile.show');

Route::get('/{profile:handle}/with_replies',[ProfileController::class, 'replies'])
    ->name('profile.replies');

Route::scopeBindings()->group(function(){
    Route::get('/{profile:handle}/status/{post}', [PostController::class, 'show'])->name('posts.show');
});
