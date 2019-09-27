{{--
<div class="row row-space-6 post">
    @if($message->sender_or_receiver == 'Sender')
    <div class="col-sm-2 text-left">
        <div class="media-photo media-round">
            <img width="70" height="70" src="{{ $message->message_sender_details->get('profile_picture') }}" class="user-profile-photo">
        </div>
    </div>
    <div class="col-sm-10">
        <div class="panel-quote-flush panel-quote panel panel-quote-left">
            <div class="panel-body">
                <div>
                    <span class="message-text">{{$message->message}}
                    </span>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="col-sm-10">
        <div class="panel-quote-flush panel-quote panel panel-quote-right">
            <div class="panel-body">
                <div>
                    <span class="message-text">{{$message->message}}
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2 text-right">
        <div class="media-photo media-round">
            <img width="70" height="70" src="{{$message->message_sender_details->get('profile_picture') }}" class="user-profile-photo">
        </div>
    </div>
    @endif
</div>
--}}
<div class="row row-space-6 post">
    @if($message->sender_or_receiver == 'Sender')
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
       <div class="col-xs-4 col-md-2 col-sm-3 col-lg-2"> 
          <center>
             <img src="{{ $message->message_sender_details->get('profile_picture') }}" class="avatar img-circle" alt="avatar" height="50px" /> 
             {{$message->message_sender_details->get('name')}}
          </center>
       </div> 
       <div class="col-xs-8 col-sm-9 col-md-10 col-lg-10">
          <div class="usermessage">{{$message->message}}
          </div>
          <div class="text-right">
              <span>-to {{$message->message_receiver_details->get('name')}}</span>
          </div>
       </div>
    </div>
    @else
    <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12">
       <div class="col-xs-8 col-sm-9 col-md-10 col-lg-10" style=" text-align:right;">
          <div class="hostmessage">{{$message->message}}
          </div>
          <div class="text-right">
              <span>-to {{$message->message_receiver_details->get('name')}}</span>
          </div>
          <div class="inline-status text-branding space-6 text-center">
              <div class="horizontal-rule-text">
                  <span class="horizontal-rule-wrapper">
                      <span>
                          <span>{{$message->admin_sub_text}}
                          </span>
                      </span>
                  </span>
              </div>
          </div>
       </div>
       <div class="col-xs-4 col-md-2 col-sm-3 col-lg-2">
          <center>
             <img src="{{$message->message_sender_details->get('profile_picture')}}" class="avatar img-circle" alt="avatar" height="50px" />
             {{$message->message_sender_details->get('name')}}
          </center>
       </div>
    </div>
    @endif
</div>