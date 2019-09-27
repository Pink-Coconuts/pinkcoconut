<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<style>
  .goog-te-banner-frame.skiptranslate {
    display: none !important;
  } 
  body {
    top: 0px !important; 
  }
</style> 
<header class="main-header">
  <!-- Logo -->
  <a href="{{ url(ADMIN_URL.'/dashboard') }}" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><b>{{ $site_name }}</b></span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b>{{ $site_name }}</b></span>
  </a>

  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top" role="navigation" ng-controller="navigation">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>
    <span id="show_date_time" ng-init="format='{{PHP_DATE_FORMAT }}'" style="color:white; font-size:16px; padding-left:33%; line-height: 46px;"></span>

    <div class="navbar-custom-menu">
      <div id="google_translate_element" class="google-translate-element"></div>
      <ul class="nav navbar-nav">
        <input type="hidden" id="current_time" value="{{ date('F d, Y H:i:s', time()) }}">

        <!-- User Account: style can be found in dropdown.less -->
        <li class="dropdown user user-menu">

          <a href="#" class="dropdown-toggle" data-toggle="dropdown">

            <img src="{{ url('admin_assets/dist/img/avatar04.png') }}" class="user-image" alt="User Image">
            <span class="hidden-xs">{{ Auth::guard('admin')->user()->username }}</span>
          </a>
          <ul class="dropdown-menu">
            <!-- User image -->
            <li class="user-header">
              <img src="{{ url('admin_assets/dist/img/avatar04.png') }}" class="img-circle" alt="User Image">

              <p>
                {{ Auth::guard('admin')->user()->username }}
                <small>Member since {{ date('M. Y', strtotime(Auth::guard('admin')->user()->created_at)) }}</small>
              </p>
            </li>

            <!-- Menu Footer-->
            <li class="user-footer">
              <div class="pull-right">
                <a href="{{ url(ADMIN_URL.'/logout') }}" class="btn btn-default btn-flat">Sign out</a>
              </div>
            </li>
          </ul>
        </li>
        <!-- Control Sidebar Toggle Button -->
        <li>
          <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
        </li>
      </ul>
    </div>
  </nav>
</header>

<div class="flash-container">
  @if(Session::has('message'))
  <div class="alert {{ Session::get('alert-class') }} text-center" role="alert">
    <a href="#" class="alert-close" data-dismiss="alert">&times;</a>
    {{ Session::get('message') }}
  </div>
  @endif
</div>

<script type="text/javascript">
var lang = [] ,obj = {!! $lang!!},language ;
for(let i =0 ;i<obj.length;i++)
{
  lang.push(obj[i].value);
}
language = lang.join();
function googleTranslateElementInit() {
new google.translate.TranslateElement({pageLanguage: 'en',includedLanguages: language}, 'google_translate_element');
}
</script>

