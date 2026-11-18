<?php

declare(strict_types=1);

use App\Models\Setting;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;

if (! function_exists('setting')) {
    /**
     * Retrieve site settings or a new instance if none exists.
     */
    function setting(): Setting
    {
        return Setting::first() ?? new Setting;
    }
}

if (! function_exists('admin')) {
    /**
     * Get the admin authentication guard.
     */
    function admin(): Guard|StatefulGuard
    {
        return auth()->guard('admin');
    }
}

if (! function_exists('get_file')) {
    /**
     * Resolve public or storage asset URL with fallback to default image.
     */
    function get_file(?string $file): string
    {
        $defaultImg = asset('Admin/imgs/default.jpg');

        if (empty($file) || trim($file) === '') {
            return $defaultImg;
        }

        // 1. External or full URLs
        if (filter_var($file, FILTER_VALIDATE_URL)) {
            $parsedPath = parse_url($file, PHP_URL_PATH);
            if ($parsedPath) {
                // Local storage URL (e.g. http://localhost/storage/...)
                if (str_contains($parsedPath, '/storage/')) {
                    $storageRelative = substr($parsedPath, strpos($parsedPath, '/storage/') + 9);
                    if (
                        (file_exists(public_path('storage/'.$storageRelative)) && ! is_dir(public_path('storage/'.$storageRelative))) ||
                        (file_exists(storage_path('app/public/'.$storageRelative)) && ! is_dir(storage_path('app/public/'.$storageRelative)))
                    ) {
                        return $file;
                    }

                    return $defaultImg;
                }

                // Local public asset (e.g. http://localhost/Admin/imgs/default.jpg)
                $trimmedPublic = ltrim($parsedPath, '/');
                if (file_exists(public_path($trimmedPublic)) && ! is_dir(public_path($trimmedPublic))) {
                    return $file;
                }
            }

            return $file;
        }

        $file = ltrim($file, '/');

        // 2. Relative storage path starting with 'storage/'
        if (str_starts_with($file, 'storage/')) {
            $relativePath = substr($file, 8);
            if (file_exists(public_path($file)) && ! is_dir(public_path($file))) {
                return asset($file);
            }
            if (file_exists(storage_path('app/public/'.$relativePath)) && ! is_dir(storage_path('app/public/'.$relativePath))) {
                return asset($file);
            }

            return $defaultImg;
        }

        // 3. File exists directly in public directory
        if (file_exists(public_path($file)) && ! is_dir(public_path($file))) {
            return asset($file);
        }

        // 4. File exists in storage disk
        if (file_exists(public_path('storage/'.$file)) && ! is_dir(public_path('storage/'.$file))) {
            return asset('storage/'.$file);
        }

        if (file_exists(storage_path('app/public/'.$file)) && ! is_dir(storage_path('app/public/'.$file))) {
            return asset('storage/'.$file);
        }

        return $defaultImg;
    }
}

if (! function_exists('my_toaster')) {
    /**
     * Flash a toast notification to the session.
     */
    function my_toaster(string $message = 'تم بنجاح', string $alertType = 'success'): void
    {
        session()->flash('message', $message);
        session()->flash('type', $alertType);
    }
}

if (! function_exists('tableCheckbox')) {
    /**
     * Generate DataTable row selection checkbox.
     */
    function tableCheckbox(int|string $id): string
    {
        return '<input type="checkbox" class="sub_chk" data-id="'.htmlspecialchars((string) $id, ENT_QUOTES, 'UTF-8').'">';
    }
}

if (! function_exists('tableAction')) {
    /**
     * Generate DataTable action buttons (edit, delete, view).
     */
    function tableAction(int|string $id, bool $edit = false, bool $delete = false, bool $view = false): string
    {
        $escapedId = htmlspecialchars((string) $id, ENT_QUOTES, 'UTF-8');
        $action = '';

        if ($edit) {
            $action .= '<button id="editBtn" class="btn btn-default btn-primary btn-sm mb-2 mb-xl-0 editBtn" data-toggle="tooltip" title="تعديل" data-id="'.$escapedId.'">
                            <i class="fa fa-edit text-white"></i>
                        </button>';
        }

        if ($delete) {
            $action .= ' <button class="btn btn-default btn-danger btn-sm mb-2 mb-xl-0 delete" data-toggle="tooltip" title="حذف" data-id="'.$escapedId.'">
                            <i class="fa fa-trash-o text-white"></i>
                        </button>';
        }

        if ($view) {
            $action .= ' <button class="btn btn-default btn-action-view btn-sm mb-2 mb-xl-0 view" data-toggle="tooltip" title="عرض" data-id="'.$escapedId.'">
                            <i class="fa fa-eye text-white"></i>
                        </button>';
        }

        return $action;
    }
}

if (! function_exists('tableStatus')) {
    /**
     * Generate DataTable status badge (Active / Inactive).
     */
    function tableStatus(bool $isActive): string
    {
        if ($isActive) {
            return '<span class="badge badge-success px-3 py-2" style="font-size: 11px; font-weight: 600; border-radius: 6px;"><i class="fa fa-check mr-1"></i> مفعل</span>';
        }

        return '<span class="badge badge-danger px-3 py-2" style="font-size: 11px; font-weight: 600; border-radius: 6px;"><i class="fa fa-times mr-1"></i> غير مفعل</span>';
    }
}

if (! function_exists('tableImage')) {
    /**
     * Generate DataTable thumbnail image preview with external link.
     */
    function tableImage(?string $path, string $alt = 'thumbnail'): string
    {
        $defaultUrl = asset('Admin/imgs/default.jpg');
        $url = $path ? get_file($path) : $defaultUrl;
        $escapedUrl = htmlspecialchars((string) $url, ENT_QUOTES, 'UTF-8');
        $escapedAlt = htmlspecialchars($alt, ENT_QUOTES, 'UTF-8');

        return '<a href="'.$escapedUrl.'" target="_blank" class="table-img-link" title="عرض">'.
            '<img src="'.$escapedUrl.'" class="table-img shadow-sm" style="width: 60px; height: 44px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0; margin: 0; padding: 0; display: block;" onerror="this.onerror=null;this.src=\''.$defaultUrl.'\';" alt="'.$escapedAlt.'">'.
            '</a>';
    }
}

if (! function_exists('tableIcon')) {
    /**
     * Generate DataTable icon representation (CSS font icon, SVG, or image).
     */
    function tableIcon(?string $icon, string $fallbackIcon = 'fa-solid fa-briefcase'): string
    {
        // 1. Fallback badge when empty
        if (empty($icon)) {
            return '<div class="d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 42px; border-radius: 10px; background: #edf3f9; color: #0b223d; border: 1.5px solid #cbd9ea; font-size: 18px;"><i class="'.htmlspecialchars($fallbackIcon, ENT_QUOTES, 'UTF-8').'"></i></div>';
        }

        // 2. CSS font icon classes (e.g. "fa-solid fa-...", "fa fa-...", "fe fe-...", "bi bi-...", "mdi mdi-...")
        if (str_starts_with($icon, 'fa-') || str_starts_with($icon, 'fa ') || str_starts_with($icon, 'fas ') || str_starts_with($icon, 'far ') || str_starts_with($icon, 'fab ') || str_starts_with($icon, 'fe ') || str_starts_with($icon, 'bi ') || str_starts_with($icon, 'mdi ')) {
            return '<div class="d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 42px; border-radius: 10px; background: #edf3f9; color: #008c45; border: 1.5px solid #cbd9ea; font-size: 18px;"><i class="'.htmlspecialchars($icon, ENT_QUOTES, 'UTF-8').'"></i></div>';
        }

        // 3. Raw SVG markup
        if (str_starts_with(trim($icon), '<svg')) {
            return '<div class="table-img-link d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 42px; border-radius: 10px; background: #f8fafc; border: 1.5px solid #e2e8f0; margin: 0; padding: 0;">'.$icon.'</div>';
        }

        // 4. File path or URL
        $url = get_file($icon);
        $defaultUrl = asset('Admin/imgs/default.jpg');
        $isSvg = str_ends_with(strtolower((string) parse_url((string) $icon, PHP_URL_PATH)), '.svg');
        $fit = $isSvg ? 'contain' : 'cover';
        $escapedUrl = htmlspecialchars((string) $url, ENT_QUOTES, 'UTF-8');

        return '<a href="'.$escapedUrl.'" target="_blank" class="table-img-link" title="عرض">'.
            '<img src="'.$escapedUrl.'" class="table-img shadow-sm" style="width: 50px; height: 42px; object-fit: '.$fit.'; border-radius: 10px; border: 1.5px solid #cbd9ea; background: #f8fafc; margin: 0; padding: 0; display: block;" onerror="this.onerror=null;this.src=\''.$defaultUrl.'\';" alt="icon">'.
            '</a>';
    }
}
