<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class UserController extends Controller
{

    public function LoginPage(): View
    {
        return view('pages.auth.login-page');
    }

    public function RegistrationPage(): View
    {
        return view('pages.auth.registration-page');
    }
    public function SendOtpPage(): View
    {
        return view('pages.auth.send-otp-page');
    }
    public function VerifyOTPPage(): View
    {
        return view('pages.auth.verify-otp-page');
    }

    public function ResetPasswordPage(): View
    {
        return view('pages.auth.reset-pass-page');
    }

    public function ProfilePage(): View
    {
        return view('pages.dashboard.profile-page');
    }

    public function UserRegistration(Request $request)
    {

        try {
            User::create([
                "firstName" => $request->input('firstName'),
                "lastName" => $request->input('lastName'),
                "email" => $request->input('email'),
                "mobile" => $request->input('mobile'),
                "password" => $request->input('password'),
            ]);

            return response()->json([
                "message" => "User Register Successfully!",
                "status" => "success",
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
                "status" => "failed",
            ], 401);
        }

    }

    public function UserLogin(Request $request)
    {
        $user = User::where('email', '=', $request->input('email'))
            ->where('password', '=', $request->input('password'))
            ->select('id')->first();

        if ($user !== null) {

            $token = JWTToken::CreateToken($request->input('email'), $user->id);
            return response()->json([
                "message" => "User Login Successfully!",
                "status" => "success",
            ], 200)->cookie('token', $token, 60 * 24 * 30);

        } else {

            return response()->json([
                "message" => "unauthorized",
                "status" => "failed",
            ], 401);
        }
    }

    public function SendOTPCode(Request $request)
    {
        $email = $request->input('email');
        $otp = rand(1000, 9999);
        $user = User::where('email', '=', $email)->count();

        if ($user == 1) {
            Mail::to($email)->send(new OTPMail($otp));
            User::where('email', '=', $email)->update(['otp' => $otp]);
            return response()->json([
                "message" => "4 - Digit OTP Code Has Been Send Your Email.",
                "status" => "success",
            ], 200);
        } else {
            return response()->json([
                "message" => "unauthorized",
                "status" => "failed",
            ], 401);
        }

    }

    public function VerifyOTP(Request $request)
    {

        $email = $request->input('email');
        $otp = $request->input('otp');
        $count = User::where('email', '=', $email)
            ->where('otp', '=', $otp)->count();

        if ($count == 1) {
            User::where('email', '=', $email)->update(['otp' => '0']);

            $token = JWTToken::CreateTokenForResetPassword($request->input('email'));
            return response()->json([
                'status' => 'success',
                'message' => 'OTP Verify Successfully!',
            ], 200)->cookie('token', $token, 60 * 24 * 30);

        } else {
            return response()->json([
                "message" => "unauthorized",
                "status" => "failed",
            ], 401);
        }

    }
    public function ResetPassword(Request $request)
    {
        try {
            $email = $request->header('email');
            $password = $request->input('password');
            User::where('email', '=', $email)->update(['password' => $password]);

            return response()->json([
                'status' => 'success',
                'message' => 'Password Reset Successfully!',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Something Went Wrong!',
            ], 200);
        }
    }

    public function UserLogout()
    {
        return redirect('/userLogin')->cookie('token', '', -1);
    }

    public function UserProfile(Request $request)
    {
        $email = $request->header('email');
        $user = User::where('email', '=', $email)->first();

        return response()->json([
            'status' => 'success',
            'message' => 'Request Successfully!',
            'data' => $user,
        ], 200);
    }

    public function UserUpdate(Request $request)
    {
        $email = $request->header('email');

        $request->validate([
            'password' => 'required|min:5',
        ]);

        User::where('email', '=', $email)->update([
            'firstName' => $request->input('firstName'),
            'lastName' => $request->input('lastName'),
            'mobile' => $request->input('mobile'),
            'password' => $request->input('password'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Profile Updated Successfully!',
        ], 200);
    }
}
