@extends('layouts.admin.auth.layout')

@section('content')
    {{-- Glowing background accents --}}
    <div class="hn-ambient-glow hn-ambient-1"></div>
    <div class="hn-ambient-glow hn-ambient-2"></div>
    <div class="hn-ambient-glow hn-ambient-3"></div>

    <div class="login-card-wrapper">
        {{-- Branding & Ministry License Badge --}}
        <div class="login-header-branding">
            <a href="{{ url('/') }}">
                <img src="{{ asset('Site/assets/images/logo-gold.svg') }}?v=1" class="login-logo-img" alt="{{ setting()->name }}">
            </a>
            <div>
                <span class="license-badge">
                    <i class="fa fa-certificate"></i>
                    ترخيص وزارة العمل رقم 912 شركات
                </span>
            </div>
        </div>

        {{-- Authentication Card --}}
        <div class="login-card">
            <div class="login-card-head">
                <div class="login-shield-icon">
                    <i class="fe fe-shield"></i>
                </div>
                <h1 class="login-card-title">لوحة تحكم {{ setting()->name }}</h1>
                <p class="login-card-subtitle">سجل دخولك لإدارة عقود العمل وتتبع المعاملات</p>
            </div>

            <form class="my_form" action="{{ route('admin.post_login') }}" method="POST" novalidate>
                @csrf

                {{-- Email --}}
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

                {{-- Password --}}
                <div class="form-group-custom">
                    <label class="form-label-custom" for="admin-password">كلمة المرور</label>
                    <div class="input-wrap-custom">
                        <input id="admin-password"
                               type="password"
                               name="password"
                               class="input-control-custom"
                               placeholder="123456"
                               autocomplete="current-password"
                               required>
                        <span class="input-icon-custom">
                            <i class="fa fa-lock"></i>
                        </span>
                        <button type="button" class="toggle-pass-btn" tabindex="-1" onclick="togglePassword(this)">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                </div>

                {{-- Submit Button --}}
                <button type="submit" class="btn-submit-login">
                    <span>تسجيل الدخول</span>
                    <i class="fa fa-sign-in"></i>
                </button>
            </form>
        </div>

        {{-- Footer --}}
        <div class="login-footer">
            <p class="login-footer-copy">{{ setting()->name }} &copy; {{ date('Y') }} — جميع الحقوق محفوظة</p>
        </div>
    </div>
@endsection

@push('css')
    @include('Admin.auth.parts.css')
@endpush

@push('js')
    @include('Admin.auth.parts.js')
@endpush
