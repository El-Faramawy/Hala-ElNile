<?php $setting = App\Models\Setting::first(); ?>

<!--APP-SIDEBAR-->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="side-header">
        <a class="header-brand1" href="{{ route('accounting.dashboard') }}">
            <img src="{{ get_file($setting?->logo) }}" class="header-brand-img desktop-logo" alt="logo">
            <img src="{{ get_file($setting?->logo) }}" class="header-brand-img toggle-logo" alt="logo">
            <img src="{{ get_file($setting?->logo) }}" class="header-brand-img light-logo" alt="logo">
            <img src="{{ get_file($setting?->logo) }}" class="header-brand-img light-logo1" alt="logo">
        </a>
    </div>
    <ul class="side-menu">
        <li class="side-item side-item-category">
            <div class="side-category-badge">
                <i class="fe fe-activity side-category-icon"></i>
                <span class="side-category-text">لوحة الحسابات</span>
            </div>
        </li>
        <x-sidebar-link href="{{ route('accounting.dashboard') }}">
            <i class="fe fe-home side-menu__icon"></i>
            <span class="side-menu__label">الرئيسية المالية</span>
        </x-sidebar-link>

        <li class="side-item side-item-category">
            <div class="side-category-badge">
                <i class="fe fe-dollar-sign side-category-icon"></i>
                <span class="side-category-text">الحركة اليومية</span>
            </div>
        </li>
        <x-sidebar-link href="{{ route('revenues.index') }}">
            <i class="fe fe-download side-menu__icon text-success"></i>
            <span class="side-menu__label">المقبوضات وسندات القبض</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('conversions.index') }}">
            <i class="fe fe-refresh-cw side-menu__icon text-primary"></i>
            <span class="side-menu__label">تحويل وتصريف العملة</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('expenses.index') }}">
            <i class="fe fe-upload side-menu__icon text-danger"></i>
            <span class="side-menu__label">المصروفات وسندات الصرف</span>
        </x-sidebar-link>

        <li class="side-item side-item-category">
            <div class="side-category-badge">
                <i class="fe fe-briefcase side-category-icon"></i>
                <span class="side-category-text">حسابات العقود والتحصيل</span>
            </div>
        </li>
        <x-sidebar-link href="{{ route('accounting.contracts.index') }}">
            <i class="fe fe-file-text side-menu__icon text-info"></i>
            <span class="side-menu__label">الحسابات المالية للعقود</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('accounting.overdue.index') }}">
            <i class="fe fe-alert-circle side-menu__icon text-warning"></i>
            <span class="side-menu__label">الدفعات المتأخرة</span>
        </x-sidebar-link>

        <li class="side-item side-item-category">
            <div class="side-category-badge">
                <i class="fe fe-layers side-category-icon"></i>
                <span class="side-category-text">الأطراف والخزائن</span>
            </div>
        </li>
        <x-sidebar-link href="{{ route('suppliers.index') }}">
            <i class="fe fe-truck side-menu__icon"></i>
            <span class="side-menu__label">الموردين والوكلاء</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('treasuries.index') }}">
            <i class="fe fe-database side-menu__icon"></i>
            <span class="side-menu__label">الخزائن وحركة النقدية</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('expense-categories.index') }}">
            <i class="fe fe-folder side-menu__icon"></i>
            <span class="side-menu__label">تصنيفات المصروفات الرئيسية</span>
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('expense-types.index') }}">
            <i class="fe fe-tag side-menu__icon"></i>
            <span class="side-menu__label">أنواع وبنود المصروفات</span>
        </x-sidebar-link>

        <li class="side-item side-item-category">
            <div class="side-category-badge">
                <i class="fe fe-pie-chart side-category-icon"></i>
                <span class="side-category-text">التقارير والإحصائيات</span>
            </div>
        </li>
        <x-sidebar-link href="{{ route('accounting.reports.index') }}">
            <i class="fe fe-bar-chart-2 side-menu__icon"></i>
            <span class="side-menu__label">التقارير المالية والأرباح</span>
        </x-sidebar-link>
    </ul>
</aside>
<!--/APP-SIDEBAR-->
