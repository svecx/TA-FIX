<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $user = \App\Models\User::where('email', $request->email)->first();
        $errorMessages = [];

        if (!$user) {
            // Jika username/email tidak ditemukan
            $errorMessages['email'] = 'Username salah.';
        } elseif (!Hash::check($request->password, $user->password)) {
            // Jika username/email ditemukan tetapi password salah
            $errorMessages['password'] = 'Password salah.';
        } elseif (!$user->approved) {
            // Jika akun belum diapprove
            if ($user->jabatan === 'Mahasiswa') {
                $errorMessages['email'] = 'Akun Anda belum diverifikasi. Mohon untuk menghubungi Kaprodi untuk verifikasi.';
            } else {
                $errorMessages['email'] = 'Akun Anda belum diverifikasi. Mohon untuk menghubungi Admin untuk verifikasi.';
            }
        }

        if (!empty($errorMessages)) {
            throw ValidationException::withMessages($errorMessages);
        }
    }

    protected function authenticated(Request $request, $user)
    {
        if (!$user->approved) {
            auth()->logout();

            if ($user->jabatan === 'Mahasiswa') {
                return redirect('/login')->withErrors([
                    'email' => 'Akun Anda belum diverifikasi. Mohon untuk menghubungi Kaprodi untuk verifikasi.'
                ]);
            } else {
                return redirect('/login')->withErrors([
                    'email' => 'Akun Anda belum diverifikasi. Mohon untuk menghubungi Admin untuk verifikasi.'
                ]);
            }
        }
    }
}
