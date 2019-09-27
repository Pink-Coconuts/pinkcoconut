@extends('template')
@section('main')
@if($header_class == 'exp_mak')
<div class="exp_home" ng-controller="host_experiences">
  <div class="exp_top">
    <div class="exp_ban"> </div>
      <div class="ed_exp"></div>
   </div>
    <div class="exp_shar">
        <h1>{{trans('experiences.home.share_your_experience')}}</h1>
        <p>{{trans('experiences.home.create_unique_experience_and_earn_money')}}</p>

        <button  class="btn btn-primary" href="javascript:void(0)" ng-click="new_experience_navigate()">{{trans('experiences.home.create_an_experience')}}</button>


    </div>
  <div class="dtes">
  <div class="shr_ht" id="gthight">
    <div class="shre">
      
      <h1>{{trans('experiences.home.share_your_experience')}}</h1>
    </div>

    <div class="exr_lik"><button  class="btn btn-primary" href="javascript:void(0)" ng-click="new_experience_navigate()">{{trans('experiences.home.create_an_experience')}}</button></div>
  </div>
  </div>
</div>
@endif
<div ng-controller="host_experiences">
  <div class="page-container">
    @if($header_class != 'exp_mak')
    <div class="row space-top-8">
      <div class="col-md-8">
        <p class="h2 bold">{{trans('experiences.home.welcome_back')}}
        </p>
        <p class="h5">{{trans('experiences.home.keep_track_and_edit_happy_hosting')}}
        </p>
      </div>
      <div class="col-md-4 md_text-right">
        <a class="btn-primary2 btn_sm" href="javascript:void(0)" ng-click="new_experience_navigate();">{{trans('experiences.home.new_experience')}}
        </a>
      </div>
    </div>
    @if($host_experiences->count() > 0)
    <div class="over_scroll space-top-7">
      <div style="width: {{$host_experiences->count() * 465}}px;">
        @foreach($host_experiences as $host_experience)
        <div class="box1 pos_rel">
          <div class="height1">
            <p class="h3 bold space-1">
              <a href="{{url('experiences/'.$host_experience->id)}}" class="link-reset">{{$host_experience->title ? $host_experience->title : trans('experiences.home.experience')}}
              </a>
            </p>
            <p>{{@$host_experience->city_details->name}} {{-- · 1 {{strtolower(trans('experiences.home.experience'))}} --}}
            </p>
            <p class="space-top-5"> 
            
              @if($host_experience->is_completed)
                @if($host_experience->admin_status == 'Pending')
                  <i class="fa fa-circle text-warning"></i>&nbsp; 
                  {{trans('experiences.home.your_experience_submitted_expect_hear_back_in_weeks')}}
                @elseif($host_experience->admin_status == 'Approved')
                  <i class="fa fa-circle text-success"></i>&nbsp; 
                  {{trans('experiences.home.experience_approved')}}
                @else
                  <i class="fa fa-circle text-danger"></i>&nbsp; 
                  {{trans('experiences.home.experience_rejected')}}
                @endif
              @else
                {{trans('experiences.home.you_still_have_to_complete_some_steps')}}
              @endif
            </p>        
          </div>
          <a class="btn-primary3 btn_sm" href="{{url('host/manage_experience/'.$host_experience->id)}}">{{trans('experiences.home.edit_experience')}}
          </a>
          <div class="tooltip_cover">
            <i class="icon icon2-settings h4">
            </i>
            <div class="tooltip2 hide">
              <a ng-click="delete_experience('{{ trans('messages.lys.delete') }}','{{ trans('messages.lys.delete_experience') }}')" class="text-rausch js-delete-photo-btn" id="{{ $host_experience->id}}"> {{trans('experiences.home.delete')}} 
              </a>
            </div>
          </div>
<div id="js-error" class="modal show" aria-hidden="true" style="" tabindex="-1">
  <div class="modal-table">
    <div class="modal-cell">
      <div class="modal-content">
        <div class="panel-header">
          
        </div>
        <div class="panel-body">
          <p> </p>
        </div>
        <div class="panel-footer">
          <button data-behavior="modal-close" class="btn">
            {{ trans('messages.home.close') }}
          </button>
          <button class="btn btn-primary js-delete-photo-confirm hide" ng-value="id" data-id="">
            {{ trans('messages.lys.delete') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
        </div>
        @endforeach
      </div>
    </div>
    @endif
    @endif
    <div class="row">
      <div class="col-lg-8 col-lg-offset-2">
        <p class="space-top-8 md_text-center space-8 h4 fot_extext ft_bug">{{trans('experiences.home.hosts_who_love_sharing_community_experiences')}}
        </p>
      </div>
    </div>
    <div class="height2 background-cover pos_rel" data-id="pop1" style="background-image: url('{{url('images/host_experiences/video_static_img12.jpg')}}');">
      <div class="trans1">
        <i class="icon icon2-play-button h4 video_ico text-white">
        </i>
      </div>
    </div>
    <p class="h2 bold space-top-7 space-7">{{trans('experiences.home.sample_of_great_hosts_do')}}
    </p>
  </div><!-- container end -->
  <div class="new_slider" style="direction: ltr">
    <div class="outer_slider">
      <div class="item_sli">
        <img src="{{url('images/host_experiences/great_host_slider1.jpg')}}" class="img-responsive">
        <div class="row space-top-5">
          <div class="col-md-4">
            <p class="h3 bold">{{trans('experiences.home.give_insider_access')}}
            </p>
          </div>
          <div class="col-md-5">
            <p class="h4 text-muted">{{trans('experiences.home.dawn_takes_aquarium_research_programs_marine')}}
            </p>
          </div>
        </div>
      </div>
      <div class="item_sli">
        <img src="{{url('images/host_experiences/great_host_slider2.jpg')}}" class="img-responsive">
        <div class="row space-top-5">
          <div class="col-md-4">
            <p class="h3 bold">{{trans('experiences.home.encourage_participation')}}
            </p>
          </div>
          <div class="col-md-5">
            <p class="h4 text-muted">{{trans('experiences.home.ron_makes_sunflower_planting_learning_about_compose')}}
            </p>
          </div>
        </div>
      </div>
      <div class="item_sli">
        <img src="{{url('images/host_experiences/great_host_slider3.jpg')}}" class="img-responsive">
        <div class="row space-top-5">
          <div class="col-md-4">
            <p class="h3 bold">{{trans('experiences.home.offer_unique_perspective')}}
            </p>
          </div>
          <div class="col-md-5">
            <p class="h4 text-muted">{{trans('experiences.home.virgo_shows_unveiling_music_virtual_reality')}}
            </p>
          </div>
        </div>
      </div>
    </div> <!-- outer_slider end  -->
    <div class="left_nav" style="display: none;">
      <span class="icon-chevron-left">
      </span>
    </div>
    <div class="right_nav">
      <span class="icon-chevron-right ">
      </span>
    </div>
  </div>
  <div class="page-container">
    <hr class="space-top-8 space-8">
    <div class="row hide">
      <div class="col-md-6 padd3 md_pull_right">
        <p class="h3 bold space-top-9 sm_tp_non">{{trans('experiences.home.want_to_host_for_social_cause')}}
        </p>
        <p class="h5 text-muted">{{trans('experiences.home.if_you_are_member_nonprofit_organization_bring_people')}}
        </p>
        <a href="#" class="h5 text-rausch">{{trans('experiences.home.learn_more')}} > 
        </a>
      </div>
      <div class="col-md-6">
        <img src="{{url('images/host_experiences/makent_ph.png')}}" class="img-responsive">
      </div>
    </div>  
    <p class="h2 bold space-top-7 space-7">{{trans('experiences.home.why_host_on_makent', ['site_name' => SITE_NAME])}}
    </p>
    <div class="height2 background-cover" style="background-image: url('{{url('images/host_experiences/why_host.jpg')}}');">
    </div>
    <div class="row space-top-7">
      <div class="col-md-4">
        <p class="h4 bold">{{trans('experiences.home.get_more_exposure')}}
        </p>
        <p class="h5 text-muted">{{trans('experiences.home.millions_of_guest_book_instant')}}
        </p>
      </div>
      <div class="col-md-4">
        <p class="h4 bold">{{trans('experiences.home.promote_your_brand')}}
        </p>
        <p class="h5 text-muted">{{trans('experiences.home.get_sharable_source_of_experience')}}
        </p>
      </div>
      <div class="col-md-4">
        <p class="h4 bold">{{trans('experiences.home.meet_locals_like_you')}}
        </p>
        <p class="h5 text-muted">{{trans('experiences.home.join_global_hosts_community_meetups_and_event')}}
        </p>
      </div>
    </div>
    <hr class="space-top-8 space-8">
    <p class="h2 bold space-top-8 space-8">{{trans('experiences.home.how_to_get_started')}}
    </p>
    <div class="row">
      <div class="col-md-3">
        <span class="circle1 text-center">1
        </span>
        <p class="h3 space-top-2">{{trans('experiences.home.get_inspired_to_host')}} 
        </p>
        <p class="h5 text-muted line1">{!!trans('experiences.home.review_quality_standards_to_know_what_community_expects', ['quality_standards_link' => '<a href="'.url('quality_standards').'" class="text-rausch"> '.trans('experiences.manage.quality_standards').'</a>'] ) !!}
        </p>
      </div>
      <div class="col-md-3">
        <span class="circle1 text-center">2
        </span>
        <p class="h3 space-top-2">{{trans('experiences.home.design_and_submit')}}
        </p>
        <p class="h5 text-muted line1">{{trans('experiences.home.build_your_experience_and_submit_our_team_will_reach_you')}}
        </p>
      </div>
      <div class="col-md-3">
        <span class="circle1 text-center">3
        </span>
        <p class="h3 space-top-2">{{trans('experiences.home.hot_on_your_terms')}}
        </p>
        <p class="h5 text-muted line1">{{trans('experiences.home.once_host_published_free_to_host_when_you_want')}}
        </p>
      </div>
      <div class="col-md-3">
        <span class="circle1 text-center">4
        </span>
        <p class="h3 space-top-2">{{trans('experiences.home.manage_on_the_fly')}}
        </p>
        <p class="h5 text-muted line1">{{trans('experiences.home.keep_track_of_bookings_message_and_update_from_app')}}
        </p>
      </div>
    </div>
  </div>
  <div class="bg1 space-top-9 text-white" id="create_host_experience_div">
    <div class="page-container">
      <div class="row space-top-9">
        <div class="col-lg-6">
          <p class="h2 bold">{{trans('experiences.home.ready_to_host_in_your_city')}}
          </p>
          <p class="h5 line1">{{trans('experiences.home.start_host_experience_short_desc')}}
          </p>
        </div>
      </div>
      <div class="row">
        <div class="col-md-7">
          <div class="bg-white space-top-8 text-black padd4 space-9 ">
            <p class="space-1 bold small">{{trans('experiences.home.where')}}
            </p>  
            <div class="row">
              {!! Form::open(['url' => 'host/experiences/new', 'id' => 'new_host_experience', 'accept-charset' => 'UTF-8' , 'name' => 'new_host_experience', 'method' => 'post']) !!}
              
              <div class="col-lg-6">            
                <div class="drp_dwn_cng1 pos_rel dropdown1">
                  <i class="icon-chevron-down" ng-init="city = ''">
                  </i>
                  {!! Form::select('city', $host_experience_cities, '', ['placeholder' => trans('experiences.home.select_city'), 'ng-model' => 'city', 'id' => 'input_city']) !!}
                </div>
              </div>
              <div class="col-lg-6 text-center">
                <button class="btn-primary3 btn_sm sm_space-top-1" ng-disabled="city == ''" type="submit"> {{trans('experiences.home.create_an_experience')}}
                </button>
              </div>
            </div>
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- <div class="popup hdsrc">
    <div class="popup_frame" data-id="pop1">
    <a class="modal-close wl-modal__modal-close hecls"></a>
      <video width="100%" height="500px" controls >
        <source src="{{url('uploads/video/home_video.mp4')}}" type="video/mp4">
        <source src="{{url('uploads/video/home_video.webm')}}" type="video/webm">
        Your browser does not support the video tag.
      </video>
    </div>
  </div> -->
</div>
@stop