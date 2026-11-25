<!-- ==========================================================================
     Main Footer
     ========================================================================== -->
<footer class="main-footer">
  <div class="container">
    <div class="row g-4">
      <!-- Col 1: About & Logo -->
      <div class="col-lg-4 col-md-6">
        <img src="{{ asset('Site/assets/images/logo-white.svg') }}" alt="هلا النيل لإلحاق العمالة بالخارج" height="60" class="mb-3">
        <p class="text-white-50 small leading-relaxed mb-4">
          شركة هلا النيل لإلحاق العمالة المصرية بالخارج. ترخيص وزارة القوى العاملة رقم (912 شركات). التخصص الأقوى والأشمل في عقود العمل الرسمية بإيطاليا وتسهيل كافة إجراءات الإقامة والتأشيرة.
        </p>
        <div class="d-flex align-items-center gap-2">
          @if(!empty($settings?->whatsapp))
            @php $waNum = preg_replace('/[^0-9]/', '', $settings->whatsapp); @endphp
            <x-site.footer-social-link href="https://wa.me/{{ $waNum }}" icon="fa-brands fa-whatsapp" label="واتساب" />
          @endif
          @if(!empty($settings?->facebook))
            <x-site.footer-social-link :href="$settings->facebook" icon="fa-brands fa-facebook-f" label="فيسبوك" />
          @endif
          @if(!empty($settings?->twitter))
            <x-site.footer-social-link :href="$settings->twitter" icon="fa-brands fa-x-twitter" label="تويتر" />
          @endif
          @if(!empty($settings?->youtube))
            <x-site.footer-social-link :href="$settings->youtube" icon="fa-brands fa-youtube" label="يوتيوب" />
          @endif
          @if(!empty($settings?->instagram))
            <x-site.footer-social-link :href="$settings->instagram" icon="fa-brands fa-instagram" label="إنستغرام" />
          @endif
        </div>
      </div>

      <!-- Col 2: Quick Links -->
      <div class="col-lg-2 col-md-6 col-6">
        <h5 class="footer-title">روابط سريعة</h5>
        <ul class="footer-links">
          <x-site.footer-link href="#hero" label="الرئيسية" />
          <x-site.footer-link href="#about" label="من نحن وتراخيصنا" />
          <x-site.footer-link href="#jobs" label="الوظائف المتاحة" />
          <x-site.footer-link href="#steps" label="مراحل السفر" />
          <x-site.footer-link href="#travelers" label="مسافرونا بإيطاليا" />
          <x-site.footer-link href="#tracker" label="تتبع حالة العقد" />
        </ul>
      </div>

      <!-- Col 3: Legal Sectors in Italy -->
      <div class="col-lg-3 col-md-6 col-6">
        <h5 class="footer-title">قطاعات العمل بإيطاليا</h5>
        <ul class="footer-links">
          <x-site.footer-link href="#jobs" icon="fa-solid fa-utensils" label="المطاعم والمطابخ الإيطالية" />
          <x-site.footer-link href="#jobs" icon="fa-solid fa-seedling" label="الزراعة ومزارع العنب والزيتون" />
          <x-site.footer-link href="#jobs" icon="fa-solid fa-trowel-bricks" label="المقاولات والبناء والتشييد" />
          <x-site.footer-link href="#jobs" icon="fa-solid fa-truck-moving" label="النقل اللوجستي والشاحنات" />
          <x-site.footer-link href="#jobs" icon="fa-solid fa-hotel" label="الضيافة والمنتجعات السياحية" />
          <x-site.footer-link href="#jobs" icon="fa-solid fa-wrench" label="الصيانة الكهربائية والميكانيكية" />
        </ul>
      </div>

      <!-- Col 4: Contact & Ministry Verification -->
      <div class="col-lg-3 col-md-6">
        <h5 class="footer-title">بيانات الترخيص المعتمد</h5>
        <p class="small text-white-50 mb-2">
          <x-site.footer-info-item label="جهة الترخيص" value="وزارة القوى العاملة المصرية" />
          <x-site.footer-info-item label="رقم الترخيص" value="912 شركات فئة (أ)" />
          @if(!empty($settings?->commercial_register))
            <x-site.footer-info-item label="السجل التجاري" :value="$settings->commercial_register" />
          @endif
          @if(!empty($settings?->address))
            <x-site.footer-info-item label="العنوان" :value="$settings->address" />
          @endif
        </p>
        <div class="p-2 rounded-2 bg-white bg-opacity-10 border border-white border-opacity-10 mt-3 text-center small text-light">
          <i class="fa-solid fa-shield-halved text-success me-1"></i> مصنف بالقائمة البيضاء للشركات القانونية
        </div>
      </div>
    </div>

    <!-- Copyright Bottom Bar -->
    <div class="footer-bottom d-flex flex-wrap justify-content-between align-items-center">
      <p class="mb-0 text-white-50">
        جميع الحقوق محفوظة © {{ date('Y') }} لشركة <strong>هلا النيل لإلحاق العمالة المصرية بالخارج</strong>.
      </p>
      <div class="d-flex gap-3 small text-white-50 mt-2 mt-md-0">
        <span>تصميم مخصص فائق الأداء</span>
        <span>•</span>
        <span>إشراف قانوني معتمد</span>
      </div>
    </div>
  </div>
</footer>
