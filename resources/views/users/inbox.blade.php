@extends('template')
@section('main')
<main id="site-content" role="main" ng-controller="inbox">
  @include('common.subheader')
  <div class="page-container-responsive page-container-inbox space-4 space-top-4">
    <div id="inbox" class="threads" ng-cloak>
      <div class="panel">
        <div class="panel-header">
          <div class="row">
            <form accept-charset="UTF-8" action="" class="col-md-4" id="inbox_filter_form" method="get"><div style="margin:0;padding:0;display:inline"><input name="utf8" type="hidden" value="✓"></div>            <div class="select select-large select-block">
              <select id="inbox_filter_select" name="filter" ng-cloak>
                <option value="all" selected="selected" >{{ trans('messages.dashboard.all_messages') }} (@{{message_count.all_message_count}})</option>
                <option value="starred" >{{ trans('messages.inbox.starred') }} (@{{message_count.stared_count}})</option>
                <option value="unread">{{ trans('messages.inbox.unread') }} (@{{message_count.unread_count}})</option>
                <option value="reservations">{{ trans('messages.inbox.reservations') }} (@{{message_count.reservation_count}})</option>
                <!-- <option value="late_or_no_response">{{ trans('messages.inbox.late_or_noresponse') }} (0)</option> -->
                <option value="pending_requests">{{ trans('messages.inbox.pending_requests') }} (@{{message_count.pending_count}})</option>
                <option value="hidden">{{ trans('messages.inbox.archived') }} (@{{message_count.archived_count}})</option>
              <option value="admin_messages">{{ trans('messages.inbox.admin_messages') }} (@{{message_count.admin_messages}})</option>
             </select>
              </div>
              <input type="hidden" id="pagin_next" value= "{{ trans('messages.pagination.pagi_next') }} ">
              <input type="hidden" id="pagin_prev" value= "{{ trans('messages.pagination.pagi_prev') }} ">
            </form>          
            <div class="hide-sm col-md-8">
              <div class="pull-right">
              </div>
            </div>
          </div>
        </div>
        <input type="hidden" ng-model="user_id" ng-init="user_id = {{ $user_id }};">
        <ul id="threads" class="list-layout panel-body">
          <li id="thread_153062093" class="panel-body js-thread is-starred thread"  ng-repeat="all in message_result.data" ng-class="(all.read == '1') ? ' thread--read' : ''" ng-cloak>
            <div class="row inbox-page">
              <div class="col-sm-2 col-md-4 col-lg-3 lang-chang-label thread-author inbox_history1">
                <div class="row row-table">
                  <div class="thread-avatar col-md-5 lang-chang-label messages-content">
                    <!-- Message from Admin  -->
          <a ng-if="all.user_to == all.user_from" href="#"><img height="50" width="50" title="@{{ all.admin_name }}" src="{{ url('admin_assets/dist/img/avatar04.png') }}" class="media-round media-photo" alt="@{{ all.admin_name }}"></a>
          <!-- Message from Admin  -->
                    <a ng-if="all.user_to != all.user_from" href="{{ url('users/show/')}}/@{{ all.user_details.id  }}"><img title="@{{ all.user_details.first_name }}" ng-src=" @{{all.user_details.profile_picture.src }}" class="media-round media-photo" alt="@{{ all.user_details.first_name }}"></a>
                  </div>
                  <div class=" thread-name list_name1">
                    <span class="text_name3">  @{{ all.user_to == all.user_from ? all.admin_name : all.user_details.first_name }}</span>
                    <br>
                    <span class="thread-date">@{{ all.created_time }}</span>
                  </div>
                </div>
              </div>
              @{{all.reservation.rooms}}
              <div class="col-sm-7 col-md-5 col-lg-6 thread-body lang-chang-label inbox_history2" >
                <div class="thread-name list_name">
                  <span class="text_name3"> @{{ all.user_details.first_name }}</span>
                  <span class="thread-date">@{{ all.created_time }}</span>
                </div>
                <div class="common_inbox">
                  <a class="thread-link link-reset text-muted1" ng-show="all.host_check ==1 && all.reservation.status == 'Pending'"  href="{{ url('reservation')}}/@{{ all.reservation_id }}">
                    <span class="thread-subject text_prag" ng-class="(all.read == '1') ? '' : 'unread_message'">
                      @{{ all.message }}
                    </span>
                    <div class="text-muted show-lg show-sm" ng-if="all.user_to != all.user_from">
                      <span class="text_address">
                      <span ng-if="all.reservation.list_type == 'Rooms'">
                        <span class="street-address"  ng-if="all.rooms_address.address_line_1 || all.rooms_address.address_line_2 " ng-show="all.reservation.status == 'Accepted'">@{{ all.rooms_address.address_line_1 }} @{{ all.rooms_address.address_line_2 }},</span>
                        <span class="locality" ng-if= "all.rooms_address.city">@{{ all.rooms_address.city }}, </span>
                        <span class="region">@{{ all.rooms_address.state }}</span>
                      </span>
                      <span ng-if="all.reservation.list_type == 'Experiences'" >
                        <span class="street-address" ng-if="all.host_experience.host_experience_location.address_line_1 || all.host_experience.host_experience_location.address_line_2 " ng-show="all.reservation.status == 'Accepted'">@{{ all.host_experience.host_experience_location.address_line_1 }} @{{ all.host_experience.host_experience_location.address_line_2 }},</span>
                        <span class="locality" ng-if= "all.host_experience.host_experience_location.city">@{{ all.host_experience.host_experience_location.city }},</span>
                        <span class="region">@{{ all.host_experience.host_experience_location.state }}</span>
                      </span>
                      <span ng-hide="all.reservation.list_type == 'Experiences' && all.reservation.type == 'contact'">  
                        (@{{ all.reservation.checkinformatted  }} - @{{ all.reservation.checkoutformatted  }})
                      </span>
                    </span>
                      <span ng-if="all.inbox_thread_count > 1">
                        <i ng-cloak class="alert-count text-center inbox_message_count">@{{ all.inbox_thread_count }}</i>
                      </span>
                    </div>
                  </a>
                  <a class="thread-link link-reset text-muted1" ng-show="all.host_check ==1 && all.reservation.status != 'Pending'"  href="{{ url('messaging/qt_with')}}/@{{ all.reservation_id }}">
                    <span class="thread-subject text_prag" ng-class="(all.read == '1') ? '' : 'unread_message'">
                      @{{ all.message }}
                    </span>
                    <div class="text-muted show-lg show-sm" ng-if="all.user_to != all.user_from">
                      <span class="text_address">
                      <span ng-if="all.reservation.list_type == 'Rooms'" >
                        <span class="street-address" ng-if="all.rooms_address.address_line_1 || all.rooms_address.address_line_2 " ng-show="all.reservation.status == 'Accepted'">@{{ all.rooms_address.address_line_1 }} @{{ all.rooms_address.address_line_2 }},</span>
                        <span class="locality" ng-if= "all.rooms_address.city">@{{ all.rooms_address.city }},</span>
                        <span class="region">@{{ all.rooms_address.state }}</span>
                      </span>
                      <span ng-if="all.reservation.list_type == 'Experiences'" >
                        <span class="street-address" ng-if="all.host_experience.host_experience_location.address_line_1 || all.host_experience.host_experience_location.address_line_2 " ng-show="all.reservation.status == 'Accepted'">@{{ all.host_experience.host_experience_location.address_line_1 }} @{{ all.host_experience.host_experience_location.address_line_2 }},</span>
                        <span class="locality" ng-if= "all.host_experience.host_experience_location.city">@{{ all.host_experience.host_experience_location.city }},</span>
                        <span class="region">@{{ all.host_experience.host_experience_location.state }}</span>
                      </span>
                      <span  ng-hide="all.reservation.list_type == 'Experiences' && all.reservation.type == 'contact'"> 
                        (@{{ all.reservation.checkinformatted  }} - @{{ all.reservation.checkoutformatted  }})
                      </span>
                    </span>
                      <span ng-if="all.inbox_thread_count > 1">
                        <i ng-cloak class="alert-count text-center inbox_message_count">@{{ all.inbox_thread_count }}</i>
                      </span>
                    </div>
                  </a>
                  <a class="thread-link link-reset text-muted1" ng-show="all.guest_check" ng-if="all.user_to != all.user_from" href="{{ url('z/q')}}/@{{ all.reservation_id }}">
                    <span class="thread-subject text_prag" ng-class="(all.read == '1') ? '' : 'unread_message'">
                      @{{ all.message }}
                    </span>
                    <div class="text-muted show-lg show-sm" ng-if="all.user_to != all.user_from" ng-hide="all.reservation.list_type == 'Experiences' && all.reservation.type == 'contact'">
                      <span class="text_address">
                      <span ng-if="all.reservation.list_type == 'Rooms'" >
                        <span class="street-address" ng-if="all.rooms_address.address_line_1 || all.rooms_address.address_line_2 " ng-show="all.reservation.status == 'Accepted'">@{{ all.rooms_address.address_line_1 }} @{{ all.rooms_address.address_line_2 }},</span>
                        <span class="locality" ng-if= "all.rooms_address.city">@{{ all.rooms_address.city }},</span>
                        <span class="region">@{{ all.rooms_address.state }}</span>
                      </span>
                      <span ng-if="all.reservation.list_type == 'Experiences'" >
                        <span class="street-address" ng-if="all.host_experience.host_experience_location.address_line_1 || all.host_experience.host_experience_location.address_line_2 " ng-show="all.reservation.status == 'Accepted'">@{{ all.host_experience.host_experience_location.address_line_1 }} @{{ all.host_experience.host_experience_location.address_line_2 }},</span>
                        <span class="locality" ng-if= "all.host_experience.host_experience_location.city">@{{ all.host_experience.host_experience_location.city }},</span>
                        <span class="region">@{{ all.host_experience.host_experience_location.state }}</span>
                      </span>
                      <span ng-hide="all.reservation.list_type == 'Experiences' && all.reservation.type == 'contact'"> 
                        (@{{ all.reservation.checkinformatted }} - @{{ all.reservation.checkoutformatted }})
                      </span>
                    </span>
                      <span ng-if="all.inbox_thread_count > 1">
                        <i ng-cloak class="alert-count text-center inbox_message_count">@{{ all.inbox_thread_count }}</i>
                      </span>
                    </div>
                  </a>
                  <a  class="thread-link link-reset text-muted1" ng-show="all.guest_check" ng-if="all.user_to == all.user_from"  href="{{ url('admin_messages')}}/@{{ all.user_to }}">
      <span class="thread-subject text_prag" ng-class="(all.read == '1') ? '' : 'unread_message'">
           @{{ all.message }}
        </span>
    </a>
                </div>
                <div class="next_list">
                  <div class="status_list">
                    <span ng-hide="all.reservation.list_type == 'Experiences' && all.reservation.type == 'contact'">
                      <span class="label label label-info"
                      ng-show="@{{(all.reservation.status == 'Pre-Accepted' || all.reservation.status == 'Inquiry') && all.reservation.checkin < (today | date : 'y-MM-dd') }}" >{{trans('messages.dashboard.Expired')}}</span>
                      <span class="label label-@{{ all.reservation.status_color }}"
                      ng-hide="@{{(all.reservation.status == 'Pre-Accepted' || all.reservation.status == 'Inquiry') && all.reservation.checkin < (today | date : 'y-MM-dd') }}">
                      @{{ all.reservation.status_language }}
                    </span>
                    <br>
                    <span id="price-breakdown-153062093" ng-show="all.guest_check" class=" price-breakdown-trigger">
                      <span ng-bind-html="all.reservation.currency.original_symbol"></span> @{{ all.reservation.total  }}
                    </span>
                    <span id="price-breakdown-153062093" ng-show="all.host_check" class=" price-breakdown-trigger">
                      <span ng-bind-html="all.reservation.currency.original_symbol"></span> @{{ all.reservation.subtotal - all.reservation.host_fee }}
                    </span>
                  </span>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-sm-7 col-lg-3 thread-label lang-chang-label inbox_history">
              <div class="row">
                <div  class="col-sm-12 col-md-12 col-lg-6 acceptrej lang-chang-label" >
                  <span ng-hide="all.reservation.list_type == 'Experiences' && all.reservation.type == 'contact'">
                    <span class="label label label-info"
                    ng-show="@{{(all.reservation.status == 'Pre-Accepted' || all.reservation.status == 'Inquiry') && all.reservation.checkin < (today | date : 'y-MM-dd') }}" >{{trans('messages.dashboard.Expired')}}</span>
                    <span class="label label-@{{ all.reservation.status_color }}"
                    ng-hide="@{{(all.reservation.status == 'Pre-Accepted' || all.reservation.status == 'Inquiry') && all.reservation.checkin < (today | date : 'y-MM-dd') }}">
                   @{{ all.reservation.status_language }}
                  </span>
                  <br>
                  <span id="price-breakdown-153062093" ng-show="all.guest_check" class=" price-breakdown-trigger">
                    <span ng-bind-html="all.reservation.currency.original_symbol"></span> @{{ all.reservation.total  }}
                  </span>
                  <span id="price-breakdown-153062093" ng-show="all.host_check" class=" price-breakdown-trigger">
                    <span ng-bind-html="all.reservation.currency.original_symbol"></span> @{{ all.reservation.subtotal - all.reservation.host_fee }}
                  </span>
                </span>
              </div>
              <div id="options_153062093" class="col-sm-6 options thread-actions hide-sm">
                <ul class="thread-actions list-unstyled">
                  <li class="row-space-1">
                    <a data-thread-id="153062093" href="javascript:void(0);" class="link-icon thread-action js-star-thread ">
                      <i ng-show="all.star == 1" class="icon istar_@{{ all.user_from }} icon-star icon-beach" ng-click="star($index, all.user_from,all.id,'Unstar')"></i>
                      <i ng-show="all.star == 0" class="icon iunstar_@{{ all.user_from }} icon-star-alt icon-gray" ng-click="star($index, all.user_from,all.id,'Star')"></i>
                      <span ng-show="all.star == 1" class="thread-star link-icon__text star_@{{all.user_from}}" ng-click="star($index, all.user_from,all.id,'Unstar')">{{ trans('messages.inbox.unstar') }}</span>
                      <span ng-show="all.star == 0" class="thread-star link-icon__text un_star_@{{all.user_from}}" ng-click="star($index, all.user_from,all.id,'Star')">{{ trans('messages.inbox.star') }}</span>
                    </a>
                  </li>
                  <li id="thread_153062093_hidden">
                    <a data-thread-id="153062093" href="javascript:void(0);" class="link-icon thread-action js-archive-thread ">
                      <i ng-show="all.archive == 1" class="icon icon-archive icon-kazan"  ng-click="archive($index,all.user_from ,all.id,'Unarchive')"></i>
                      <i ng-show="all.archive == 0" class="icon icon-archive icon-gray" ng-click="archive($index, all.user_from,all.id,'Archive')"></i>
                      <span  ng-show="all.archive == 0" class="link-icon__text user_from_@{{all.user_from}}"  ng-click="archive($index, all.user_from,all.id,'Archive')">{{ trans('messages.inbox.archive') }}</span>
                      <span ng-show="all.archive == 1" class="link-icon__text un_user_from_@{{all.user_from}}"  ng-click="archive($index,all.user_from ,all.id,'Unarchive')">{{ trans('messages.inbox.unarchive') }}</span>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>          
        </div>
      </li>
    </ul>
    <div class="results-footer">
      <div class="pagination-buttons-container" ng-cloak>
        <div class="results_count" style="float: right;margin-top: 20px;">
          <div> <p>
            <span>@{{ message_result.from }} – @{{ message_result.to }}</span><span> of</span><span> @{{ message_result.total }} </span><span>{{ trans_choice('messages.dashboard.message',2) }}</span>
          </p></div>
          <posts-pagination></posts-pagination>
        </div>
        <div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</main>
@stop