@extends('template')
@section('main')
<style type="text/css">
#header, #footer {
  display: none;
}
</style>
<div class="bg_new clearfix text-white mob_side" ng-controller="manage_experiences" ng-cloak>
  {!! Form::open(['url' => url('host/manage_experience/'.$host_experience->id.'/update_experience'), 'id' => 'update_host_experience', 'accept-charset' => 'UTF-8' , 'name' => 'update_host_experience', 'method' => 'post']) !!}
  <input type="hidden" name="step_num" ng-model="step_num" value="{{$step_num}}" >
  <input type="hidden" name="step" ng-model="step" value="{{$step}}" >
  <div class="side_bar pull-left padd4 het" ng-init="host_experience = {{json_encode($host_experience_array)}}; host_experience_id={{$host_experience->id}}; host_experience_steps = {{json_encode($host_experience->steps)}}; step_num={{$step_num}}; step='{{$step}}'; ajax_base_url = '{{$ajax_base_url}}'; field_validations = {{json_encode(trans('experiences.field_validations'))}}; host_experience_provides = {{json_encode(@$host_experience_provides)}}; host_experience_photos = {{json_encode(@$host_experience_photos)}}; host_experience_packing_lists = {{json_encode(@$host_experience->host_experience_packing_lists)}}; provide_items={{json_encode(@$provide_items)}}; ">
    <a href="{{url('/')}}" aria-label="Homepage" data-prevent-default="" class="logo_new" style="background-image: url('{{ url(LOGO_URL) }}'); background-size: 70px;">
    </a>
    <div class="col-sm-2 for_sideleft two_bld">
      <i class="fa fa-bars side_menu_bar sidebarbar">
      </i>
    </div>
    <div id="manage_experience_menu" class="sidemen" ng-cloak>
      @include('host_experiences.manage_experience.menu')
    </div>
  </div>
  <!--  side_bar end -->
  <div id="manage_experience_main_content" class="exp_left new_load" >
    @include('host_experiences.'.$main_content_section)
  </div>
  {!! Form::close() !!}
  <!--  main_bar end -->

  <div class="popup hide1 " id="control_btns_popup">
    <div class="popup_frame popup_frame padd4 popup_frame_sz1">
      <a href="javascript:void(0)" class="h5 rm_link text-muted close_control_btns_popup" ><i class="icon icon2-cancel"></i></a>
      <p class="h4 space-top-3 space-3 text-black" >{{trans('experiences.manage.there_are_some_unsaved_changes_please_complete_the_step')}}
      </p>
      @include('host_experiences.manage_experience.control_buttons')      
    </div>
  </div>
</div>

<div class="popup hide1 hide">
  <div class="popup_frame popup_frame padd4 popup_frame_sz1">
    <a href="#" class="h5 rm_link close_pop text-muted"><i class="icon icon2-cancel"></i></a>
    <p class="h4 space-top-3 space-3">Are you sure you want to change your submission language? If saved, this will delete your descriptions written in English.</p>
    <a href="#" class="btn-primary2 btn_sm">Yes</a>&nbsp; <a href="#" class="btn-primary3 btn_sm">No, undo</a>
  </div>
</div>

<div class="popup hide" id="photo_error_popup">
  <div class="popup_frame popup_frame padd4 popup_frame_sz1">
    <a href="javascript:void(0)" class="h5 rm_link text-muted close_photo_error_popup"><i class="icon icon2-cancel"></i></a>
    <p class="h3 bold space-top-8" id="title">
    </p>
    <p class="h4 space-top-3 space-3" id="description"></p>
    <a href="javascript:void(0);" class="btn-primary3 btn_sm" id="choose_another_photo_btn" data-index="" >{{trans('experiences.manage.choose_another_photo')}}</a>&nbsp; 
    <a href="javascript:void(0);" class="btn-primary2 btn_sm close_photo_error_popup nt_ccld" id="photo_upload_cancel_btn">{{trans('experiences.manage.cancel')}}</a>
  </div>
</div>

<div class="popup hide" id="photo_image_popup">
  <div class="popup_frame popup_frame padd4 popup_frame_sz1">
    <a href="javascript:void(0)" class="h5 rm_link text-muted close_photo_error_popup"><i class="icon icon2-cancel"></i></a>
    <p class="h3 bold space-top-8" id="title">
    </p>
  </div>
</div>
@stop