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
      <li class="op_lw1"><a href="#" class="rm_link"> Language</a> <i class="icon icon2-tick pull-right text-rausch"></i></li>
      <li class="active op_lw1"><a href="#" class="rm_link"> Category</a></li>
    </ul>

    <ul class="list-unstyled text-uppercase bold side_menu  space-top-7">
      <li class="op_lw1"><a href="2" class="rm_link"> EXPERIENCE PAGE</a></li>
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

  <div class="main_bar pull-left padd5 text-black bg-white">

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
      <div class="col-lg-7">
        <p class="h2 bold space-top-8">What type of experience will you host?</p>
        <p class="h4 line1 space-2">Choose the category that best describes your experience. Add a second, if you think it fits into another category.</p>
        
        <div class="drp_dwn_cng1 pos_rel dropdown1 space-top-4 space-4">
              <i class="icon-chevron-down"></i>
              <select class="light" id="Categ1">
                <option>Choose a Category</option>
                <option>Entertainment</option>
                <option>Fashion</option>
              </select>            
        </div>

        <div class="add_categ2 space-top-5 hide">
          <span class="h3">Second category (optional)</span> <a href="#" class="text-rausch rm_link h4 add_categ3">&nbsp;remove</a>
          <div class="drp_dwn_cng1 pos_rel dropdown1 space-top-2 space-4">
                <i class="icon-chevron-down"></i>
                <select class="light" >
                  <option>Choose a Category</option>
                  <option>Entertainment</option>
                  <option>Fashion</option>
                </select>            
          </div>
        </div>

        <a href="#" class="rm_link text-rausch h4 add_categ1">+ Add secondary category (optional)</a>

        <p class="h4 space-top-6 space-1 bold"> <i class="icon icon2-ribbon"></i> Is this a social impact experience?</p>
        <p class="h4 line1 space-top-4">If you’re partnering with a nonprofit or a charitable organization, you can host a social impact experience. To host, you’ll have to validate your organization with our partner, TechSoup. <a href="#" class="rm_link text-rausch"> Learn more</a></p>

        <label class="space-top-4"><input type="checkbox" name="" class="chekbox1 check_detail_tri"> <span class="h4"> Yes, this is a social impact experience</span></label>

        <div class="check_detail hide">
        <p class="h4 space-top-6 space-1 bold"> Are you signed into the correct account?</p>
        <p class="h4 line1 space-top-4">Your payout method must go to your nonprofit. It cannot be changed once this experience is published.</p>

        <div class="tabl">
          <div class="tb_cell">
            <div class="prof1" style="background-image: url('{{url('images/host_experiences/pro.jpg')}}');"></div>
          </div>
          <div class="tb_cell">
           <p class="h4 bold space-0"> &nbsp;&nbsp;Arun</p>
          </div>
        </div>

        <label class="space-top-4"><input type="checkbox" name="" class="chekbox1 check_detail_tri1"> <span class="h4"> This is the correct account and not my personal Airbnb account</span></label>
        </div><!-- check_detail end -->

        <div class="check_detail1">
          <button class="btn-primary3 space-top-8 space-8" > <a href="1_1" class="rm_link"> Save</a></button> &nbsp;
          <button class="btn-primary2 space-top-8 space-8" > <a href="1_1" class="rm_link"> Undo</a></button>
        </div>

        <div class="check_detail1-1 hide">
        <button class="btn-primary2 space-top-8 space-8" > <a href="1_1" class="rm_link"> Next</a></button>
        </div>

      </div>
    </div>
  </div><!--  main_bar end -->

</div>


@stop