<!-- ==========================================================================
     Live Statistics Section
     ========================================================================== -->
<section class="stats-section" id="statsSection">
  <div class="container">
    <div class="stats-strip reveal-on-scroll">
      <div class="row g-4 align-items-center">
        <!-- Stat 1 -->
        <x-site.stat-card
          icon="fa-solid fa-passport"
          iconClass="bg-soft-green text-success"
          :target="3850"
          plus="+"
          label="مسافر استلموا عقودهم وسافروا بنجاح"
        />

        <!-- Stat 2 -->
        <x-site.stat-card
          icon="fa-solid fa-award"
          iconClass="bg-primary-subtle text-primary"
          :target="100"
          plus="%"
          label="عقود حكومية وقانونية 100% موثقة"
        />

        <!-- Stat 3 -->
        <x-site.stat-card
          icon="fa-solid fa-calendar-check"
          iconClass="bg-warning-subtle text-warning"
          :target="14"
          plus="+ سنة"
          label="خبرة متخصصة حصرياً في ملف إيطاليا"
        />

        <!-- Stat 4 -->
        <x-site.stat-card
          icon="fa-solid fa-handshake-angle"
          iconClass="bg-danger-subtle text-danger"
          :target="85"
          plus="+"
          label="شريك وصاحب عمل إيطالي معتمد"
        />
      </div>
    </div>
  </div>
</section>
