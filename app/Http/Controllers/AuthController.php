<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Routing\Pipeline;
use Laravel\Fortify\Actions\AttemptToAuthenticate;
use Laravel\Fortify\Actions\EnsureLoginIsNotThrottled;
use Laravel\Fortify\Actions\PrepareAuthenticatedSession;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Requests\LoginRequest;
use Spatie\RouteAttributes\Attributes\Post;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'store']]);
    }

    #[Post('store', name: 'store', middleware: 'guest')]
    public function store(LoginRequest $request)
    {
        return (new Pipeline(app()))->send($request)->through(array_filter([
            AttemptToAuthenticate::class,
        ]))->then(function ($request) {
            $credentials = request(['email', 'password']);
            auth()->attempt($credentials);

            return app(LoginResponse::class);
        });
    }

    /* protected function loginPipeline(LoginRequest $request) */
    /* { */
    /*     if (Fortify::$authenticateThroughCallback) { */
    /*         return (new Pipeline(app()))->send($request)->through(array_filter( */
    /*             call_user_func(Fortify::$authenticateThroughCallback, $request) */
    /*         )); */
    /*     } */

    /*     if (is_array(config('fortify.pipelines.login'))) { */
    /*         return (new Pipeline(app()))->send($request)->through(array_filter( */
    /*             config('fortify.pipelines.login') */
    /*         )); */
    /*     } */

    /*     //dd('1'); */

    /*     return (new Pipeline(app()))->send($request)->through(array_filter([ */
    /*         config('fortify.limiters.login') ? null : EnsureLoginIsNotThrottled::class, */
    /*         Features::enabled(Features::twoFactorAuthentication()) ? RedirectIfTwoFactorAuthenticatable::class : null, */
    /*         AttemptToAuthenticate::class, */
    /*         PrepareAuthenticatedSession::class, */
    /*     ])); */
    /* } */
}
