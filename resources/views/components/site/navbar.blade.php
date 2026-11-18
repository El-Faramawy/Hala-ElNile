<!-- ==========================================================================
     Main Navbar
     ========================================================================== -->
<nav class="navbar navbar-expand-xl main-navbar" id="mainNavbar">
  <!-- Smooth Scroll Progress Line (Directly Below Navbar) -->
  <div class="scroll-progress-bar" id="scrollProgressBar"></div>

  <div class="container-fluid px-lg-4 px-xxl-5">
    <!-- Brand Logo -->
    <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
      <img src="{{ asset('Site/assets/images/logo-header.svg') }}" alt="شركة هلا النيل لإلحاق العمالة بالخارج" class="navbar-brand-logo">
    </a>

    <!-- Mobile Toggle Button -->
    <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="تبديل القائمة">
      <i class="fa-solid fa-bars-staggered fs-3 text-primary"></i>
    </button>

    <!-- Nav Links (Single line strictly, with dynamic active indicator) -->
    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav mx-auto mb-2 mb-xl-0 align-items-center">
        <li class="nav-item">
          <a class="nav-link active" href="#hero">الرئيسية</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#about">من نحن</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#why-us">لماذا نحن؟</a>
        </li>
        <li class="nav-item">
          <a class="nav-link fw-bold text-success" href="#jobs">
            <i class="fa-solid fa-fire text-danger me-1"></i> الوظائف المتاحة
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#steps">خطوات السفر</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#travelers">مسافرونا بإيطاليا</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#tracker">تتبع العقد</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#faq">الأسئلة الشائعة</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#contact">تواصل معنا</a>
        </li>
      </ul>

      <!-- Action CTA -->
      <div class="d-flex align-items-center gap-2 mt-3 mt-xl-0">
        <button class="btn btn-primary px-3 py-2 rounded-pill shadow-sm text-nowrap" data-bs-toggle="modal" data-bs-target="#quickApplyModal" onclick="prepareApplyModal('طلب توظيف واستشارة مجانية')">
          <i class="fa-solid fa-file-signature me-1"></i> قدّم طلبك الآن
        </button>
      </div>
    </div>
  </div>
</nav>
