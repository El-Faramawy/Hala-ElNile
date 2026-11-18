<?php $setting = App\Models\Setting::first(); ?>

<!--APP-SIDEBAR-->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="side-header">
        <a class="header-brand1" href="{{ route('admin.home') }}">
            <img src="{{ get_file($setting?->logo) }}" class="header-brand-img desktop-logo" alt="logo">
            <img src="{{ get_file($setting?->logo) }}" class="header-brand-img toggle-logo" alt="logo">
            <img src="{{ get_file($setting?->logo) }}" class="header-brand-img light-logo" alt="logo">
            <img src="{{ get_file($setting?->logo) }}" class="header-brand-img light-logo1" alt="logo">
        </a><!-- LOGO -->
    </div>
    <ul class="side-menu">
        <li class="side-item side-item-category">
            <div class="side-category-badge">
                <i class="fe fe-activity side-category-icon"></i>
                <span class="side-category-text">لوحة التحكم</span>
            </div>
        </li>
        <x-sidebar-link href="{{ route('admin.home') }}">
            <i class="fe fe-home side-menu__icon"></i>
            <span class="side-menu__label">الرئيسية</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('admins.index') }}">
            <i class="fe fe-users side-menu__icon"></i>
            <span class="side-menu__label">المشرفين</span>
        </x-sidebar-link>

        <li class="side-item side-item-category">
            <div class="side-category-badge">
                <i class="fe fe-briefcase side-category-icon"></i>
                <span class="side-category-text">التوظيف والفرص</span>
            </div>
        </li>
        <x-sidebar-link href="{{ route('job-categories.index') }}">
            <i class="fe fe-grid side-menu__icon"></i>
            <span class="side-menu__label">أقسام ومجالات العمل</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('jobs.index') }}">
            <i class="fe fe-briefcase side-menu__icon"></i>
            <span class="side-menu__label">عقود وفرص العمل</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('job-applications.index') }}">
            <i class="fe fe-file-text side-menu__icon"></i>
            <span class="side-menu__label">طلبات التوظيف</span>
        </x-sidebar-link>

        <li class="side-item side-item-category">
            <div class="side-category-badge">
                <i class="fe fe-compass side-category-icon"></i>
                <span class="side-category-text">المتابعة والتجارب</span>
            </div>
        </li>
        <x-sidebar-link href="{{ route('contract-trackers.index') }}">
            <i class="fe fe-check-circle side-menu__icon"></i>
            <span class="side-menu__label">تتبع العقود والمعاملات</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('travelers.index') }}">
            <i class="fe fe-award side-menu__icon"></i>
            <span class="side-menu__label">مسافرونا في إيطاليا</span>
        </x-sidebar-link>

        <li class="side-item side-item-category">
            <div class="side-category-badge">
                <i class="fe fe-message-square side-category-icon"></i>
                <span class="side-category-text">بيانات الموقع والتواصل</span>
            </div>
        </li>
        <x-sidebar-link href="{{ route('contact-messages.index') }}">
            <i class="fe fe-mail side-menu__icon"></i>
            <span class="side-menu__label">رسائل واستفسارات العملاء</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('cities.index') }}">
            <i class="fe fe-map-pin side-menu__icon"></i>
            <span class="side-menu__label">المحافظات والمدن</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('faqs.index') }}">
            <i class="fe fe-help-circle side-menu__icon"></i>
            <span class="side-menu__label">الأسئلة الشائعة</span>
        </x-sidebar-link>

        <li class="side-item side-item-category">
            <div class="side-category-badge">
                <i class="fe fe-settings side-category-icon"></i>
                <span class="side-category-text">الإعدادات العامة</span>
            </div>
        </li>
        <x-sidebar-link href="{{ route('settings.index') }}">
            <i class="fe fe-settings side-menu__icon"></i>
            <span class="side-menu__label">إعدادات المنظومة</span>
        </x-sidebar-link>
    </ul>
</aside>
<!--/APP-SIDEBAR-->
