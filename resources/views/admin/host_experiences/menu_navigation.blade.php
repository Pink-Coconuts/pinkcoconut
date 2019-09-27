@if(Auth::guard('admin')->user()->can('manage_host_experiences') || Auth::guard('admin')->user()->can('manage_host_experience_cities') || Auth::guard('admin')->user()->can('manage_host_experience_categories') || Auth::guard('admin')->user()->can('manage_host_experience_provide_items') || Auth::guard('admin')->user()->can('manage_host_experiences_reservation') || Auth::guard('admin')->user()->can('manage_host_experiences_reviews') ||
Auth::guard('admin')->user()->can('manage_host_experiences_inquiries'))
<li class="treeview {{@$host_experience_menu ? 'active' : ''}}">
  <a href="#">
    <i class="fa fa-star-o"></i> <span>Host Experiences</span> <i class="fa fa-angle-left pull-right"></i>
  </a>
  <ul class="treeview-menu">
    @if(Auth::guard('admin')->user()->can('manage_host_experiences'))
    <li class="{{@$host_experience_menu == 'host_experiences' ? 'active' : ''}}">
      <a href="{{ url(ADMIN_URL.'/host_experiences') }}">
        <i class="fa fa-circle-o"></i><span>Host Experiences</span>
      </a>
    </li>
    @endif
    @if(Auth::guard('admin')->user()->can('manage_host_experience_cities'))
    <li class="{{@$host_experience_menu == 'host_experience_cities' ? 'active' : ''}}">
      <a href="{{ url(ADMIN_URL.'/host_experience_cities') }}">
        <i class="fa fa-circle-o"></i><span>Host Experience Cities</span>
      </a>
    </li>
    @endif
    @if(Auth::guard('admin')->user()->can('manage_host_experience_categories'))
    <li class="{{@$host_experience_menu == 'host_experience_categories' ? 'active' : ''}}">
      <a href="{{ url(ADMIN_URL.'/host_experience_categories') }}">
        <i class="fa fa-circle-o"></i><span>Host Experience Categories</span>
      </a>
    </li>
    @endif
    @if(Auth::guard('admin')->user()->can('manage_host_experience_provide_items'))
    <li class="{{@$host_experience_menu == 'host_experience_provide_items' ? 'active' : ''}}">
      <a href="{{ url(ADMIN_URL.'/host_experience_provide_items') }}">
        <i class="fa fa-circle-o"></i><span>Host Experience Provide Items</span>
      </a>
    </li>
    @endif
    @if(Auth::guard('admin')->user()->can('manage_host_experiences_reservation'))
    <li class="{{@$host_experience_menu == 'host_experiences_reservation' ? 'active' : ''}}">
      <a href="{{ url(ADMIN_URL.'/host_experiences_reservation') }}">
        <i class="fa fa-circle-o"></i><span>Host Experience Reservations</span>
      </a>
    </li>
    @endif
    @if(Auth::guard('admin')->user()->can('manage_host_experiences_reviews'))
    <li class="{{@$host_experience_menu == 'host_experiences_reviews' ? 'active' : ''}}">
      <a href="{{ url(ADMIN_URL.'/host_experiences_reviews') }}">
        <i class="fa fa-circle-o"></i><span>Host Experience Reviews</span>
      </a>
    </li>
    @endif
    @if(Auth::guard('admin')->user()->can('manage_host_experiences_inquiries'))
    <li class="{{@$host_experience_menu == 'host_experiences_inquiries' ? 'active' : ''}}">
      <a href="{{ url(ADMIN_URL.'/host_experiences_inquiries') }}">
        <i class="fa fa-circle-o"></i><span>Host Experience Inquiries</span>
      </a>
    </li>
    @endif
  </ul>
</li>
@endif