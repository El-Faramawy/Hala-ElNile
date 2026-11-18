<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (admin()->check()) {
            return redirect('admin/home');
        }

        return view('Admin.auth.login');
    }

    public function login(LoginRequest $request): JsonResponse
    {
        if (admin()->attempt($request->validated())) {
            return response()->json([
                'message' => 'تم تسجيل الدخول بنجاح',
                'url' => route('admin.home'),
            ]);
        }

        return response()->json([
            'errors' => ['بيانات الدخول غير صحيحة، يرجى التأكد من البريد وكلمة المرور.'],
        ], 422);
    }

    public function logout(): RedirectResponse
    {
        admin()->logout();
        my_toaster('تم تسجيل الخروج بنجاح', 'warning');

        return redirect('admin/login');
    }
}
