<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerificationController extends Controller
{
    use VerifiesEmails;

    /**
     * VerificationController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * @param Request $request
     * @return RedirectResponse|View
     */
    public function show(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectTo());
        }

        return view('auth.verify');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function verify(Request $request)
    {
        $routeId = (string)$request->route('id');
        $routeHash = (string)$request->route('hash');
        $userId = (string)$request->user()->getKey();
        $userEmail = (string)$request->user()->getEmailForVerification();

        if (!hash_equals($routeId, $userId)) {
            throw new AuthorizationException;
        }

        if (!hash_equals($routeHash, generateVerifyEmailHash($userId, $userEmail))) {
            throw new AuthorizationException;
        }

        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectTo());
        }

        return $this->verified($request);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    protected function verified(Request $request)
    {
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect($this->redirectTo());
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectTo());
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->withFlashSuccess(trans('auth.verify.sent'));
    }

    /**
     * @return string
     */
    protected function redirectTo()
    {
        return routeHome();
    }
}
