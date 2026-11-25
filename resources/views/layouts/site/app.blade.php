<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  
  <title>@yield('title', 'شركة هلا النيل لإلحاق العمالة بالخارج | عقود وتأشيرات عمل إيطاليا الرسمية')</title>
  <meta name="description" content="@yield('meta_description', 'شركة هلا النيل لإلحاق العمالة المصرية بالخارج ترخيص قوى عاملة رقم 912 شركات. بوابتك القانونية الموثوقة للسفر والعمل في إيطاليا عبر عقود فلوتسي (Decreto Flussi) المعتمدة.')">
  <meta name="keywords" content="إلحاق عمالة, السفر لإيطاليا, عقود عمل إيطاليا, فلوتسي, ديكريتو فلوتسي, نولا أوستا, هلا النيل, وظائف إيطاليا, تأشيرة عمل إيطاليا">
  <meta name="author" content="شركة هلا النيل">
  <meta name="theme-color" content="#0b223d">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Open Graph / Social Media -->
  <meta property="og:title" content="شركة هلا النيل لإلحاق العمالة بالخارج | عقود عمل إيطاليا">
  <meta property="og:description" content="عقود عمل رسمية وموثقة في إيطاليا (مطاعم، زراعة، بناء، قيادة شاحنات) برواتب مجزية وسكن مؤثث.">
  <meta property="og:image" content="{{ asset('Site/assets/images/hero/slide-travelers-italy.jpg') }}">
  <meta property="og:type" content="website">

  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml" href="{{ asset('Site/assets/images/favicon.svg') }}">

  <!-- Google Fonts: Cairo & Tajawal -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&family=Tajawal:wght@400;500;700;900&display=swap" rel="stylesheet">

  <!-- FontAwesome 6 Free -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- Bootstrap 5 RTL -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">

  <!-- Swiper.js CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

  <!-- Toastify CSS (Dashboard Style) -->
  <link rel="stylesheet" type="text/css" href="{{ asset('Admin/assets/plugins/toastify/toastify.min.css') }}">

  <!-- Custom Styles -->
  <link rel="stylesheet" href="{{ asset('Site/assets/css/style.css?v=5.8') }}">

  @stack('styles')
</head>
<body>

  <!-- Page Loader -->
  <div id="page-loader" aria-hidden="true">
    <div class="page-loader-card">
      <img src="{{ asset('Site/assets/images/loader.webp') }}" alt="جاري التحميل..." class="page-loader-img">
    </div>
  </div>

  <!-- Top Header Bar -->
  <x-site.top-bar />

  <!-- Main Navbar -->
  <x-site.navbar />

  <!-- Main Page Content -->
  <main>
    @yield('content')
  </main>

  <!-- Main Footer -->
  <x-site.footer />

  <!-- Shared Modals (Quick Apply & Job Details) -->
  <x-site.modals :cities="$cities ?? []" />

  <!-- Floating WhatsApp & Back to Top -->
  <x-site.floating-buttons />

  <!-- Shared Global Config -->
  <script>
    window.SiteConfig = {
      csrfToken: '{{ csrf_token() }}',
      whatsapp: '{{ !empty($settings?->whatsapp) ? preg_replace('/[^0-9]/', '', $settings->whatsapp) : '' }}',
      routes: {
        jobsFilter: '{{ route('site.jobs.filter') }}',
        apply: '{{ route('site.apply.store') }}',
        track: '{{ route('site.tracker.track') }}',
        contact: '{{ route('site.contact.store') }}'
      }
    };
  </script>

  <!-- Scripts: Bootstrap, Swiper, SweetAlert2, Toastify, App Data & Logic -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script type="text/javascript" src="{{ asset('Admin/assets/plugins/toastify/toastify.js') }}"></script>
  @include('layouts.admin.inc.toaster')
  <script src="{{ asset('Site/assets/js/jobs-data.js?v=5.1') }}"></script>
  <script src="{{ asset('Site/assets/js/main.js?v=5.8') }}"></script>

  @stack('scripts')
</body>
</html>
