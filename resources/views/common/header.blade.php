<div id="header" class="makent-header cls_nhead {{@$header_class}} new {{ (!isset($exception)) ? (Route::current()->uri() == '/' ? 'shift-with-hiw' : '') : '' }}">
  <header class="header--sm show-sm" aria-hidden="true" role="banner">
    <a href="javascript:void(0);" aria-label="Homepage" data-prevent-default="" class="link-reset burger--sm header-logo" style="background-image: url('{{ url(LOGO_URL) }}'); background-size: 80px;">
     <!--  <i class="fa fa-bars lang-chang-label arrow-icon1" style="font-size:21px;padding:5px;color: #ff5a5f; left: -25px;"></i> -->
     <i class="fa fa-angle-down lang-chang-label arrow-icon mbl_nav"></i>
     <span class="screen-reader-only">
      {{ $site_name }}
    </span>
  </a>
  <div class="title--sm text-center">
   <button class="btn btn-block search-btn--sm search-modal-trigger ser_mobtab">
    <i class="icon icon-search icon-gray"></i>
    <span class="search-placeholder--sm">
      {{ trans('messages.header.where_are_you_going') }}
    </span>
  </button>
</div>
<div class="action--sm"></div>
<nav role="navigation" class="nav--sm"><div class="nav-mask--sm"></div>
  <div class="nav-content--sm panel text-white">
    <div class="nav-header {{ (Auth::user()) ? '' : 'items-logged-in' }}">
      @php $current_user = Route::current()->parameter('id') == @Auth::user()->id; @endphp
      <div class="nav-profile clearfix {{(!isset($exception)) ?((Route::current()->uri() == 'users/show/{id}' && $current_user)?'active':''):''}}">
        <div class="user-item pull-left">
          <a href="{{ url('/') }}/users/show/{{ (Auth::user()) ? Auth::user()->id : '0' }}" class="link-reset user-profile-link">
            <div class="media-photo media-round user-profile-image" style="background:rgba(0, 0, 0, 0) url({{ (Auth::user()) ? Auth::user()->profile_picture->header_src : '' }}) no-repeat scroll 0 0 / cover">
            </div>
            {{ (Auth::user()) ? Auth::user()->first_name : 'User' }}
          </a>
        </div>
      </div>
      <hr>
    </div>
    <div class="nav-menu-wrapper">
      <div class="va-container va-container-v va-container-h">
        <div class=" nav-menu panel-body">
          <ul class="menu-group list-unstyled row-space-3">
            <li class="{{(!isset($exception)) ?(Route::current()->uri() == '/'?'active':''):''}}">
              <a rel="nofollow" class="link-reset menu-item" href="{{ url('/') }}">
                {{ trans('messages.header.home') }}
              </a>
            </li>

            <li aria-hidden="true"><hr class="divider_1qenmrf"></li>
            <li class="{{ (Auth::user()) ? 'items-logged-out' : '' }} {{(!isset($exception)) ?(Route::current()->uri() == 'rooms/new'?'active':''):''}}">
              <a rel="nofollow" class="link-reset menu-item" href="{{ url('rooms/new') }}">
               {{ trans('messages.header.head_homes') }}
             </a>
           </li>
           {{--HostExperienceBladeCommentStart--}}
           <li class="{{ (Auth::user()) ? 'items-logged-out' : '' }} {{(!isset($exception)) ?(Route::current()->uri() == 'host/experiences'?'active':''):''}}">
            <a rel="nofollow" class="link-reset menu-item" href="{{ url('host/experiences') }}">
             {{ trans('messages.header.head_experience') }}
           </a>
         </li>
         {{--HostExperienceBladeCommentEnd--}}
         <li class="{{ (Auth::user()) ? 'items-logged-out' : '' }} {{(!isset($exception)) ?(Route::current()->uri() == 'signup_login'?'active':''):''}}">
          <a rel="nofollow" class="link-reset menu-item " href="{{ url('/') }}/signup_login" data-signup-modal="">
            {{ trans('messages.header.signup') }}
          </a>
        </li>
        <li class="{{ (Auth::user()) ? 'items-logged-out' : '' }} {{(!isset($exception)) ?(Route::current()->uri() == 'login'?'active':''):''}}">
          <a rel="nofollow" class="link-reset menu-item " href="{{ url('/') }}/login" data-login-modal="">
            {{ trans('messages.header.login') }}
          </a>
        </li>
        <li class="{{ (Auth::user()) ? 'items-logged-out' : '' }}" aria-hidden="true"><hr class="divider_1qenmrf"></li>

        <li class="{{ (Auth::user()) ? '' : 'items-logged-in' }} {{(!isset($exception)) ?(Route::current()->uri() == 'dashboard'?'active':''):''}}">
          <a href="{{ url('dashboard') }}" rel="nofollow" class="no-crawl link-reset menu-item item-user-edit">
            {{ trans('messages.header.dashboard') }}
          </a>
        </li>

        <li class="{{ (Auth::user()) ? '' : 'items-logged-in' }} {{(!isset($exception)) ?((Route::current()->uri() == 'users/edit' || Route::current()->uri() == 'users/reviews' || Route::current()->uri() == 'users/edit/media' || Route::current()->uri() == 'users/edit_verification')?'active':''):''}}">
          <a href="{{ url('users/edit') }}" rel="nofollow" class="no-crawl link-reset menu-item item-user-edit">
           {{ trans('messages.header.profile') }}
         </a>
         <div class="child_1g2dfiu-o_O-child_alignMiddle_13gjrqj"  style="float: right;
         padding: 10px;"><svg viewBox="0 0 24 24" role="presentation" aria-hidden="true" focusable="false" style="display: block; fill: currentcolor; height: 26px; width: 26px;"><path fill-rule="evenodd"></path></svg></div>
       </li>

       <li class="{{ (Auth::user()) ? '' : 'items-logged-in' }} {{(!isset($exception)) ?((Route::current()->uri() == 'account' || Route::current()->uri() == 'users/security' || Route::current()->uri() == 'users/payout_preferences/{id}' || Route::current()->uri() == 'users/transaction_history')?'active':''):''}}">
        <a href="{{ url('account') }}" rel="nofollow" class="no-crawl link-reset menu-item item-user-edit">
         {{ trans('messages.header.account') }}
       </a>
     </li>

     <li class="{{ (Auth::user()) ? '' : 'items-logged-in' }} {{(!isset($exception)) ?((Route::current()->uri() == 'trips/current' || Route::current()->uri() == 'trips/previous')?'active':''):''}}">
      <a class="link-reset menu-item" rel="nofollow" href="{{ url('/') }}/trips/current">
       {{ trans('messages.header.Trips') }}
     </a>
     <div class="child_1g2dfiu-o_O-child_alignMiddle_13gjrqj"  style="float: right;
     padding: 10px;"><svg viewBox="0 0 24 24" role="presentation" aria-hidden="true" focusable="false" style="display: block; fill: currentcolor; height: 26px; width: 26px;"><path fill-rule="evenodd"></path></svg></div>
   </li>
   <li class="{{ (Auth::user()) ? '' : 'items-logged-in' }} {{(!isset($exception)) ?((Route::current()->uri() == 'inbox' || Route::current()->uri() == 'z/q/{id}' || Route::current()->uri() == 'messaging/qt_with/{id}')?'active':''):''}}">
    <a class="link-reset menu-item" rel="nofollow" href="{{ url('/') }}/inbox">
      {{ trans_choice('messages.dashboard.message', 2) }}
      @if(Auth::check())
         <span ng-init="inbox_count='{{ @Auth::user()->inbox_count()}}'">
           <i class="alert-count unread-count--sm text-center @{{ inbox_count != '0' ? '' : 'fade' }}">
          @{{ inbox_count }}
        </i>
         </span>
         @else
      <i class="alert-count unread-count--sm fade text-center">
        0
      </i>
      @endif
    </a>
    <div class="child_1g2dfiu-o_O-child_alignMiddle_13gjrqj" style="float: right;
    padding: 10px;"><svg viewBox="0 0 24 24" role="presentation" aria-hidden="true" focusable="false" style="display: block; fill: currentcolor; height: 26px; width: 26px;"><path fill-rule="evenodd"></path></svg></div>
  </li>
  @if(@Auth::user()->wishlists)
  <li class="{{ (Auth::user()) ? '' : 'items-logged-in' }} {{(!isset($exception)) ?(Route::current()->uri() == 'wishlists/my'?'active':''):''}}">
    <a href="{{ url('wishlists/my') }}" class="link-reset menu-item" rel="nofollow">
      {{ trans_choice('messages.header.wishlist',2) }}
    </a>
    <div class="child_1g2dfiu-o_O-child_alignMiddle_13gjrqj"  style="float: right;
    padding: 10px;"><svg viewBox="0 0 32 32" fill="currentColor" fill-opacity="0" stroke="currentColor" stroke-width="1.5" aria-hidden="true" role="presentation" stroke-linecap="round" stroke-linejoin="round" style="height: 26px; width: 26px; display: block;"><path ></path></svg></div>
  </li>
  @endif

  <li class="{{(!isset($exception)) ?(Route::current()->uri() == 'invite'?'active':''):''}}">
    <a href="{{ url('invite') }}" class="link-reset menu-item">
      {{ trans('messages.referrals.travel_credit') }}
      <span class="label label-pink label-new">
      </span>
    </a>
  </li>

  <li class="{{ (Auth::user()) ? '' : 'items-logged-in' }} {{(!isset($exception)) ?((Route::current()->uri() == 'rooms' || Route::current()->uri() == 'my_reservations')?'active':''):''}}">
    <a href="{{ url('rooms') }}" class="link-reset menu-item" rel="nofollow">
      {{ trans_choice('messages.header.your_listing', 2) }}
    </a>
  </li>
  <li class="{{ (Auth::user()) ? '' : 'items-logged-in' }} {{(!isset($exception)) ?((Route::current()->uri() == 'disputes' )?'active':''):''}}">
    <a href="{{ url('disputes') }}" class="link-reset menu-item">{{ trans('messages.disputes.disputes') }}
    </a>
  </li>
  <li  class="{{ (Auth::user()) ? '' : 'items-logged-in' }}" aria-hidden="true"><hr class="divider_1qenmrf"></li>

  <li  class="{{ (Auth::user()) ? '' : 'items-logged-in' }} {{(!isset($exception)) ?(Route::current()->uri() == 'rooms/new'?'active':''):''}}">
    <a rel="nofollow" class="link-reset menu-item" href="{{ url('rooms/new') }}">
     {{ trans('messages.header.head_homes') }}
   </a>
 </li>
 {{--HostExperienceBladeCommentStart--}}
 <li class="{{ (Auth::user()) ? '' : 'items-logged-in' }} {{(!isset($exception)) ?(Route::current()->uri() == 'host/experiences'?'active':''):''}}">
  <a rel="nofollow" href="{{ url('host/experiences') }}" rel="nofollow" class="no-crawl link-reset menu-item item-listing padding-left">
    {{ trans('messages.header.head_experience') }}
  </a>
</li>
{{--HostExperienceBladeCommentEnd--}}
<li  class="{{ (Auth::user()) ? '' : 'items-logged-in' }}" aria-hidden="true"><hr class="divider_1qenmrf"></li>


<li>
  <a rel="nofollow" class="link-reset menu-item js-show-how-it-works {{(!isset($exception)) ? (Route::current()->uri() == '/' ? '' : 'hide') : ''}}" href="javascript:void(0);" >
   {{ trans('messages.home.how_it_works') }}
 </a>
</li>
</ul>

<ul class="menu-group list-unstyled row-space-3 mb_scroll">
  <li class=" {{(!isset($exception)) ?(Route::current()->uri() == 'help'?'active':''):''}}">
    <a class="link-reset menu-item" rel="nofollow" href="{{ url('/') }}/help">
      {{ trans('messages.header.help') }}
    </a>
  </li>
  <li class="{{ (Auth::user()) ? '' : 'items-logged-in' }}">
    <a class="link-reset menu-item logout" rel="nofollow" href="{{ url('/') }}/logout">
      {{ trans('messages.header.logout') }}
    </a>
  </li>
</ul>
</div>
</div>
</div>
</div>
</nav>
<div class="search-modal-container moched">
  <div class="modal hide" role="dialog" id="search-modal--sm" aria-hidden="false" tabindex="-1">
    <div class="modal-table">
      <div class="modal-cell">
        <div class="modal-content">
          <div class="panel-header modal-header">
            <a href="#" class="modal-close" data-behavior="modal-close">
              <span class="screen-reader-only">{{ trans('messages.home.close') }}</span>
              <span class="aria-hidden"></span>
            </a>
            {{ trans('messages.home.search') }}
          </div>
          <div class="panel-body">
            <div class="modal-search-wrapper--sm">
              <input type="hidden" name="source" value="mob">
              <div class="row">
                <div class="searchbar__location-error hide" style="left:22px; top:2%;">{{ trans('messages.home.search_validation') }}</div>
                <div class="col-sm-12">
                  <label for="header-location--sm">
                    <span class="screen-reader-only">{{ trans('messages.header.where_are_you_going') }}</span>
                    <input type="text" placeholder="{{ trans('messages.header.where_are_you_going') }}" autocomplete="off" name="location" id="header-search-form-mob" class="input-large" value="{{ @$location }}">
                  </label>
                </div>
              </div>
              @if(Request::segment(1) != 's')
              <div class="row row-condensed">
                <div class="col-sm-6">
                  <label class="checkin">
                    <span class="screen-reader-only">{{ trans('messages.home.checkin') }}</span>
                    <input type="text" readonly="readonly" onfocus="this.blur()" autocomplete="off" name="checkin" id="modal_checkin" class="checkin input-large ui-datepicker-target" placeholder="{{ trans('messages.home.checkin') }}" value="{{ @$checkin }}">
                  </label>
                </div>
                <div class="col-sm-6">
                  <label class="checkout">
                    <span class="screen-reader-only">{{ trans('messages.home.checkout') }}</span>
                    <input type="text" readonly="readonly" onfocus="this.blur()" autocomplete="off" name="checkout" id="modal_checkout" class="checkout input-large ui-datepicker-target" placeholder="{{ trans('messages.home.checkout') }}" value="{{ @$checkout }}">
                  </label>
                </div>
              </div>
              <div class="row space-2 space-top-1">
                <div class="col-sm-12">
                  <label for="header-search-guests" class="screen-reader-only">
                    {{ trans('messages.home.no_of_guests') }}
                  </label>
                  <div class="select select-block select-large">
                    <select id="modal_guests" name="guests--sm">
                      @for($i=1;$i<=16;$i++)
                      <option value="{{ $i }}" {{ ($i == @$guest) ? 'selected' : '' }}>{{ $i }} {{ ($i>1) ? trans_choice('messages.home.guest',$i) : trans_choice('messages.home.guest',$i) }}</option>
                      @endfor
                    </select>
                  </div>
                </div>
              </div>
              @endif
              {{--HostExperienceBladeCommentStart--}}
              @if(Request::segment(1) != 's')
              <div class="panel-new-header menu-header normal-line-height st_menuu">
                <strong>{{ trans('messages.referrals.explore') }}</strong>
                <ul class="header_refinement_ul">
                  <input type="hidden" name="header_refinement" class="header_refinement_input" value="Homes">
                  <li><button class="header_refinement in_form active" data="Homes" type="button" id="ftb">{{ trans('messages.header.homes') }}</button></li>
                  <li><button class="header_refinement in_form" data="Experiences" type="button" id="ftb1">{{ trans_choice('messages.home.experience',1) }}</button></li>
                </ul>
              </div>
              @else
              <div class="panel-new-header menu-header normal-line-height st_menuu">
                <strong>{{ trans('messages.referrals.explore') }}</strong>
                <ul class="header_refinement_ul">
                  <input type="hidden" name="header_refinement" class="header_refinement_modal_input" value="Homes">
                  <li><button class="header_refinement_modal in_form active" data="Homes" type="button" id="ftb">{{ trans('messages.header.homes') }}</button></li>
                  <li><button class="header_refinement_modal in_form" data="Experiences" type="button" id="ftb1">{{ trans_choice('messages.home.experience',1) }}</button></li>
                </ul>
              </div>
              @endif
              {{--HostExperienceBladeCommentEnd--}}
              @if(Request::segment(1) != 's')
              <div class="home_de">
                <div class="home_pro">
                  <div class="home_check">
                    <strong>{{ trans('messages.header.room_type') }}</strong>
                    <!-- <div class="rom_ty" id="content-3"> -->
                      <div class="rom_ty">
                      @php $room_inc = 1 @endphp
                      @foreach($header_room_type as $row_room)
                      <div class="col-md-12 margin-top-10">
                        <div class="row">
                          <div class="holecheck">
                            <input type="checkbox" value="{{ @$row_room->id }}" id="room-type-{{ @$row_room->id }}" class="mob_room_type" {{@in_array($row_room->id, @$room_type_selected) ? 'checked' : ''}} />
                            <i class="icon-activities">
                              <img src="{{ $row_room->image_name }}">
                            </i>
                            <label class="search_check_label" for="room-type-{{ @$row_room->id }}">{{ @$row_room->name }}</label>
                          </div>
                        </div>
                      </div>
                      @php $room_inc++ @endphp
                      @endforeach
                    </div>
                  </div>
                </div>
                {{--HostExperienceBladeCommentStart--}}
                <div class="exp_cat" style="display:none">
                  <div class="home_check">
                    <strong>{{ trans('messages.home.category') }}</strong>
                    <!-- <div class="rom_ty" id="content-4"> -->
                      <div class="rom_ty">
                      @foreach($host_experience_category as $row_cat)
                      <div class="col-md-12 margin-top-10">
                        <div class="row">
                          <div class="holecheck">
                            <input type="checkbox" id="cat-type-{{ @$row_cat->id }}" value="{{ @$row_cat->id }}" class="mob_cat_type" {{@in_array($row_cat->id, @$cat_type_selected) ? 'checked' : ''}} />
                            <label class="search_check_label" for="cat-type-{{ @$row_cat->id }}">{{ @$row_cat->name }}</label>
                          </div>
                        </div>
                      </div>
                      @endforeach
                    </div>
                  </div>
                </div>
                {{--HostExperienceBladeCommentEnd--}}
              </div>
              <div class="row row-space-top-2">
                <div class="col-sm-12">
                  <button type="button" id="search-form--sm-btn" class="btn btn-primary btn-large btn-block">
                    <i class="icon icon-search"></i>
                    {{ trans('messages.header.find_place') }}
                  </button>
                </div>
              </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</header>


<header class="regular-header clearfix hide-sm bttm_border" id="old-header-hide" role="banner">

  <a aria-label="Homepage" style="background-image: url('{{ url(LOGO_URL) }}'); background-size: 125px;" href="{{ url('/') }}" class="header-belo header-logo pull-left {{ (!isset($exception)) ? (Route::current()->uri() == '/' ? 'home-logo' : '') : '' }}" >
    <span class="screen-reader-only">
      {{ $site_name }}
    </span>
  </a>
  <div class="pull-left search-input-home resp-ipod ne_hed">
    @if(Request::segment(1) != 'help')
    <ul class="nav pull-left  list-unstyled search-form-container" id="search-form-header">
      <li id="header-search" class="search-bar-wrapper pull-left medium-right-margin">
        <form action="{{ url('/') }}/s" class=" search-form @if(Request::segment(1) != 's') normal_header_form @else search_header_form @endif" >
          <div class="search-bar">
            <i class="icon icon-search icon-gray h4"></i>
            <label class="screen-reader-only" for="header-search-form">{{ trans('messages.header.where_are_you_going') }}</label>
            <input type="text" placeholder="{{ trans('messages.header.where_are_you_going') }}" autocomplete="off" name="location" id="header-search-form" class="location" value="">
            {{--HostExperienceBladeCommentStart--}}
            <div class="panel-new-header menu-header normal-line-height search_new_header">
              <strong>{{ trans('messages.referrals.explore') }}</strong>
              <ul class="header_refinement_ul">
                <input type="hidden" name="header_refinement" class="header_refinement_input" value="Homes">
                <li><button class="header_refinement active" data="Homes" type="button">{{ trans('messages.header.homes') }}</button>
                </li>
                <li><button class="header_refinement" data="Experiences" type="button">{{ trans_choice('messages.home.experience',1) }}</button></li>
              </ul>
            </div>
            {{--HostExperienceBladeCommentEnd--}}



          </div>
          <div id="header-search-settings" class="panel search-settings header-menu rem_hed">
            <div class="panel-body clearfix basic-settings">
              <div class="setting checkin lang-chang-label">
                <label for="header-search-checkin" class="field-label">
                  <strong>{{ trans('messages.home.checkin') }}</strong>
                </label>
                <input type="text" readonly="readonly" autocomplete="off" id="header-search-checkin" data-field-name="check_in_dates" class="checkin ui-datepicker-target" onfocus="this.blur()"  placeholder="{{ trans('messages.rooms.dd-mm-yyyy') }}">
                <input type="hidden" name="checkin">
              </div>

              <div class="setting checkout lang-chang-label">
                <label for="header-search-checkout" class="field-label">
                  <strong>{{ trans('messages.home.checkout') }}</strong>
                </label>
                <input type="text" readonly="readonly" autocomplete="off" id="header-search-checkout" data-field-name="check_out_dates" class="checkout ui-datepicker-target" onfocus="this.blur()"  placeholder="{{ trans('messages.rooms.dd-mm-yyyy') }}">
                <input type="hidden" name="checkout">
              </div>

              <div class="setting guests lang-chang-label">
                <label for="header-search-guests" class="field-label">
                  <strong>{{ trans_choice('messages.home.guest', 2) }}</strong>
                </label>
                <div class="select select-block">
                  <select id="header-search-guests" data-field-name="number_of_guests" name="guests">
                    @for($i=1;$i<=16;$i++)
                    <option value="{{ $i }}"> {{ ($i == '16') ? $i.'+ ' : $i }} </option>
                    @endfor
                  </select>
                </div>
              </div>
            </div>

 <!--  <div class="panel-header menu-header normal-line-height">
  <small>
  <strong>{{ trans('messages.header.room_type') }}</strong>
  </small>
</div> -->

<!--   <div class="panel-body">
  <div class="row-space-4">
  @foreach($header_room_type as $row)
  <label class="checkbox menu-item" for="header_room_type_{{ $row->id }}">
  <input type="checkbox" id="header_room_type_{{ $row->id }}" name="room_types[]" value="{{ $row->id }}">
  @if($row->id == 1)
  <i class="icon icon-entire-place horizontal-margin-medium"></i>
  @endif
  @if($row->id == 2)
  <i class="icon icon-private-room horizontal-margin-medium"></i>
  @endif
  @if($row->id == 3)
  <i class="icon icon-shared-room horizontal-margin-medium"></i>
  @endif

  @if($row->id >= 4)
  <i class="icon icon-home horizontal-margin-medium"></i>
  @endif
  <span>{{ $row->name }}</span>
  </label>
  @endforeach
  </div>
  <button type="submit" class="btn btn-primary btn-block">
  <i class="icon icon-search"></i>
  <span>{{ trans('messages.header.find_place') }}</span>
  </button>
</div> -->
{{--HostExperienceBladeCommentStart--}}
<div class="panel-new-header menu-header normal-line-height st_menuu">
  <strong>{{ trans('messages.referrals.explore') }}</strong>
  <ul class="header_refinement_ul">
    <input type="hidden" name="header_refinement" class="header_refinement_input" value="Homes">
    <li><button class="header_refinement active" data="Homes" type="button" id="ftbm">{{ trans('messages.header.homes') }}</button></li>
    <li><button class="header_refinement" data="Experiences" type="button" id="ftbm1">{{ trans_choice('messages.home.experience',1) }}</button></li>
  </ul>
</div>
{{--HostExperienceBladeCommentEnd--}}
<div class="home_de">
  <div class="home_pro">
    <div class="home_check">
      <strong>{{ trans('messages.header.room_type') }}</strong>
      <!-- <div class="rom_ty" id="content-1"> -->
          <div class="rom_ty" >
        @php $room_inc = 1 @endphp
        @foreach($header_room_type as $row_room)
        <div class="col-md-12 margin-top-10">
          <div class="row">
            <div class="holecheck">
              <input type="checkbox" value="{{ @$row_room->id }}" id="room-type-{{ @$row_room->id }}" class="head_room_type" {{@in_array($row_room->id, @$room_type_selected) ? 'checked' : ''}} />
              <i class="icon-activities">
                <img src="{{ $row_room->image_name }}">
              </i>
              <label class="search_check_label" for="room-type-{{ @$row_room->id }}">{{ @$row_room->name }}</label>
            </div>
          </div>
        </div>
        @php $room_inc++ @endphp
        @endforeach
      </div>
    </div>
  </div>
  {{--HostExperienceBladeCommentStart--}}
  <div class="exp_cat" style="display:none">
    <div class="home_check">
      <strong>{{ trans('messages.home.category') }}</strong>
      <!-- <div class="rom_ty" id="content-3"> -->
        <div class="rom_ty" >
        @foreach($host_experience_category as $row_cat)
        <div class="col-md-12 margin-top-10">
          <div class="row">
            <div class="holecheck">
              <input type="checkbox" id="cat-type-{{ @$row_cat->id }}" value="{{ @$row_cat->id }}" class="head_cat_type" {{@in_array($row_cat->id, @$cat_type_selected) ? 'checked' : ''}} />
              <label class="search_check_label" for="cat-type-{{ @$row_cat->id }}">{{ @$row_cat->name }}</label>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
  {{--HostExperienceBladeCommentEnd--}}
</div>
<div class="panel-new-body">
  <button type="submit" class="btn btn-primary btn-block">
    <i class="icon icon-search"></i>
    <span>{{ trans('messages.header.find_place') }}</span>
  </button>
</div>

</div>
</form>
</li>

<li class="dropdown-trigger pull-left large-right-margin hide-sm hide" data-behavior="recently-viewed__container">
  <a class="no-crawl link-reset" href="{{ url('/') }}/login?_ga=1.237472128.1006317855.1436675116#" data-href="/history#docked-filters" data-behavior="recently-viewed__trigger">
    <span class="show-lg recently-viewed__label">
      {{ trans('messages.header.recently_viewed') }}
    </span>
    <span class="hide-lg recently-viewed__label">
      <i class="icon icon-recently-viewed icon-gray h4"></i>
    </span>
    <i class="icon icon-caret-down icon-light-gray h5"></i>
  </a>
  <div class="tooltip tooltip-top-left dropdown-menu recently-viewed__dropdown" data-behavior="recently-viewed__dropdown">
  </div>
</li>
</ul>
@endif
</div>
<div class="pull-right resp-zoom">
  <ul class="nav pull-left help-menu list-unstyled">
    {{--HostExperienceBladeCommentStart--}}
    @if(!Auth::check())
    <li class="list-your-space pull-left">
      <a id="my_element"  class="header-become-host become {{ Auth::check() ? '' : 'login_popup_open' }}" href="javascript:void(0)" style="border-left:none !important;">
        <span id="list-your-space" class="btn btn-special list-your-space-btn btn_border_none">
          {{ trans('messages.header.list_your_space') }}
        </span>
      </a>
      <ul class="become_dropdown " style="display:none">
        <li>
          <a href="{{ url('rooms/new') }}" rel="nofollow" class="no-crawl link-reset menu-item item-dashboard padding-left">
            {{ trans('messages.header.head_homes') }}
          </a>
        </li>
        <li class="listings">
          <a href="{{ url('host/experiences') }}" rel="nofollow" class="no-crawl link-reset menu-item item-listing padding-left">
            {{ trans('messages.header.head_experience') }}
          </a>
        </li>
      </ul>
    </li>

    @endif
    @if(Auth::check())
    <li class="list-your-space pull-left">
      <a id="my_element"  class="header-become-host become {{ Auth::check() ? 'hostverification' : 'login_popup_open' }}" href="javascript:void(0)" style="border-left:none !important;">
        <span id="list-your-space" class="btn btn-special list-your-space-btn btn_border_none">
          {{ trans('messages.header.list_your_space') }}
        </span>
      </a>
      <ul class="become_dropdown" style="display:none">
        <li>
          <a href="{{ url('rooms/new') }}" rel="nofollow" class="no-crawl link-reset menu-item item-dashboard padding-left">
            {{ trans('messages.header.head_homes') }}
          </a>
        </li>
        <li class="listings">
          <a href="{{ url('host/experiences') }}" rel="nofollow" class="no-crawl link-reset menu-item item-listing padding-left">
            {{ trans('messages.header.head_experience') }}
          </a>
        </li>
      </ul>
    </li>
    @endif
    {{--HostExperienceBladeCommentEnd--}}
    {{--HostExperienceBladeUnCommentStart
    <li class="list-your-space pull-left">
      <a id="my_element"  class="{{ Auth::check() ? '' : 'login_popup_open' }}" href="{{ url('rooms/new') }}" style="border-left:none !important;">
        <span id="list-your-space" class="btn btn-special list-your-space-btn btn_border_none">
          {{ trans('messages.header.list_your_space') }}
        </span>
      </a>
    </li>
    HostExperienceBladeUnCommentEnd--}}

    @if(!Auth::check())
    <li id="header-help-menu" class="help-menu-container pull-left dropdown-trigger">
      <a class="help-toggle link-reset btn_border_none font-color" href="{{ url('help') }}">
        {{ trans('messages.header.help') }}
      </a>
    </li>
    @endif
  </ul>
  @if(!Auth::check())
  <ul class="nav pull-left logged-out list-unstyled">
    <li id="sign_up" class="pull-left font-color">
      <a data-signup-modal="" data-header-view="true" href="{{ url('signup_login') }}" data-redirect="" class="link-reset signup_popup_head">
        {{ trans('messages.header.signup') }}
      </a>
    </li>
    <li id="login" class="pull-left font-color">
      <a data-login-modal="" href="{{ url('login') }}" data-redirect="" class="link-reset login_popup_head">
        {{ trans('messages.header.login') }}
      </a>
    </li>
  </ul>
  @endif

  @if(Auth::check())
  <ul class="nav pull-left list-unstyled msg-wish">
    <li class="pull-left dropdown-trigger">
      <a class="link-reset trip-drop" href="{{ url('trips/current') }}">
        <span class="trip-pos"> {{ trans('messages.header.Trips') }}</span>
        <i class="trips-icon">
          <i class="alert-count fade">0</i>
        </i>
      </a>
      <div class="panel drop-down-menu-trip hide js-become-a-host-dropdown">
        <div class="trip-width">
          <div class="panel-header no-border section-header-home" ><strong><span>Trips</span></strong><a href="{{ url('trips/current') }}" class="link-reset view-trips pull-right"><strong><span>View Trips</span></strong></a></div>
          <div class="pull-left" style="width:100%;padding:15px 20px;">
            <div class="pull-left" style="padding:15px 20px 0px;">
              <strong>No upcoming trips</strong>
              <p>continue searching in paris</p>
            </div>
            <div class="pull-right suitcase-icon">
              <i class="icon icon-suitcase"></i>
            </div>
          </div>
          <div class="panel-header no-border section-header-home pull-left" style="width:100%;" ><strong><span>Wishlist</span></strong><a href="{{ url('wishlists/my') }}" class="link-reset view-trips pull-right"><strong><span>View Wishlists</span></strong></a></div>
          <div class="pull-left" style="width:100%;padding:15px 20px;">
            <div class="pull-left" style="padding:15px 20px 0px;">
              <strong>No wish list created</strong>
              <p>create a wish list</p>
            </div>
            <div class="pull-right suitcase-icon">
              <i class="icon icon-heart-alt"></i>
            </div>
          </div>
        </div>
      </div>
    </li>
    <li id="inbox-item" class="inbox-item pull-left dropdown-trigger js-inbox-comp" ng-init="inbox_count='{{ @Auth::user()->inbox_count()}}'">
      <a href="{{ url('inbox') }}" rel="nofollow" class="no-crawl link-reset inbox-icon">

       <span class="" style="position:relative;" > {{ trans_choice('messages.dashboard.message', 2) }}
        <i ng-cloak class="alert-count text-center @{{ inbox_count != '0' ? '' : 'fade' }}">@{{ inbox_count }}</i>
      </span>
    </a>
    <div class="tooltip tooltip-top-right dropdown-menu list-unstyled header-dropdown
    notifications-dropdown hide">
  </div>
  <div class="panel drop-down-menu-msg hide js-become-a-host-dropdown">
    <div class="trip-width">
      <div class="panel-header no-border section-header-home" ><strong><span>Messages</span></strong><a href="{{ url('inbox') }}" class="link-reset view-trips pull-right"><strong><span>View Inbox</span></strong></a></div>

      <div class="panel-header no-border section-header-home pull-left" style="width:100%;" ><strong><span>Notifications</span></strong><a href="{{ url('dashboard') }}" class="link-reset view-trips pull-right"><strong><span>View Dashboard</span></strong></a></div>
      <div class="pull-left" style="width:100%;padding:15px 20px;">

        <p style="margin:0px;padding-top:10px !important;"> There are 3 notifications waiting for you in your <a style="color:#333;text-decoration:underline;" href="{{ url('dashboard') }}">  {{ trans('messages.header.dashboard') }} </a>.</p>

      </div>
    </div>
  </div>
</li>
<li id="header-help-menu" class="help-menu-container pull-left dropdown-trigger">
  <a class=" link-reset" href="{{ url('help') }}">
    <span class="help-pos">{{ trans('messages.header.help') }}</span>
    <input type="hidden" name="value" id='host_verification' value="{{ Auth::user()->verification_status }}">
    <input type="hidden" name="value" id='hostname' value="{{ Auth::user()->first_name.' '.Auth::user()->last_name }}">
    <input type="hidden" name="value" id='hostreason' value="{{ Auth::user()->id_resubmit_reason }}">
    <!-- <i class="help-icon"></i> -->
  </a>
</li>
</ul>


<ul class="nav pull-left list-unstyled" role="navigation">
  <li class="user-item pull-left medium-right-margin dropdown-trigger">
    <a class="link-reset header-avatar-trigger " id="header-avatar-trigger" href="{{ url('login') }}">
      <span class="value_name">
        {{ Auth::user()->first_name }}
      </span>
      <div class="media-photo media-round user-profile-image" style="background: rgba(0, 0, 0, 0) url({{ Auth::user()->profile_picture->header_src }}) no-repeat scroll 0 0 / cover" ></div>
      <!--<i class="icon icon-caret-down icon-light-gray h5"></i>-->
    </a>
    <ul class="tooltip tooltip-top-right dropdown-menu list-unstyled header-dropdown drop-down-menu-login">
      <li>
        <a href="{{ url('dashboard') }}" rel="nofollow" class="no-crawl link-reset menu-item item-dashboard padding-left">
          {{ trans('messages.header.dashboard') }}
        </a>
      </li>
      <li class="listings">
        <a href="{{ url('rooms') }}" rel="nofollow" class="no-crawl link-reset menu-item item-listing padding-left">
          <span class="plural">
            {{ trans_choice('messages.header.your_listing',2) }}
          </span>
        </a>
      </li>
      <li class="reservations" style="display: none;">
        <a href="{{ url('my_reservations') }}" rel="nofollow" class="no-crawl link-reset menu-item item-reservation padding-left">
         {{ trans('messages.header.your_reservations') }}
       </a>
     </li>
     <li style="display: none;">
      <a href="{{ url('trips/current') }}" rel="nofollow" class="no-crawl link-reset menu-item item-trips padding-left">
        {{ trans('messages.header.your_trips') }}
      </a>
    </li>
    @if(Auth::user()->wishlists)
    <li>
      <a href="{{ url('wishlists/my') }}" id="wishlists" class="no-crawl link-reset menu-item item-wishlists padding-left">
        {{ trans_choice('messages.header.wishlist',2) }}
      </a>
    </li>
    @endif
    <li class="groups hide">
      <a href="{{ url('groups') }}" rel="nofollow" class="no-crawl link-reset menu-item item-groups padding-left">
        {{ trans('messages.header.groups') }}
      </a>
    </li>
    <li>
      <a href="{{ url('invite') }}" class="no-crawl link-reset menu-item item-invite-friends padding-left">
        {{ trans('messages.referrals.travel_credit') }}
        <span class="label label-pink label-new">
        </span>
      </a>
    </li>
    <li>
      <a href="{{ url('users/edit') }}" rel="nofollow" class="no-crawl link-reset menu-item item-user-edit padding-left">
       {{ trans('messages.header.edit_profile') }}
     </a>
   </li>
   <li>
    <a href="{{ url('account') }}" rel="nofollow" class="no-crawl link-reset menu-item item-account padding-left">
      {{ trans('messages.header.account') }}
    </a>
  </li>
  <li class="business-travel hide">
    <a href="{{ url('business') }}" rel="nofollow" class="no-crawl link-reset menu-item item-business-travel padding-left">
      {{ trans('messages.header.business_travel') }}
    </a>
  </li>
  <li>
    <a href="{{ url('logout') }}" rel="nofollow" class="no-crawl link-reset menu-item header-logout padding-left">
      {{ trans('messages.header.logout') }}
    </a>
  </li>
</ul>
</li>
</ul>
@endif
</div>
</header>
</div>

<div class="flash-container">
  @if(Session::has('message') && !isset($exception))
  @if((!Auth::check() || Route::current()->uri() == 'rooms/{id}' || Route::current()->uri() == 'rooms/new' || Route::current()->uri() == 'payments/book/{id?}'|| Request::segment(1) == 'host' || Request::segment(1) == 'experiences' || Route::current()->uri() == 's') )
  <div class="alert {{ Session::get('alert-class') }}" role="alert">
    <a href="#" class="alert-close" data-dismiss="alert"></a>
    {{ Session::get('message') }}
  </div>
  @endif
  @endif
</div>

<div class="login-close">
  <div class="login_popup">
    <div class="page-container-responsive page-container-auth margintop">
      <div class="row">
        <div class="log_pop col-center">
          <div class="panel top-home">
            <!-- <div class="login-close">
            <img src="images/close.png">
          </div>-->
            <!-- <div class="log-ash-head">
            Log In
          </div> -->
          <div class="panel-body pad-25 bor-none padd1 ">
            <i class="icon-remove-1 rm_lg"></i>

            <a href="{{ $fb_url }}" class="fb-button fb-blue btn icon-btn btn-block btn-large row-space-1 btn-facebook font-normal pad-top">
              <span ><i class="icon icon-facebook"></i></span>
              <span >{{ trans('messages.login.login_with')}} Facebook</span>
            </a>

            <a href="javascript:;" id="pop_google_login" class="google_login btn icon-btn btn-block btn-large row-space-1 btn-google font-normal pad-top mr1">
              <span ><i class="icon icon-google-plus"></i></span>
              <span >{{ trans('messages.login.login_with')}} Google</span>
            </a>

                <!--  Hided LinkedIn
                <a href="{{URL::to('auth/linkedin')}}" class="li-button li-blue btn icon-btn btn-block btn-large row-space-1 btn-linkedin mr1">
                  <span ><i class="icon icon-linkedin"></i></span>
                  <span >{{ trans('messages.login.login_with')}} LinkedIn</span>
                </a> -->

                <div class="signup-or-separator">
                  <span class="h6 signup-or-separator--text">{{ trans('messages.login.or')}}</span>  <hr>
                </div>

                <div class="clearfix"></div>

                <form method="POST" action="{{ url('authenticate') }}" accept-charset="UTF-8" class="signup-form login-form ng-pristine ng-valid" data-action="Signin" novalidate="true"><input name="_token" type="hidden">

                  <input id="login_from" name="from" type="hidden" value="email_login">

                  <div class="control-group row-space-2 field_ico">
                   @if ($errors->has('login_email')) <p class="help-block">{{ $errors->first('login_email') }}</p> @endif
                   <div class="pos_rel">
                     <i class="icon-envelope"></i>
                     <input class="{{ $errors->has('login_email') ? 'decorative-input inspectletIgnore invalid' : 'decorative-input inspectletIgnore name-icon' }}"  placeholder="{{ trans('messages.login.email_address') }}" id="signin_email" name="login_email" type="email" value="">
                   </div>
                 </div>

                 <div class="control-group row-space-3 field_ico">
                   @if ($errors->has('login_password')) <p class="help-block">{{ $errors->first('login_password') }}</p> @endif
                   <div class="pos_rel">
                    <i class="icon-lock"></i>
                    <input class="{{ $errors->has('login_password') ? 'decorative-input inspectletIgnore invalid' : 'decorative-input inspectletIgnore name-icon' }}" placeholder="{{ trans('messages.login.password') }}" id="signin_password" data-hook="signin_password" name="login_password" type="password" value="">
                  </div>
                </div>

                <div class="clearfix row-space-3">
                  <label for="remember_me2" class="checkbox remember-me">
                    <input id="remember_me2" class="remember_me" name="remember_me" type="checkbox" value="1"> {{ trans('messages.login.remember_me')}}
                  </label>
                  <a href="" class="forgot-password forgot-password-popup link_color pull-right h5">{{ trans('messages.login.forgot_pwd')}}</a>
                </div>

                <input class="btn btn-primary btn-block btn-large pad-top btn_new" id="user-login-btn" type="submit" value="{{ trans('messages.header.login') }}">
              </form>
            </div>
            <div class="panel-body bottom-panel1 text-center">  <hr>
              {{ trans('messages.login.dont_have_account')}}
              <a href="" class="link-to-signup-in-login login-btn link_color signup_popup_head">
                {{ trans('messages.header.signup')}} </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="login-close">
    <div class="signup_popup">
      <div class="page-container-responsive page-container-auth margintop">
        <div class="row">

          <div class="log_pop col-center">
            <div class="panel top-home">
          <!--<div class="login-close">
          <img src="images/close.png">
        </div>-->
        
        <div class="panel-padding panel-body pad-25 padd1">
          <i class="icon-remove-1 rm_lg"></i>
          <div class="social-buttons">
            <a href="{{ $fb_url }}" class="fb-button fb-blue btn icon-btn btn-block row-space-1 btn-large btn-facebook pad-23" data-populate_uri="" data-redirect_uri="{{URL::to('/')}}/authenticate">
              <span >
                <i class="icon icon-facebook"></i>
              </span>
              <span >
                {{ trans('messages.login.signup_with') }} Facebook
              </span>
            </a>
            <a href="javascript:;" id="pop_google_signup_login" class="btn icon-btn btn-block row-space-1 btn-large btn-google pad-23">
              <span >
                <i class="icon icon-google-plus"></i>
              </span>
              <span >
                {{ trans('messages.login.signup_with') }} Google
              </span>
            </a>
        <!-- Hided LinkedIn
              <a href="{{URL::to('auth/linkedin')}}" class="li-button li-blue btn icon-btn btn-block btn-large row-space-1 btn-linkedin">
                  <span >
                    <i class="icon icon-linkedin"></i>
                  </span>
                  <span >
                    {{ trans('messages.login.signup_with') }} LinkedIn
                  </span>
                </a>
              -->
            </div>

            <div class="text-center social-links hide">
              {{ trans('messages.login.signup_with') }} <a href="{{ $fb_url }}" class="facebook-link-in-signup">Facebook</a> {{ trans('messages.login.or') }} <a href="javascript:;" id="pop_google_signup_login">Google</a>
            </div>

            <div class="signup-or-separator">
              <span class="h6 signup-or-separator--text">{{ trans('messages.login.or') }}</span>
              <hr>
            </div>

            <div class="text-center">
              <a href="" class="create-using-email btn-block  row-space-2 btn btn-primary btn-block btn-large large icon-btn pad-23 signup_popup_head2 btn_new1" id="create_using_email_button">
                <span >
                  <i class="icon icon-envelope"></i>
                </span>
                <span >
                  {{ trans('messages.login.signup_with') }} {{ trans('messages.login.email') }}
                </span>
              </a>
            </div>

            <div id="tos_outside" class="row-space-top-3">
              <small class="small-font style1">
                {{ trans('messages.login.signup_agree') }} {{ $site_name }}'s
                @foreach($company_pages as $company_page)
                <a href="{{ url($company_page->url) }}" class="link_color" data-popup="true">,{{ $company_page->name }}</a>
                @endforeach  
              </small>
            </div>
          </div>

          <div class="panel-body bottom-panel1 text-center">
            <hr>
            {{ trans('messages.login.already_an') }} {{ $site_name }} {{ trans('messages.login.member') }}
            <a href="{{ url('login') }}" class="modal-link link-to-login-in-signup login-btn login_popup_head link_color" data-modal-href="/login_modal?" data-modal-type="login">
              {{ trans('messages.header.login') }}
            </a>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
</div>
<div class="login-close">
  <div class="signup_popup2">
    <div class="page-container-responsive page-container-auth margintop">
      <div class="row">
        <div class="log_pop col-center">
          <div class="panel top-home bor-none">
<!--  <div class="login-close">
<img src="images/close.png">
</div>-->
            <!-- <div class="alert alert-with-icon alert-error alert-header panel-header hidden-element notice" id="notice">
              <i class="icon alert-icon icon-alert-alt"></i>
            </div>
            <div class="log-ash-head">{{ trans('messages.header.signup') }}</div> -->
            <div class="pad-25 panel-body pad-25 bor-none padd1 clearfix">
              <i class="icon-remove-1 rm_lg"></i>

              <p class="text-center mr_non">{{ trans('messages.login.signup_with') }}

                <a href="{{ $fb_url }}" data-populate_uri="" data-redirect_uri="{{URL::to('/')}}/authenticate" class="link_color"> Facebook</a>
                or
                <a href="javascript:;" id="google_email_login" class="link_color">Google</a>

              <!-- Hided LinkedIn
              <a href="{{URL::to('auth/linkedin')}}" class="link_color">LinkedIn </a> -->
            </p>

            <div class="signup-or-separator">
              <span class="h6 signup-or-separator--text">{{ trans('messages.login.or') }}</span>
              <hr>
            </div>

            <div class="clearfix"></div>

            {!! Form::open(['action' => 'UserController@create', 'class' => 'signup-form', 'data-action' => 'Signup', 'id' => 'user_new', 'accept-charset' => 'UTF-8' , 'novalidate' => 'true']) !!}
            <div class="signup-form-fields">
              {!! Form::hidden('from', 'email_signup', ['id' => 'signup_from']) !!}
              <div class="control-group row-space-2 field_ico" id="inputFirst">
                @if ($errors->has('first_name')) <p class="help-block">{{ $errors->first('first_name') }}</p> @endif
                <div class="pos_rel">
                  <i class="icon-users"></i>
                  {!! Form::text('first_name', '', ['class' =>  $errors->has('first_name') ? 'decorative-input invalid ' : 'decorative-input name-icon input_new', 'placeholder' => trans('messages.login.first_name')]) !!}
                </div>
              </div>
              <div class="control-group row-space-2 field_ico" id="inputLast">
                @if ($errors->has('last_name')) <p class="help-block">{{ $errors->first('last_name') }}</p> @endif
                <div class="pos_rel">
                  <i class="icon-users"></i>
                  {!! Form::text('last_name', '', ['class' => $errors->has('last_name') ? 'decorative-input inspectletIgnore invalid' : 'decorative-input inspectletIgnore name-icon input_new', 'placeholder' => trans('messages.login.last_name')]) !!}
                </div>
              </div>
              <div class="control-group row-space-2 field_ico" id="inputEmail">
                @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
                <div class="pos_rel">
                  <i class="icon-envelope"></i>
                  {!! Form::email('email', '', ['class' => $errors->has('email') ? 'decorative-input inspectletIgnore invalid' : 'decorative-input inspectletIgnore name-mail name-icon input_new', 'placeholder' => trans('messages.login.email_address')]) !!}
                </div>
              </div>
              <div class="control-group row-space-2 field_ico" id="inputPassword">
                @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
                <div class="pos_rel">
                  <i class="icon-lock"></i>
                  {!! Form::input('password','password',old('password'), ['class' => $errors->has('password') ? 'decorative-input inspectletIgnore invalid' : 'decorative-input inspectletIgnore name-pwd name-icon input_new', 'placeholder' => trans('messages.login.password'), 'id' => 'user_password', 'data-hook' => 'user_password']) !!}
                </div>
                <div data-hook="password-strength" class="password-strength hide"></div>
              </div>
              <div class="control-group row-space-top-3 row-space-2 ">
                <p class="h4 row-space-1">{{ trans('messages.login.birthday') }}</p>
                <p class="let_sp">{{  trans('messages.login.birthday_message') }}</p>
              </div>
              <div class="control-group row-space-1 " id="inputBirthday"></div>
              @if ($errors->has('birthday_month') || $errors->has('birthday_day') || $errors->has('birthday_year')) <p class="help-block"> {{ $errors->has('birthday_day') ? $errors->first('birthday_day') : ( $errors->has('birthday_month') ? $errors->first('birthday_month') : $errors->first('birthday_year') ) }} </p> @endif
              <div class="control-group row-space-2 calander_new">
                <div class="select month drp_dwn_cng">
                  <i class="icon-chevron-down"></i>
                  {!! Form::selectMonthWithDefault('birthday_month', null, trans('messages.header.month'), [ 'class' => $errors->has('birthday_month') ? 'invalid' : '', 'id' => 'user_birthday_month']) !!}
                </div>
                <div class="select day month drp_dwn_cng">
                  <i class="icon-chevron-down"></i>
                  {!! Form::selectRangeWithDefault('birthday_day', 1, 31, null, trans('messages.header.day'), [ 'class' => $errors->has('birthday_day') ? 'invalid' : '', 'id' => 'user_birthday_day']) !!}
                </div>
                <div class="select month drp_dwn_cng">
                  <i class="icon-chevron-down"></i>
                  {!! Form::selectRangeWithDefault('birthday_year', date('Y'), date('Y')-120, null, trans('messages.header.year'), [ 'class' => $errors->has('birthday_year') ? 'invalid' : '', 'id' => 'user_birthday_year']) !!}
                </div>
              </div>
              <div class="clearfix"></div>
              <div id="tos_outside" class="row-space-top-3 chk-box">
                <div class="dis_tb">
                  <div class="dis_cell">
                    <input type="checkbox" ng-model="checked">
                  </div><!-- dis_cell end -->
                  <div class="dis_cell">
                    <small>
                      {{ trans('messages.login.signup_agree') }} {{ $site_name }}'s 
                      @foreach($company_pages as $company_page)
                      <a href="{{ url($company_page->url) }}" data-popup="true">,{{ $company_page->name }}</a>
                      @endforeach 
                    </small>
                  </div><!-- dis_cell end -->
                </div><!-- dis_tb end -->


              </div>
            </div>
            {!! Form::submit( trans('messages.header.signup'), ['class' => 'btn btn-primary btn-block btn-large pad-top' , 'id' => 'user-signup-btn', 'ng-disabled'=>'!checked'])  !!}
            {!! Form::close() !!}
          </div>
          <div class="panel-body bottom-panel1 text-center ">
            <hr>
            {{ trans('messages.login.already_an') }} {{ $site_name }} {{ trans('messages.login.member') }}
            <a href="{{ url('login') }}" class="width-100 modal-link link-to-login-in-signup login-btn login_popup_head link_color " data-modal-href="/login_modal?" data-modal-type="login">
              {{ trans('messages.header.login') }}
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>


<div class="login-close">
  <div class="forgot-passward">
   <div class="page-container-responsive page-container-auth">
    <div class="row">
      <div class="log_pop col-center">
        <div class="panel top-home">
          {!! Form::open(['url' => url('forgot_password')]) !!}
          <div id="forgot_password_container" class="padd1">
            <i class="icon-remove-1 rm_lg"></i>
            <h3 class="mr_non row-space-3">
              <b>{{ trans('messages.login.reset_pwd') }}</b>
            </h3>
            <div class="hr1 row-space-3 space-top-4"></div>
            <div class="panel-padding row-space-3 mb_padd0">
              <p class="sz1 row-space-3">{{ trans('messages.login.reset_pwd_desc') }}</p>
              @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
              <div id="inputEmail" class="textInput text-input-container field_ico">
                {!! Form::email('email', '', ['placeholder' => trans('messages.login.email'), 'id' => 'forgot_email', 'class' => $errors->has('email') ? 'decorative-input inspectletIgnore invalid input_new' : 'decorative-input inspectletIgnore input_new']) !!}
                <i class="icon-envelope"></i>
              </div>
            </div>
            <div class="clearfix">
              <a href="#" class="bck_btn"> <i class="icon-chevron-left"></i> Back to Login</a>
              <button id="reset-btn" class="btn btn-primary sub_btn1" type="submit">
                {{ trans('messages.login.send_reset_link') }}
              </button>
            </div>
          </div>
          {!! Form::close() !!}
        </div>
      </div>

    </div>
  </div>
</div>
</div>


<div class="verification-close">
  <div class="verification">
   <div class="page-container-responsive page-container-auth">
    <div class="row">
      <div class="log_pop col-center">
        <div class="panel top-home">
          <div class="padd1">
            <i class="icon-remove-1 rm_lg"></i>
            <div class="panel-padding row-space-3 mb_padd0" style="padding: 0px;">
              <p class="sz1 row-space-3" id='verify_details'>
              </p>
            </div>
            <div class="clearfix">
              <a id="verification-btn" href="{{ url('users/edit_verification') }}" class="btn btn-primary" type="submit">Ok</a>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
</div>