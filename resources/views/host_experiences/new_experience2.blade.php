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
        <p class="h1 bold space-top-8">Review our experience standards</p>
        <p class="h4 line1 space-2">Your experience has to meet our quality standards to be published on Airbnb. These standards are meant to help you create a memorable experience for your guests.  <a href="#" class="link1"> Learn more about them.</a> </p>

        <p class="h4 bold space-top-4 space-1"> 1. Access</p>
        <p class="h4 line1">Provide an experience that your guests wouldn’t be able to find on their own.</p>

        <p class="h4 bold space-top-4 space-1"> 2. Participation</p>
        <p class="h4 line1">Your guests should be able to fully take part in your experience by participating in two or more activities—not just observe them</p>

        <p class="h4 bold space-top-4 space-1"> 3. Perspective</p>
        <p class="h4 line1">Every experience should have some sort of personal meaning. Offer an original perspective that your guests might not have considered before.</p>

        <label class="space-top-4"><input type="checkbox" name=""> <span class="h4"> My experiences will meet these standards</span></label>

        <a class="btn-primary3 space-top-8 space-8" href="../create-your-experience/01">Next</a>
      </div>
      <div class="pos_fix1">
        <img src="{{url('images/host_experiences/pro2.jpg')}}" class="img-responsive img_style1">
      </div>
    </div>
  </div><!--  main_bar end -->

</div>

@stop