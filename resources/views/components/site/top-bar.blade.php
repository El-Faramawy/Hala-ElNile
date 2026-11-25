<!-- ==========================================================================
     Top Header Bar
     ========================================================================== -->
<div class="top-bar d-none d-lg-block">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center">
      <!-- License & Hours -->
      <div class="d-flex align-items-center gap-3">
        <span class="top-bar-license-badge">
          <i class="fa-solid fa-certificate"></i>
          ترخيص وزارة القوى العاملة رقم 912 شركات
        </span>
        <span class="text-white-50">|</span>
        <span>
          <i class="fa-regular fa-clock text-warning me-1"></i>
          مواعيد العمل: السبت - الخميس: 9:00 ص - 6:00 م
        </span>
      </div>

      <!-- Contact Hotline & Address -->
      <div class="d-flex align-items-center gap-4">
        @if(!empty($settings?->phone))
          <a href="tel:{{ $settings->phone }}" class="d-flex align-items-center gap-1">
            <i class="fa-solid fa-phone text-success"></i>
            <span class="font-monospace dir-ltr fw-bold">{{ $settings->phone }}</span>
          </a>
        @endif

        @if(!empty($settings?->whatsapp))
          @php $waNum = preg_replace('/[^0-9]/', '', $settings->whatsapp); @endphp
          <a href="https://wa.me/{{ $waNum }}" target="_blank" rel="noopener noreferrer" class="d-flex align-items-center gap-1 text-success fw-bold">
            <i class="fa-brands fa-whatsapp"></i>
            <span>واتساب الاستقدام</span>
          </a>
        @endif

        @if(!empty($settings?->address))
          <span class="text-white-50">|</span>
          <span class="small">
            <i class="fa-solid fa-location-dot text-danger me-1"></i>
            {{ $settings->address }}
          </span>
        @endif
      </div>
    </div>
  </div>
</div>
