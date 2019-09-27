<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0" />
<div class="manage-listing-header" id="js-manage-listing-header">
  <div class="subnav ml-header-subnav">
  <ul class="subnav-list ">
    <li class="show-if-collapsed-nav hide" id="collapsed-nav">
      <a href="" data-prevent-default="" class="subnav-item show-collapsed-nav-link" id="price-id">
        <i class="icon icon-reorder show-collapsed-nav-link--icon"></i>
        <span class="show-collapsed-nav-link--text">
          {{ trans('messages.lys.pricing_listing_details') }}…
        </span>
      </a>
    </li>
    <li class="subnav-text">
      <span id="listing-name">
      <span ng-hide="name">{{ ($result->name == '') ? $result->sub_name : $result->name }}</span>
      <span ng-show="name"><span ng-bind="name"></span></span>
      </span>
      <span class="see-all-listings-link">
        <span class="text-very-muted">(</span><span class="text-muted"><a href="{{ url('rooms') }}" class="text-normal link-underline">{{ trans('messages.lys.see_all_listings') }}</a></span><span class="text-very-muted">)</span>
      </span>
    </li>
    <li class="lang-left">
      <a href="{{ url('rooms/'.$room_id.'?preview') }}" data-track="preview" class="subnav-item pull-right" id="preview-btn" title="{{trans('messages.lys.preview_view')}}" target="_blank">

        <i class="icon icon-eye"></i>

        {{ ($result->status == NULL) ? trans('messages.lys.preview') : trans('messages.lys.view') }}

      </a>
    </li>
  </ul>
  <ul class="subnav-list has-collapsed-nav hide-md tespri">
    <li class="show-if-collapsed-nav" id="collapsed-nav">
      <a href="" data-prevent-default="" class="subnav-item show-collapsed-nav-link">
        <i class="icon icon-reorder show-collapsed-nav-link--icon"></i>
        <span class="show-collapsed-nav-link--text">
          {{ trans('messages.lys.pricing_listing_details') }}…
        </span>
      </a>
    </li>
    </ul>
</div>
</div>
