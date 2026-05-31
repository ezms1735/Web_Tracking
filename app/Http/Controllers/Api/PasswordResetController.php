<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Pengguna;
use Carbon\Carbon;
use App\Mail\ResetPasswordMail;

class PasswordResetController extends Controller
{
    private $tableName = 'password_reset_tokens';

    public function sendResetToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = Pengguna::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'success' => false, 
                'message' => 'Email tidak terdaftar di sistem kami.'
            ], 404);
        }

        $token = mt_rand(100000, 999999);

        DB::table($this->tableName)->where('email', $request->email)->delete();

        DB::table($this->tableName)->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::to($request->email)->send(new ResetPasswordMail($token));

        return response()->json([
            'success' => true, 
            'message' => 'Kode token berhasil dikirim ke email.'
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|numeric',
            'password' => 'required|min:6'
        ]);

        $resetRecord = DB::table($this->tableName)
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$resetRecord) {
            return response()->json([
                'success' => false, 
                'message' => 'Kode token salah atau tidak valid.'
            ], 400);
        }

        $createdAt = Carbon::parse($resetRecord->created_at);
        if (Carbon::now()->diffInMinutes($createdAt) > 60) {
            DB::table($this->tableName)->where('email', $request->email)->delete();
            
            return response()->json([
                'success' => false, 
                'message' => 'Kode token sudah kedaluwarsa. Silakan minta ulang.'
            ], 400);
        }

        $user = Pengguna::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table($this->tableName)->where('email', $request->email)->delete();

        return response()->json([
            'success' => true, 
            'message' => 'Kata sandi berhasil diubah.'
        ]);
    }
}