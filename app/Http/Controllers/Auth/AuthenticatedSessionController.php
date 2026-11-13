<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\LoginThrottleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function __construct(private LoginThrottleService $throttle) {}

    public function create(Request $request): View
    {
        // Email dari input lama (setelah gagal login) dipakai untuk mengetahui sisa waktu kunci
        return view('auth.login', [
            'lockoutSeconds' => $this->throttle->secondsLeft((string) $request->old('email'), (string) $request->ip()),
        ]);
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate($this->throttle);
        $request->session()->regenerate();

        // Penanda sesi "ingat saya"; hilang sendiri saat logout karena sesi dihapus
        $request->session()->put('remember_me', $request->boolean('remember'));

        return redirect()->intended(route('admin.dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Logout otomatis karena tidak aktif: kembali ke login dengan penjelasan
        if ($request->boolean('idle')) {
            return redirect()->route('login')->with('error', 'Anda keluar otomatis karena tidak ada aktivitas.');
        }

        return redirect()->route('login');
    }
}
