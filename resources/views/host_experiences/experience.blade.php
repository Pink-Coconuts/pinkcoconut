@extends('template')

@section('main')

<style type="text/css">
  #header, #footer {
    display: none;
}
</style>

<div class="bg_new clearfix text-white">

  <div class="side_bar pull-left padd4">
    <a href="javascript:void(0);" aria-label="Homepage" data-prevent-default="" class="logo_new" style="background-image: url('{{ url(LOGO_URL) }}'); background-size: 70px;"></a>

    <ul class="list-unstyled space-top-5 text-uppercase bold side_menu">
      <li class="op_lw1"><a href="#" class="rm_link"> THE BASICS</a></li>
      <li class="op_lw1"><a href="#" class="rm_link"> Language</a> <i class="icon icon2-tick pull-right text-rausch"></i></li>
      <li class="op_lw1"><a href="#" class="rm_link"> Category</a></li>
    </ul>

    <ul class="list-unstyled text-uppercase bold side_menu  space-top-7">
      <li class="active op_lw1"><a href="#" class="rm_link"> EXPERIENCE PAGE</a></li>
      <li class="op_lw1"><a href="#" class="rm_link"> Experience title</a></li>
      <li class="op_lw1"><a href="#" class="rm_link"> Time </a></li>
      <li class="op_lw1"><a href="#" class="rm_link"> Tagline </a></li>
      <li class="op_lw1"><a href="#" class="rm_link"> Photos </a></li>
      <li class="op_lw1"><a href="#" class="rm_link"> What we’ll do </a></li>
      <li class="op_lw1"><a href="#" class="rm_link"> Where we’ll be </a></li>
      <li class="op_lw1"><a href="#" class="rm_link"> Where we’ll meet </a></li>
      <li class="op_lw1"><a href="#" class="rm_link"> What I’ll provide </a></li>
      <li class="op_lw1"><a href="#" class="rm_link"> Notes </a></li>
    </ul>

    <ul class="list-unstyled text-uppercase bold side_menu  space-top-7">
      <li class="op_lw1"><a href="#" class="rm_link"> <span class="icon-lock"></span> FINISHING TOUCHES</a></li>
    </ul>

    <ul class="list-unstyled text-uppercase bold side_menu  space-top-7">
      <li class="op_lw1"><a href="#" class="rm_link"> <span class="icon-lock"></span> REVIEW & SUBMIT</a></li>
    </ul>

  </div><!--  side_bar end -->

  <div class="main_bar pull-left padd5">

      <div class="pos_fix2">
      <div class="col-sm-2">
        <i class="fa fa-bars side_menu_bar"></i>
      </div>
      <div class="col-sm-10 text-right">
        <ul class="list-inline list_style1">
          <li class="op_lw1 hide_sm"> Changes saved 5 minutes ago</li>
          <li><a href="#" class="rm_link"> Save & exit </a></li>
        </ul>        
      </div>      
    </div>


    <div class="clearfix ">
      <div class="col-md-7">
        <p class="h1 bold space-top-8">Create the page guests will see</p>
        <p class="h4 line1 space-2">Use the preview on the the right to see how your experience will look publicly. As you create the description for your experience, it will appear in the preview.</p>

        <p class="h4 line1 space-2"> Write in a clear, straightforward, and friendly way. We’ll give you tips on when to show off your personality and be more descriptive.</p>
        
        <button class="btn-primary3 space-top-8 space-8" > <a href="3" class="rm_link"> Next</a></button>
      </div>

      <div class="pos_fix1">
        <div class="phone_eff" style="background-image: url('{{url('images/host_experiences/mb_cover.png')}}');">
          <div class="over_scroll1">
            <img src="{{url('images/host_experiences/mb_data.jpg')}}" class="img-responsive">
          </div>
        </div>
      </div>

    </div>
  </div><!--  main_bar end -->

</div>

<div class="popup hide">
 <div class="popup_frame popup_frame padd4 popup_frame_sz1">
    <a href="#" class="h5 rm_link close_pop text-muted"><i class="icon icon2-cancel"></i></a>
    <p class="h4 space-top-3 space-3">Are you sure you want to change your submission language? If saved, this will delete your descriptions written in English.</p>
    <a href="#" class="btn-primary2">Yes</a>&nbsp; <a href="#" class="btn-primary3">No, undo</a>
 </div>
</div>

@stop