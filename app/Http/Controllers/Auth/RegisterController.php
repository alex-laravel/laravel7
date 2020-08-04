<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User\User;
use App\Repositories\User\UserRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\RedirectResponse;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * RegisterController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->middleware('guest');

        $this->userRepository = $userRepository;
    }

    /**
     * @param RegisterRequest $request
     * @return RedirectResponse
     */
    public function register(RegisterRequest $request)
    {
        $user = $this->userRepository->create($request->only([
            'name',
            'email',
            'password',
        ]));

        return $this->registered($user);
    }

    /**
     * @param User $user
     * @return RedirectResponse
     */
    protected function registered($user)
    {
        event(new Registered($user));

        $this->guard()->login($user);

        return redirect($this->redirectTo())->withFlashSuccess(trans('alerts.auth.verify.sent'));
    }

    /**
     * @return string
     */
    protected function redirectTo()
    {
        return routeHome();
    }
}
