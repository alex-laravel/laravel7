<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User\User;
use App\Repositories\User\UserRepository;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * ResetPasswordController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->middleware('guest');

        $this->userRepository = $userRepository;
    }

    /**
     * @param ResetPasswordRequest $request
     * @return RedirectResponse
     */
    public function reset(ResetPasswordRequest $request)
    {
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        return $response == Password::PASSWORD_RESET
            ? $this->sendResetResponse($request, $response)
            : $this->sendResetFailedResponse($request, $response);
    }

    /**
     * @param User $user
     * @param string $password
     * @return void
     * @throws \Exception
     */
    protected function resetPassword($user, $password)
    {
        $user = $this->userRepository->resetPassword($user, $password);

        event(new PasswordReset($user));

        $this->guard()->login($user);
    }

    /**
     * @param ResetPasswordRequest $request
     * @param string $response
     * @return RedirectResponse
     */
    protected function sendResetResponse(ResetPasswordRequest $request, $response)
    {
        return redirect($this->redirectPath())
            ->withFlashSuccess(trans($response));
    }

    /**
     * @param ResetPasswordRequest $request
     * @param string $response
     * @return RedirectResponse
     */
    protected function sendResetFailedResponse(ResetPasswordRequest $request, $response)
    {
        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => trans($response)]);
    }

    /**
     * @return string
     */
    protected function redirectTo()
    {
        return routeHome();
    }
}
