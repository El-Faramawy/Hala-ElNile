<!-- ==========================================================================
     Floating WhatsApp Button & Back To Top
     ========================================================================== -->
@if(!empty($settings?->whatsapp))
  @php $waNum = preg_replace('/[^0-9]/', '', $settings->whatsapp); @endphp
  <a href="https://wa.me/{{ $waNum }}?text=السلام%20عليكم،%20أرغب%20في%20معرفة%20تفاصيل%20عقود%20العمل%20المتاحة%20بإيطاليا%20لدى%20شركة%20هلا%20النيل" target="_blank" rel="noopener noreferrer" class="floating-whatsapp shadow-lg" title="تواصل عبر واتساب فوراً" aria-label="تواصل معنا عبر واتساب">
    <i class="fa-brands fa-whatsapp"></i>
    <span class="floating-whatsapp-badge">1</span>
  </a>
@endif

<a href="#" class="back-to-top" id="backToTop" title="العودة لأعلى الصفحة" aria-label="العودة لأعلى الصفحة">
  <i class="fa-solid fa-arrow-up"></i>
</a>
