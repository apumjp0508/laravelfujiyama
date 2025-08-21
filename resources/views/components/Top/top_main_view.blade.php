@push('styles')
  <link rel="stylesheet" href="{{ asset('css/top-page-css/top-page.css') }}">
@endpush

  <div class="daigo_production-container">
    <div class="page-overlay">
        <h1>CMART OFFICIAL</h1>
    </div>
    <div class="logoDiv">
      <img src="{{ asset('img/top-logo.png') }}" class="logo">
    </div>
    
    <div class="daigo_production-wrap daigo_production1" id="wrap1"><img src="{{ asset('img/star-badge.png') }}" class="daigo_production" id="daigo_production1"></div>
    <div class="daigo_production-wrap daigo_production2" id="wrap2"><img src="{{ asset('img/logo-badge.png') }}" class="daigo_production" id="daigo_production2"></div>
    <div class="daigo_production-wrap daigo_production3" id="wrap3"><img src="{{ asset('img/like-earth-badge.png') }}" class="daigo_production" id="daigo_production3"></div>
    <div class="daigo_production-wrap daigo_production4" id="wrap4"><img src="{{ asset('img/pink-and-blue-badge.png') }}" class="daigo_production" id="daigo_production4"></div>
    <div class="daigo_production-wrap daigo_production5" id="wrap5"><img src="{{ asset('img/yossy-badge.png') }}" class="daigo_production" id="daigo_production5"></div>
  </div>
  <script src="{{ asset('js/top-page-js/badgeAnimetion.js') }}"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>