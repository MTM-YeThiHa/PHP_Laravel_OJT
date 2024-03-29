<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\Services\Auth\AuthServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserSignUpRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegisterController extends Controller
{
    //Register controller
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;


    /**
     * Auth Interface
     */
    private $authInterface;

    /**
     * Create a new controller instance.
     * @param AuthServiceInterface $authServiceInterface
     * @return void
     */

    public function __construct(AuthServiceInterface $authServiceInterface)
    {
        // $this->middleware('auth');
        $this->authInterface = $authServiceInterface;
    }

    /**
     * To show registration view
     *
     * @return View registration form
     */

    protected function showRegistrationView()
    {
        return view('auth.register');
    }

    /**
     * To check register form is valid or not.
     * If valid will return to register confim page.
     * If not, redirect to register page.
     *
     * @param  UserRegisterRequest $request Request from register
     * @return View registration confirm
     */

    protected function submitRegistrationView(UserRegisterRequest $request)
    {
        $validated = $request->validated();
        $fileName = time() . Auth::user()->id . '.' . $request->file('profile')->getClientOriginalExtension();
        $request->file('profile')->storeAs('public/profiles/', $fileName);   
        return redirect()
            ->route('register.confirm')
            ->withInput()
            ->with('profile', $fileName);
    }

    /**
     * To show registration view
     *
     * @return View registration confirm view
     */
    protected function showRegistrationConfirmView()
    {
        if (old()) {
            return view('auth.register-confirm');
        }
        return redirect()
            ->route('userlist');
    }

    protected function submitRegistrationConfirmView(Request $request)
    {
        $user = $this->authInterface->saveUser($request);
        return redirect()
            ->route('userlist')->with('message', 'User created successfully');
    }

    public function showUserRegistration()
    {
        return view('users.register');
    }

    public function submitUserRegistration(UserSignUpRequest $request)
    {
        $validated = $request->validated();
        $user = $this->authInterface->saveUser($request);
        return redirect()->route('login')->with('message', 'User signup successfully');
    }
}
