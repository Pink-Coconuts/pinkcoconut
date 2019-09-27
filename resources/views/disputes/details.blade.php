@extends('template')
@section('main')
<main id="site-content" role="main" ng-controller="dispute_details">
    @include('common.subheader')
    <div class="page-container page-container-responsive row-space-top-4">
        <div class="row">
            <div class="col-md-8 col-sm-12 dispute_leftside">
                <div class="row row-space-4">
                    <div class="col-sm-12">
                        <h3>{{$dispute->inbox_subject}}</h3>
                    </div>
                </div>
                @if($dispute->can_dispute_accept_form_show())
                <div class="row row-space-8 dispute_amount_accept_panel">
                    <div class="col-sm-12 space-2">
                        <label class="label">{{trans('messages.disputes.include_a_message_for_user', ['first_name' => $dispute->user_or_dispute_user == 'User' ? $dispute->dispute_user->first_name : $dispute->user->first_name])}}:</label>
                        <textarea class="form-control" name="message" ng-model="accept_amount_data.message"></textarea>
                        <p  ng-cloak class="text-danger">@{{accept_amount_form_errors.message[0]}}</p>
                        @if($dispute->is_pay())
                        <input type="hidden" name="payment_type" ng-model="accept_amount_data.payment" ng-init="accept_amount_data.payment = 'Pay'">
                        <button class="btn btn-host-banner" ng-click="accept_amount();">{{trans('messages.disputes.pay')}} {{$dispute->currency->original_symbol}}{{$dispute->final_dispute_data->get('amount')}}</button>
                        @else
                        <input type="hidden" name="payment_type" ng-model="accept_amount_data.payment" ng-init="accept_amount_data.payment = 'Accept'">
                        <button class="btn btn-host-banner" ng-click="accept_amount();">{{trans('messages.disputes.accept')}} {{$dispute->currency->original_symbol}}{{$dispute->final_dispute_data->get('amount')}}</button>
                        @endif
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col-sm-12 space-4">
                        <div class="row">
                           <!--  <div class="col-sm-2 text-left disputes_conversation_image">
                                <div class="media-photo media-round">
                                    <img width="70" height="70" src="{{ Auth::user()->profile_picture->src }}">
                                </div>
                            </div> -->
                            <div class="col-sm-12 dispute_conversation_details">
                                <div class="panel-default panel">
                                    <div class="panel-header">
                                        <ul class="tabs tabs-header">
                                          <li>
                                            <a href="javascript:void(0)" data-target="keep_talking" class="tab-item" aria-selected="true">{{trans('messages.disputes.keep_talking')}}
                                            </a>
                                          </li>
                                          {{--
                                          <li>
                                            <a href="javascript:void(0)" data-target="close" class="tab-item">{{trans('messages.home.close')}}
                                            </a>
                                          </li>
                                          --}}
                                          <li>
                                            <a href="javascript:void(0)" data-target="involve_site" class="tab-item">{{trans('messages.disputes.involve_site', ['site_name' => $site_name])}}
                                            </a>
                                          </li>
                                        </ul>
                                    </div>
                                    <div class="panel-body space-4" ng-cloak id="dispute_controls_area">
                                        <input type="hidden" name="dispute_id" id="dispute_id" value="{{$dispute->id}}" >
                                        <div class="tabs-content">
                                            <div class="tab-panel" data-tab_content="keep_talking">
                                                <div class="row">
                                                    @if(($dispute->status == 'Open' || $dispute->status == 'Processing') && $dispute->payment_status == null)
                                                    <div class="col-sm-12 space-2">
                                                        <p class="space-1">{{trans('messages.disputes.offer_a_different_amount')}}:</p>
                                                        <div class="input-addon">
                                                            <span class="input-prefix">{{$dispute->currency->original_symbol}} </span>
                                                            <input type="text" name="amount" class="input-stem input-large form-control" ng-model="dispute_message.amount">
                                                        </div>
                                                        <p class="text-danger">@{{dispute_message_form_errors.amount[0]}}</p>
                                                    </div>
                                                    @endif
                                                    <div class="col-sm-12 space-2">
                                                        <textarea class="form-control" name="message" ng-model="dispute_message.message"></textarea>
                                                        <p class="text-danger">@{{dispute_message_form_errors.message[0]}}</p>
                                                    </div>
                                                    <div class="col-sm-12 space-2">
                                                        <button class="btn btn-primary pull-right" type="button" ng-click="keep_talking()">{{trans('messages.disputes.keep_talking')}}</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-panel" data-tab_content="close" aria-hidden="true">
                                                
                                            </div>
                                            <div class="tab-panel" data-tab_content="involve_site" aria-hidden="true">
                                                <div class="row">
                                                    <div class="col-sm-12 space-2">
                                                        <textarea class="form-control" name="message" ng-model="involve_site_data.message"></textarea>
                                                        <p class="text-danger">@{{involve_site_form_errors.message[0]}}</p>
                                                    </div>
                                                    <div class="col-sm-12 space-2">
                                                        <button class="btn btn-primary pull-right" type="button" ng-click="involve_site()">{{trans('messages.disputes.involve_site', ['site_name' => $site_name])}}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12" id="thread-list">
                        @foreach($dispute->dispute_messages->sortByDesc('id') as $message)
                        @include('disputes/thread_list_item', ['message' => $message])
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12 dispute_rightside">
                <div class="row row-space-2">
                    <div class="col-sm-12">
                        <h5>{{trans('messages.disputes.dispute_reason')}}</h5>
                        <p>{{$dispute->subject}}</p>
                    </div>
                </div>
                <div class="row row-space-2">
                    <div class="col-sm-12">
                        <h5>{{trans('messages.disputes.reservation_information')}}</h5>
                        <div class="listing">
                            <div class="panel-image listing-img img-large">
                                <a href="{{ url('rooms/'.$dispute->reservation->room_id) }}" class="media-photo media-cover wishlist-bg-img" target="_blank">
                                    <img src="{{ $dispute->reservation->rooms->photo_name }}" width="639" height="426">
                                </a>
                            </div>
                            <div class="panel-body panel-card-section">
                                <div class="media">
                                    <a href="{{ url('users/show/'.$dispute->reservation->rooms->user_id) }}" target="_blank" class="pull-right media-photo media-round card-profile-picture card-profile-picture-offset" title="{{ $dispute->reservation->rooms->users->first_name }}">
                                        <img src="{{ $dispute->reservation->rooms->users->profile_picture->src }}" height="60" width="60" alt="{{ $dispute->reservation->rooms->users->first_name }}">
                                    </a>
                                    <a href="{{ url('rooms/'.$dispute->reservation->rooms->id) }}" class="text-normal" target="_blank">
                                        <div title="{{ $dispute->reservation->rooms->name }}" class="h5 listing-name text-truncate row-space-top-1">
                                            {{ $dispute->reservation->rooms->name }}
                                        </div>
                                    </a>
                                    <div class="">
                                        <span>{{$dispute->reservation->created_at_date}}</span> 
                                        <span class="dot-cont">·</span>
                                        <span>{{$dispute->reservation->nights}} {{trans_choice('messages.rooms.night', $dispute->reservation->nights)}}</span>
                                        <span class="dot-cont">·</span>
                                        <span class="text-beach">#{{$dispute->reservation->code}}</span>
                                        <br>
                                        <span>{{$dispute->reservation->guests_text}} </span>
                                        <span class="dot-cont">·</span>
                                        <span>{{$dispute->currency->symbol}}{{$dispute->reservation->total}}</span>
                                        <span class="dot-cont">·</span>
                                        <span class="text-beach" class="label label-{{ $dispute->reservation->status_color }}">{{$dispute->reservation->status}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row row-space-2">
                    <div class="col-sm-12">
                        <h5>{{trans('messages.disputes.attachments')}}</h5>
                        <div class="row row-space-2" >
                            <form method="POST" action="{{url('dispute_documents_upload/'.$dispute->id)}}" enctype="multipart/form-data" id="dispute_documents_form">
                                <div class="col-sm-12">
                                    <input type="file" name="documents[]" multiple class="form-control" id="dispute_documents" />
                                    <p class="text-danger">{{$errors->first('documents')}}</p>
                                </div>
                            </form>
                        </div>
                        @foreach($dispute->dispute_documents->chunk(2) as $documents_row)
                        <div class="row row-space-1">
                            @foreach($documents_row as $document)
                            <div class=" dispute_gal">
                            <a href="{{ url('dispute_documents_slider/'.$dispute->id) }}" onclick="event.preventDefault()" oncontextmenu="return false" class="gallery" data-lightbox-type="iframe">
                                <div class="col-md-12 col-sm-12 space-1">
                                    <img src="{{$document->file_url}}" class="img-responsive"></div>
                                    
                            </a>
                            @if($document->uploaded_by == @Auth::user()->id)
                             <button type="button" ng-click="delete_document('{{ trans('messages.disputes.delete_document') }}','{{ trans('messages.disputes.delete_document_desc') }}')" class="js-delete-photo-btn" id="{{ $document->id}}">
                      <i class="fa fa-trash" aria-hidden="true"></i>
                      </button>
                      @endif
                           </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
        </div>
    </div>
    @if($dispute->can_dispute_accept_form_show() && $dispute->is_pay())
    @include('disputes.payment_popup')
    @endif
    <div id="js-id-error" class="modal show delet_pop" aria-hidden="true" style="" tabindex="-1">
    <div class="modal-content popupcls">
      <div class="panel-header" style="font-size: 20px; text-align: center;">{{ trans('messages.home.warning') }}</div>
      <div class="panel-body">
        <p> </p>
      </div>
      <div class="panel-footer">
        <button data-behavior="modal-close" class="btn modal-close-btn back_close">
        {{ trans('messages.home.close') }}
        </button>
        <button class="btn btn-primary js-delete-photo-confirm hide">
        {{ trans('messages.lys.delete') }}
        </button>
      </div>
    </div>
  </div>
</main>
<style type="text/css">
    .listing-img .media-photo.media-cover {
    z-index: 0;
    border-radius: 3px;
}
.img-large .wishlist-bg-img {
    background-size: contain;
}
.wishlist-bg-img {
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}
.listing-img {
    overflow: hidden;
    padding-bottom: 67%;
}
.listing-img {
    overflow: hidden;
    padding-bottom: 67%;
}
</style>
@stop
