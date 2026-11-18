@extends('layouts.site.app')

@section('title', 'شركة هلا النيل لإلحاق العمالة بالخارج | عقود وتأشيرات عمل إيطاليا الرسمية')

@section('content')
  {{-- 1. Hero Slider Section --}}
  @include('Site.parts.hero')

  {{-- 2. Live Statistics Section --}}
  @include('Site.parts.statistics')

  {{-- 3. About Company & Official Licensing --}}
  @include('Site.parts.about')

  {{-- 4. Why Choose Us (Guarantees & Features) --}}
  @include('Site.parts.why-us')

  {{-- 5. Jobs & Contracts Section (Interactive Filter & Search) --}}
  @include('Site.parts.jobs')

  {{-- 6. Timeline & Steps to Italy (Decreto Flussi Roadmap) --}}
  @include('Site.parts.steps')

  {{-- 7. Travelers in Italy Gallery & Success Stories --}}
  @include('Site.parts.travelers')

  {{-- 8. Interactive Contract & Visa Tracker Section --}}
  @include('Site.parts.tracker')

  {{-- 9. FAQ Section (Frequently Asked Questions) --}}
  @include('Site.parts.faq')

  {{-- 10. Official Warning Banner --}}
  @include('Site.parts.warning-banner')

  {{-- 11. Contact Us Section (Form & Info Cards) --}}
  @include('Site.parts.contact')

  {{-- 12. Location Map Section --}}
  @include('Site.parts.map')
@endsection
