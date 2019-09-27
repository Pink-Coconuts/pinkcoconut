@extends('template')

@section('main')

<style type="text/css">
  #header, #footer {
    display: none !important;
}
</style>

<div class="bg_new clearfix text-white">

  <div class="side_bar pull-left padd4">
    <a href="javascript:void(0);" aria-label="Homepage" data-prevent-default="" class="logo_new" style="background-image: url('{{ url(LOGO_URL) }}'); background-size: 70px;"></a>

    <ul class="list-unstyled space-top-5 text-uppercase bold side_menu">
      <li class="op_lw1"><a href="#" class="rm_link"> THE BASICS</a></li>
      <li class="active op_lw1"><a href="#" class="rm_link"> Language</a> <i class="icon icon2-tick pull-right text-rausch"></i></li>
      <li class="op_lw1"><a href="#" class="rm_link"> Category</a></li>
    </ul>

    <ul class="list-unstyled text-uppercase bold side_menu  space-top-7">
      <li class="op_lw1"><a href="#" class="rm_link"> EXPERIENCE PAGE</a></li>
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

  <div class="main_bar pull-left padd5 text-black bg-white pos_rel">

    <div class="pos_fix2 bg-white">
      <div class="col-sm-2">
        <i class="fa fa-bars side_menu_bar"></i>
      </div>
      <div class="col-sm-10 text-right">
        <ul class="list-inline list_style1">
          <li class="op_lw1 hide_sm"> Changes saved 5 minutes ago</li>
          <li><a href="#" class="rm_link text-rausch"> Save & exit </a></li>
        </ul>        
      </div>      
    </div>

    <div class="clearfix ">    
      <div class="col-md-7">
        <p class="h2 bold space-top-8">Which language will you host in?</p>
        <p class="h4 line1 space-2">You’ll write your descriptions in this language and guests will expect you to speak it during experiences.</p>

        <p class="h3 space-top-4 space-1"> Submission language</p>
        <div class="row">
          <div class="col-sm-9">
            <div class="drp_dwn_cng1 pos_rel dropdown2 space-top-4">
              <i class="icon-chevron-down"></i>
              <select class="light" id="lang1">
                <option>English</option>
                <option>Français</option>
                <option>Español</option>
              </select>            
            </div>
          </div>
        </div>   

        <p class="h3 space-top-6 space-1"> Fluent in more languages?</p>
        <p class="h4 line1">You’ll have a chance to add another language later.</p>

        <label class="space-top-4 op_lw"><input type="checkbox" name="" checked="" class="chekbox1" disabled=""> <span class="h4"> Let me know when I can add another language</span></label>
        
        <a class="btn-primary2 space-top-8 space-8" href="1"> Next</a>

      </div>
      <div class="pos_fix1 padd6">
        <p class="h4 space-2 space-top-8 line1">Languages spoken by makent travelers to New York City</p>

        <div class="row space-top-4">
          <div class="col-sm-8">
            <p class="space-1 h5">English</p>
          </div>
          <div class="col-sm-4 text-right">
            <p class="space-1 h5">72%</p>
          </div>
        </div>
        <div class="progress_bar">
          <div class="progress_val anim" style="width: 72%"></div>
        </div>

        <div class="row space-top-4">
          <div class="col-sm-8">
            <p class="space-1 h5">Français</p>
          </div>
          <div class="col-sm-4 text-right">
            <p class="space-1 h5">7%</p>
          </div>
        </div>
        <div class="progress_bar">
          <div class="progress_val anim" style="width: 7%"></div>
        </div>

        <div class="row space-top-4">
          <div class="col-sm-8">
            <p class="space-1 h5">Español</p>
          </div>
          <div class="col-sm-4 text-right">
            <p class="space-1 h5">5%</p>
          </div>
        </div>
        <div class="progress_bar">
          <div class="progress_val anim" style="width: 50%"></div>
        </div>

        <div class="row space-top-4">
          <div class="col-sm-8">
            <p class="space-1 h5">Deutsch</p>
          </div>
          <div class="col-sm-4 text-right">
            <p class="space-1 h5">5%</p>
          </div>
        </div>
        <div class="progress_bar">
          <div class="progress_val anim" style="width: 5%"></div>
        </div>

        <div class="row space-top-4">
          <div class="col-sm-8">
            <p class="space-1 h5">中文 (简体)</p>
          </div>
          <div class="col-sm-4 text-right">
            <p class="space-1 h5">3%</p>
          </div>
        </div>
        <div class="progress_bar">
          <div class="progress_val anim" style="width: 3%"></div>
        </div>

      </div>
    </div>
  </div><!--  main_bar end -->

</div>

<div class="popup hide1">
 <div class="popup_frame popup_frame padd4 popup_frame_sz1">
    <a href="#" class="h5 rm_link close_pop text-muted"><i class="icon icon2-cancel"></i></a>
    <p class="h4 space-top-3 space-3">Are you sure you want to change your submission language? If saved, this will delete your descriptions written in English.</p>
    <a href="#" class="btn-primary2 btn_sm">Yes</a>&nbsp; <a href="#" class="btn-primary3 btn_sm">No, undo</a>
 </div>
</div>

@stop