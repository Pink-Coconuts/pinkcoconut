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

    <ul class="list-unstyled space-top-5 text-uppercase bold side_menu op_lw">
      <li>THE BASICS</li>
      <li>Language</li>
      <li>Category</li>
    </ul>

    <ul class="list-unstyled text-uppercase bold side_menu op_lw space-top-7">
      <li> <span class="icon-lock"></span> EXPERIENCE PAGE</li>
    </ul>

    <ul class="list-unstyled text-uppercase bold side_menu op_lw space-top-7">
      <li> <span class="icon-lock"></span> FINISHING TOUCHES</li>
    </ul>

    <ul class="list-unstyled text-uppercase bold side_menu op_lw space-top-7">
      <li> <span class="icon-lock"></span> REVIEW & SUBMIT</li>
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


    <div class="clearfix">
      <div class="col-md-7">
        <p class="h1 bold space-top-8">Review our hosting standards</p>
        <p class="h4 line1 space-2">it’s important you don’t think of yourself as a tour guide. You’re a host who’s helping travelers experience a city in a meaningful way.</p>
        <p class="h4 line1">Make sure you meet these qualities before you start hosting:</p>

        <p class="h4 bold space-top-4 space-1"> 1. Credible</p>
        <p class="h4 line1">I’m deeply knowledgeable about my subject or skilled at my activity, and can demonstrate this to guests.</p>

        <p class="h4 bold space-top-4 space-1"> 2. Genuine</p>
        <p class="h4 line1">I’m truly passionate about hosting and meeting people. I want to have authentic interactions with guests, not just transactional ones.</p>

        <p class="h4 bold space-top-4 space-1"> 3. Empathetic</p>
        <p class="h4 line1">I can anticipate my guest’s needs and feelings. I keep in mind that each group I host will have varying levels of familiarity with my subject.</p>

        <label class="space-top-4"><input type="checkbox" name=""> <span class="h4"> That’s me!</span></label>

        <a class="btn-primary3 space-top-8 space-8" href="1003">Next</a>
      </div>
      <div class="pos_fix1">
        <img src="{{url('images/host_experiences/pro2.jpg')}}" class="img-responsive img_style1">
      </div>
    </div>
  </div><!--  main_bar end -->

</div>

@stop