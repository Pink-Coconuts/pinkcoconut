 <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ url('admin_assets/dist/img/avatar04.png') }}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{ Auth::guard('admin')->user()->username }}</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">

        <li class="header">MAIN NAVIGATION</li>

        <li class="{{ (Route::current()->uri() == ADMIN_URL.'/dashboard') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/dashboard') }}"><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>

        @if(Auth::guard('admin')->user()->can('manage_admin'))
          <li class="treeview {{ (Route::current()->uri() == ADMIN_URL.'/admin_users' || Route::current()->uri() == ADMIN_URL.'/roles' || Route::current()->uri() == ADMIN_URL.'/permissions') ? 'active' : ''  }}">
          <a href="#">
            <i class="fa fa-user-plus"></i> <span>Manage Admin</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="{{ (Route::current()->uri() == ADMIN_URL.'/admin_users') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/admin_users') }}"><i class="fa fa-circle-o"></i><span>Admin Users</span></a></li>
            <li class="{{ (Route::current()->uri() == ADMIN_URL.'/roles') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/roles') }}"><i class="fa fa-circle-o"></i><span>Roles & Permissions</span></a></li>
          </ul>
          </li>
        @endif

        @if(Auth::guard('admin')->user()->can('users'))
          <li class="{{ (Route::current()->uri() == ADMIN_URL.'/users') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/users') }}"><i class="fa fa-users"></i><span>Manage Users</span></a></li>
        @endif

        @if(Auth::guard('admin')->user()->can('rooms'))
          <li class="{{ (Route::current()->uri() == ADMIN_URL.'/rooms') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/rooms') }}"><i class="fa fa-home"></i><span>Manage Rooms</span></a></li>
        @endif
{{--HostExperienceBladeCommentStart--}}
        @include('admin/host_experiences/menu_navigation')
        {{--HostExperienceBladeCommentEnd--}}
        @if(Auth::guard('admin')->user()->can('reservations'))
          <li class="treeview {{ (Route::current()->uri() == ADMIN_URL.'/reservations' || Route::current()->uri() == ADMIN_URL.'/host_penalty') ? 'active' : ''  }}">
          <a href="#">
            <i class="fa fa-plane"></i> <span>Reservations & Penalty</span><i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="{{ (Route::current()->uri() == ADMIN_URL.'/reservations') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/reservations') }}"><i class="fa fa-circle-o"></i><span>Reservations</span></a></li>
              <li class="{{ (Route::current()->uri() == ADMIN_URL.'/host_penalty') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/host_penalty') }}"><i class="fa fa-circle-o"></i><span>Host Penalty</span></a></li>
          </ul>
          </li>
        @endif

        @if(Auth::guard('admin')->user()->can('manage_disputes'))
          <li class="{{ (Route::current()->uri() == '{ADMIN_URL}/disputes') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/disputes') }}"><i class="fa fa-hand-peace-o"></i><span>Manage Disputes</span></a></li>          
        @endif
        
        @if(Auth::guard('admin')->user()->can('email_settings') || Auth::guard('admin')->user()->can('send_email'))
          <li class="treeview {{ (Route::current()->uri() == ADMIN_URL.'/email_settings' || Route::current()->uri() == ADMIN_URL.'/send_email') ? 'active' : ''  }}">
          <a href="#">
            <i class="fa fa-envelope-o"></i> <span>Manage Emails</span><i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            @if(Auth::guard('admin')->user()->can('send_email'))
            <li class="{{ (Route::current()->uri() == ADMIN_URL.'/send_email') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/send_email') }}"><i class="fa fa-circle-o"></i><span>Send Email</span></a></li>
            @endif
            @if(Auth::guard('admin')->user()->can('email_settings'))
              <li class="{{ (Route::current()->uri() == ADMIN_URL.'/email_settings') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/email_settings') }}"><i class="fa fa-circle-o"></i><span>Email Settings</span></a></li>
            @endif
          </ul>
          </li>
        @endif

        @if(Auth::guard('admin')->user()->can('manage_reviews'))
          <li class="{{ (Route::current()->uri() == ADMIN_URL.'/reviews') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/reviews') }}"><i class="fa fa-eye"></i><span>Reviews</span></a></li>
        @endif
        
        @if(Auth::guard('admin')->user()->can('manage_referrals'))
        <li class="{{ (Route::current()->uri() == ADMIN_URL.'/referrals') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/referrals') }}"><i class="fa fa-group"></i><span>Referrals</span></a></li>
        @endif

        @if(Auth::guard('admin')->user()->can('manage_wishlists'))
          <li class="{{ (Route::current()->uri() == ADMIN_URL.'/wishlists') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/wishlists') }}"><i class="fa fa-heart"></i><span>Wish Lists</span></a></li>
        @endif

        @if(Auth::guard('admin')->user()->can('manage_coupon_code'))
          <li class="{{ (Route::current()->uri() == ADMIN_URL.'/coupon_code') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/coupon_code') }}"><i class="fa fa-ticket"></i><span>Manage Coupon Code</span></a></li>
        @endif

        @if(Auth::guard('admin')->user()->can('reports'))
          <li class="{{ (Route::current()->uri() == ADMIN_URL.'/reports') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/reports') }}"><i class="fa fa-file-text-o"></i><span>Reports</span></a></li>
        @endif

        @if(Auth::guard('admin')->user()->can('manage_home_cities'))
          <li class="{{ (Route::current()->uri() == ADMIN_URL.'/home_cities') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/home_cities') }}"><i class="fa fa-globe"></i><span>Manage Home Cities</span></a></li>
        @endif

        @if(Auth::guard('admin')->user()->can('manage_home_page_sliders'))
          <li class="{{ (Route::current()->uri() == ADMIN_URL.'/slider') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/slider') }}"><i class="fa fa-image"></i><span>Manage Home Page Sliders</span></a></li>
        @endif
        @if(Auth::guard('admin')->user()->can('manage_home_page_bottom_sliders'))
          <li class="{{ (Route::current()->uri() == ADMIN_URL.'/bottom_slider') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/bottom_slider') }}"><i class="fa fa-image"></i><span>Home Page Bottom Sliders</span></a></li>
        @endif

        @if(Auth::guard('admin')->user()->can('manage_our_community_banners'))
          <li class="{{ (Route::current()->uri() == ADMIN_URL.'/our_community_banners') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/our_community_banners') }}"><i class="fa fa-image"></i><span>Manage Our Communities</span></a></li>
        @endif

        @if(Auth::guard('admin')->user()->can('manage_host_banners'))
          <li class="{{ (Route::current()->uri() == ADMIN_URL.'/host_banners') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/host_banners') }}"><i class="fa fa-image"></i><span>Manage Host Banners</span></a></li>
        @endif

         @if(Auth::guard('admin')->user()->can('manage_hotel_partners'))
          <li class="{{ (Route::current()->uri() == ADMIN_URL.'/features') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/features') }}"><i class="fa fa-image"></i><span>Manage Features</span></a></li>
        @endif


        @if(Auth::guard('admin')->user()->can('manage_help'))
          <li class="treeview {{ (Route::current()->uri() == ADMIN_URL.'/help' || Route::current()->uri() == ADMIN_URL.'/help_category' || Route::current()->uri() == ADMIN_URL.'/help_subcategory') ? 'active' : ''  }}">
          <a href="#">
            <i class="fa fa-support"></i> <span>Manage Help</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="{{ (Route::current()->uri() == ADMIN_URL.'/help') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/help') }}"><i class="fa fa-circle-o"></i><span>Help</span></a></li>
            <li class="{{ (Route::current()->uri() == ADMIN_URL.'/help_category') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/help_category') }}"><i class="fa fa-circle-o"></i><span>Category</span></a></li>
            <li class="{{ (Route::current()->uri() == ADMIN_URL.'/help_subcategory') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/help_subcategory') }}"><i class="fa fa-circle-o"></i><span>Subcategory</span></a></li>
          </ul>
          </li>
        @endif

        @if(Auth::guard('admin')->user()->can('manage_amenities'))
          <li class="treeview {{ (Route::current()->uri() == ADMIN_URL.'/amenities' || Route::current()->uri() == ADMIN_URL.'/amenities_type') ? 'active' : ''  }}">
          <a href="#">
            <i class="fa fa-bullseye"></i> <span>Manage Amenities</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="{{ (Route::current()->uri() == ADMIN_URL.'/amenities') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/amenities') }}"><i class="fa fa-circle-o"></i><span>Amenities</span></a></li>
            <li class="{{ (Route::current()->uri() == ADMIN_URL.'/amenities_type') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/amenities_type') }}"><i class="fa fa-circle-o"></i><span>Amenities Type</span></a></li>
          </ul>
          </li>
        @endif

        @if(Auth::guard('admin')->user()->can('manage_property_type'))
          <li class="{{ (Route::current()->uri() == ADMIN_URL.'/property_type') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/property_type') }}"><i class="fa fa-building"></i><span>Manage Property Type</span></a></li>
        @endif

        @if(Auth::guard('admin')->user()->can('manage_room_type'))
          <li class="{{ (Route::current()->uri() == ADMIN_URL.'/room_type') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/room_type') }}"><i class="fa fa-home"></i><span>Manage Room Type</span></a></li>
        @endif

        @if(Auth::guard('admin')->user()->can('manage_bed_type'))
          <li class="{{ (Route::current()->uri() == ADMIN_URL.'/bed_type') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/bed_type') }}"><i class="fa fa-hotel"></i><span>Manage Bed Type</span></a></li>
        @endif

        @if(Auth::guard('admin')->user()->can('manage_pages'))
          <li class="{{ (Route::current()->uri() == ADMIN_URL.'/pages') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/pages') }}"><i class="fa fa-newspaper-o"></i><span>Manage Static Pages</span></a></li>
        @endif

        @if(Auth::guard('admin')->user()->can('manage_currency'))
          <li class="{{ (Route::current()->uri() == ADMIN_URL.'/currency') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/currency') }}"><i class="fa fa-dollar"></i><span>Manage Currency</span></a></li>
        @endif

        @if(Auth::guard('admin')->user()->can('manage_language'))
          <li class="{{ (Route::current()->uri() == ADMIN_URL.'/language') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/language') }}"><i class="fa fa-language"></i><span>Manage Language</span></a></li>
        @endif

        @if(Auth::guard('admin')->user()->can('manage_country'))
          <li class="{{ (Route::current()->uri() == ADMIN_URL.'/country') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/country') }}"><i class="fa fa-globe"></i><span>Manage Country</span></a></li>
        @endif

        @if(Auth::guard('admin')->user()->can('manage_referral_settings'))
          <li class="{{ (Route::current()->uri() == ADMIN_URL.'/referral_settings') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/referral_settings') }}"><i class="fa fa-users"></i><span>Manage Referrals Settings</span></a></li>
        @endif

        @if(Auth::guard('admin')->user()->can('manage_fees'))
          <li class="{{ (Route::current()->uri() == ADMIN_URL.'/fees') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/fees') }}"><i class="fa fa-dollar"></i><span>Manage Fees</span></a></li>
        @endif

        @if(Auth::guard('admin')->user()->can('manage_ngofee'))
          <li class="{{ (Route::current()->uri() == ADMIN_URL.'/ngofees') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/ngofees') }}"><i class="fa fa-dollar"></i><span>Manage Ngo Fees</span></a></li>
        @endif

        @if(Auth::guard('admin')->user()->can('manage_metas'))
          <li class="{{ (Route::current()->uri() == ADMIN_URL.'/metas') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/metas') }}"><i class="fa fa-bar-chart"></i><span>Manage Metas</span></a></li>
        @endif

        @if(Auth::guard('admin')->user()->can('api_credentials'))
          <li class="{{ (Route::current()->uri() == ADMIN_URL.'/api_credentials') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/api_credentials') }}"><i class="fa fa-facebook"></i><span>Api Credentials</span></a></li>
        @endif

        @if(Auth::guard('admin')->user()->can('payment_gateway'))
          <li class="{{ (Route::current()->uri() == ADMIN_URL.'/payment_gateway') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/payment_gateway') }}"><i class="fa fa-paypal"></i><span>Payment Gateway</span></a></li>
        @endif

        @if(Auth::guard('admin')->user()->can('join_us'))
          <li class="{{ (Route::current()->uri() == ADMIN_URL.'/join_us') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/join_us') }}"><i class="fa fa-share-alt"></i><span>Join Us Links</span></a></li>
        @endif

        @if(Auth::guard('admin')->user()->can('site_settings'))
          <!-- <li class="{{ (Route::current()->uri() == ADMIN_URL.'/theme_settings') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/theme_settings') }}"><i class="fa fa-eye"></i><span>Theme Settings</span></a></li> -->
          <li class="{{ (Route::current()->uri() == ADMIN_URL.'/site_settings') ? 'active' : ''  }}"><a href="{{ url(ADMIN_URL.'/site_settings') }}"><i class="fa fa-gear"></i><span>Site Settings</span></a></li>
        @endif

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>