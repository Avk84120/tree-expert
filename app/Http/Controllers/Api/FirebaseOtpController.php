<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FirebaseOtpController extends Controller
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('FIREBASE_API_KEY'); // Add to .env
    }

    /**
     * Send OTP using Firebase Identity Toolkit API
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string'
        ]);

        $url = "https://identitytoolkit.googleapis.com/v1/accounts:sendVerificationCode?key={$this->apiKey}";

        $response = Http::post($url, [
            'phoneNumber' => $request->phone,
            'recaptchaToken' => 'ignored-for-testing' // In real apps, generate reCAPTCHA from frontend
        ]);

        if ($response->failed()) {
            return response()->json(['message' => 'OTP send failed', 'error' => $response->json()], 400);
        }

        return response()->json([
            'message' => 'OTP sent successfully',
            'sessionInfo' => $response->json()['sessionInfo']
        ]);
    }

    /**
     * Verify OTP using Firebase Identity Toolkit API
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'sessionInfo' => 'required|string',
            'code' => 'required|string'
        ]);

        $url = "https://identitytoolkit.googleapis.com/v1/accounts:signInWithPhoneNumber?key={$this->apiKey}";

        $response = Http::post($url, [
            'sessionInfo' => $request->sessionInfo,
            'code' => $request->code,
        ]);

        if ($response->failed()) {
            return response()->json(['message' => 'Invalid OTP', 'error' => $response->json()], 401);
        }

        $data = $response->json();

        return response()->json([
            'message' => 'OTP verified successfully',
            'firebase_user' => $data
        ]);
    }
}
