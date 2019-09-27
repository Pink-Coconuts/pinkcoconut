<div class="row row-space-6 post">
    @if($message->sender_or_receiver == 'Sender')
    <div class="col-lg-2 col-md-3 col-sm-3 text-center name_break user_msg_detail1">
        <div class="media-photo media-round">
            <img width="70" height="70" src="{{ $message->message_sender_details->get('profile_picture') }}" class="user-profile-photo">
        </div>
        <p>{{$message->message_sender_details->get('name')}}
        </p>
    </div>
    <div class="col-lg-10 col-md-9 col-sm-9 user_msg_detail">
        <div class="panel-quote-flush panel-quote panel panel-quote-left">
            <div class="panel-body">
                <div>
                    <span class="message-text">{{$message->message}}
                    </span>
                </div>
                <div class="text-right space-top-2">
                    <span class="">-{{trans('messages.payments.to')}} {{$message->message_receiver_details->get('name')}}</span>
                </div>
                @if($message->sub_text)
                <div class="inline-status text-branding space-top-3 text-center">
                    <div class="horizontal-rule-text">
                        <span class="horizontal-rule-wrapper">
                            <span>
                                <span>{{$message->sub_text}}
                                </span>
                            </span>
                        </span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @else
    <div class="col-lg-10 col-md-9 col-sm-9 user_msg_detail1">
        <div class="panel-quote-flush panel-quote panel panel-quote-right">
            <div class="panel-body">
                <div>
                    <span class="message-text">{{$message->message}}
                    </span>
                </div>
                @if($message->sub_text)
                <div class="inline-status text-branding space-top-3 text-center">
                    <div class="horizontal-rule-text">
                        <span class="horizontal-rule-wrapper">
                            <span>
                                <span>{{$message->sub_text}}
                                </span>
                            </span>
                        </span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-3 text-center name_break user_msg_detail">
        <div class="media-photo media-round">
            <img width="70" height="70" src="{{$message->message_sender_details->get('profile_picture') }}" class="user-profile-photo">
        </div>
        <p>{{$message->message_sender_details->get('name')}}
        </p>
    </div>
    @endif
</div>