@extends('layouts.admin.auth.layout')

@section('content')
    <div class="hn-ambient-glow hn-ambient-1"></div>
    <div class="hn-ambient-glow hn-ambient-2"></div>
    <div class="hn-ambient-glow hn-ambient-3"></div>

    <div class="login-card-wrapper">
        <div class="login-header-branding">
            <a href="{{ url('/') }}">
                <img src="{{ asset('Site/assets/images/logo-gold.svg') }}?v=1" class="login-logo-img" alt="{{ setting()->name }}">
            </a>
            <div>
                <span class="license-badge">
                    <i class="fa fa-certificate"></i>
                    المنظومة المالية والإدارة المحاسبية
                </span>
            </div>
        </div>

        <div class="login-card">
            <div class="login-card-head">
                <div class="login-shield-icon" style="background: rgba(0, 140, 69, 0.15); color: #008c45;">
                    <i class="fe fe-dollar-sign"></i>
                </div>
                <h1 class="login-card-title">بوابة الحسابات والمالية</h1>
                <p class="login-card-subtitle">سجل دخولك لإدارة الخزائن وسندات القبض والصرف وعقود السفر</p>
            </div>

            <form class="my_form" action="{{ route('accounting.post_login') }}" method="POST" novalidate>
                @csrf

                <div class="form-group-custom">
                    <label class="form-label-custom" for="admin-email">البريد الإلكتروني</label>
                    <div class="input-wrap-custom">
                        <input id="admin-email"
                               type="email"
                               name="email"
                               class="input-control-custom"
                               placeholder="admin@admin.com"
                               value="admin@admin.com"
                               autocomplete="email"
                               required>
                        <span class="input-icon-custom">
                            <i class="fa fa-envelope-o"></i>
                        </span>
                    </div>
                </div>

                <div class="form-group-custom">
                    <label class="form-label-custom" for="admin-password">كلمة المرور</label>
                    <div class="input-wrap-custom">
                        <input id="admin-password"
                               type="password"
                               name="password"
                               class="input-control-custom"
                               placeholder="••••••••"
                               value="123456"
                               autocomplete="current-password"
                               required>
                        <span class="input-icon-custom">
                            <i class="fa fa-lock"></i>
                        </span>
                        <button type="button" class="password-toggle-btn" id="togglePasswordBtn" aria-label="تبديل ظهور كلمة المرور">
                            <i class="fa fa-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="form-action-wrap">
                    <button type="submit" class="login-submit-btn" id="loginBtn" style="background: linear-gradient(135deg, #008c45 0%, #0b223d 100%);">
                        <span class="btn-spinner d-none" id="loginSpinner">
                            <i class="fa fa-circle-o-notch fa-spin"></i>
                        </span>
                        <span class="btn-text" id="loginBtnText">
                            <i class="fa fa-calculator ml-1"></i> دخول لوحة الحسابات
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
