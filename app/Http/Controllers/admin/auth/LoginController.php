<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    // Show Register Page
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Handle Register
    public function register(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name'     => 'required|string',
            'email'    => 'nullable|email|unique:users,email',
            'mobile'   => 'required|unique:users,mobile',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'mobile'   => $request->mobile,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registration successful!');
    }

    // Show Login Page
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle Login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'mobile', 'password');

        if (isset($credentials['password'])) {
            // Try login with email or mobile
            $user = User::where('email', $request->email)
                        ->orWhere('mobile', $request->mobile)
                        ->first();

            if ($user && Hash::check($request->password, $user->password)) {
                Auth::login($user);
                return redirect()->route('dashboard')->with('success', 'Logged in successfully!');
            }

            return back()->withErrors(['login' => 'Invalid credentials'])->withInput();
        }

        // OTP login
        if ($request->otp && $request->mobile) {
            $cacheKey = 'otp_' . $request->mobile;
            if (Cache::get($cacheKey) != $request->otp) {
                return back()->withErrors(['otp' => 'Invalid OTP'])->withInput();
            }

            $user = User::firstOrCreate(['mobile' => $request->mobile], [
                'name' => 'User ' . $request->mobile,
                'password' => Hash::make('default123') // default for OTP users
            ]);

            Cache::forget($cacheKey);
            Auth::login($user);

            return redirect()->route('dashboard')->with('success', 'Logged in with OTP!');
        }

        return back()->withErrors(['login' => 'Bad request'])->withInput();
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }

    // Send OTP
    public function sendOtp(Request $request)
    {
        $request->validate(['mobile' => 'required']);
        $code = rand(100000, 999999);

        Cache::put('otp_' . $request->mobile, (string)$code, now()->addMinutes(10));

        // TODO: Integrate SMS service here
        return back()->with('otp_sent', "OTP sent to {$request->mobile}. (Debug OTP: $code)");
    }
}
