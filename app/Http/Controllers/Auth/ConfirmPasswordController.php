<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ConfirmPasswordRequest;
use Illuminate\Foundation\Auth\ConfirmsPasswords;
use Illuminate\Http\RedirectResponse;

class ConfirmPasswordController extends Controller
{
    use ConfirmsPasswords;

    /**
     * ConfirmPasswordController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param ConfirmPasswordRequest $request
     * @return RedirectResponse
     */
    public function confirm(ConfirmPasswordRequest $request)
    {
        $this->resetPasswordConfirmationTimeout($request);

        return redirect()->intended($this->redirectTo());
    }

    /**
     * @return string
     */
    protected function redirectTo()
    {
        return routeHome();
    }
}
