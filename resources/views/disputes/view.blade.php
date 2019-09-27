@extends('template')
@section('main')
<main id="site-content" role="main" ng-controller="disputes">
    @include('common.subheader')
    <div class="page-container-responsive page-container-inbox space-4 space-top-4">
        <div id="disputes" class="threads" ng-cloak>
            <div class="panel">
                <div class="panel-header">
                    <div class="row">
                        <form accept-charset="UTF-8" action="" class="col-md-4" id="disputes_filter_form" method="get">
                            <div style="margin:0;padding:0;display:inline">
                                <input name="utf8" type="hidden" value="✓">
                            </div>            
                            <div class="select select-large select-block">
                                <select id="disputes_status_filter" name="status_filter" ng-cloak ng-model="disputes_status" ng-change="get_disputes_result()">
                                    <option value="" selected="selected" >{{ trans('messages.disputes.all_disputes') }} (@{{disputes_count.All}})
                                    </option>
                                    <option value="Open" >{{ trans('messages.disputes.Open') }} (@{{disputes_count.Open}})
                                    </option>
                                    <option value="Processing">{{ trans('messages.disputes.Processing') }} (@{{disputes_count.Processing}})
                                    </option>
                                    <option value="Closed">{{ trans('messages.disputes.Closed') }} (@{{disputes_count.Closed}})
                                    </option>
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
                <input type="hidden" ng-model="user_id" ng-init="user_id = {{ $user_id }}">
                <ul id="threads" class="list-layout panel-body disputes_layout_view">
                    <li id="thread_153062093" class="panel-body js-thread is-starred thread" ng-repeat="dispute in disputes_result.data" ng-cloak ng-class="dispute.dispute_messages.length > 0 ? 'unread_message' : ''">
                        <div class="row">
                            <div class="col-sm-2 col-md-4 col-lg-3 lang-chang-label thread-author inbox_history1">
                                <div class="row row-table" ng-init="this_user[$index] = dispute.user_or_dispute_user == 'User' ? dispute.dispute_user : dispute.user">
                                    <div class="thread-avatar col-md-5 lang-chang-label">
                                        <a href="{{ url('users/show/')}}/@{{ this_user[$index].id  }}">
                                            <img height="50" width="50" title="@{{ this_user[$index].first_name }}" ng-src=" @{{this_user[$index].profile_picture.src }}" class="media-round media-photo" alt="@{{ all.user_details.first_name }}">
                                        </a>
                                    </div>
                                    <div class=" thread-name list_name1">
                                        <span class="text_name3"> @{{ this_user[$index].first_name }}</span>
                                        <br>
                                        <span class="thread-date">@{{ dispute.created_at_view }}
                                        </span>
                                    </div>
                                </div>
                            </div>


                            <div class="col-sm-7 col-md-4 col-lg-6 thread-body lang-chang-label inbox_history2" >
                                 <div class=" thread-name list_name">
                                        <span class="text_name3"> @{{ this_user[$index].first_name }}</span>
                                      
                                        <span class="thread-date">@{{ dispute.created_at_view }}
                                        </span>
                                    </div>
                                <div class="common_inbox"> 
                                <span class="thread-subject">
                                    @{{ dispute.inbox_subject }}
                                </span>
                                 
                                <div class="text-muted disputes_listview show-lg show-sm">

                                    <span>@{{ dispute.reservation.list_type == 'Rooms' ? dispute.reservation.rooms.name : dispute.reservation.rooms.title }}  (@{{ dispute.reservation.checkinformatted  }} - @{{ dispute.reservation.checkoutformatted  }}, @{{ dispute.reservation.guests_text  }})
                                    </span>
                                </div>
                            </div>
                            <div class="next_list">
                              <div class="status_list">
                                  <span>@{{dispute.status_show}}</span>
                                    <a class="btn btn-normal" href="{{ url('dispute_details')}}/@{{ dispute.id }}">{{trans('messages.disputes.view_details')}}
                                    </a> 
                                </div>
                            </div>
 
                            </div>
                            <div class="col-md-4 col-sm-7 col-lg-3 thread-label lang-chang-label inbox_history disputes_history">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <span>@{{dispute.status_show}}</span>
                                        <a class="btn btn-normal view_disputes" href="{{ url('dispute_details')}}/@{{ dispute.id }}">{{trans('messages.disputes.view_details')}}
                                        </a> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li ng-if="disputes_result.data.length <= 0 || !disputes_result">
                        <div class="row">
                            <div class="col-sm-12">
                                {{trans('messages.search.no_results_found')}}
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="results-footer">
                    <div class="pagination-buttons-container" ng-cloak>
                        <div class="results_count" ng-show="disputes_result.data.length" style="float: right;margin-top: 20px;">
                            <div> 
                                <p>
                                    <span>@{{ disputes_result.from }} – @{{ disputes_result.to }}
                                    </span>
                                    <span> {{trans('messages.search.of')}}
                                    </span>
                                    <span> @{{ disputes_result.total }} 
                                    </span>
                                    <span>{{ trans('messages.disputes.disputes') }}
                                    </span>
                                </p>
                            </div>
                            <posts-pagination>
                            </posts-pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@stop
