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
      <li class="op_lw1"><a href="#" class="rm_link"> FINISHING TOUCHES</a></li>
      <li class="op_lw1"><a href="#" class="rm_link"> About you</a></li>
      <li class="op_lw1"><a href="#" class="rm_link"> Guest requirements</a></li>      
      <li class="op_lw1"><a href="#" class="rm_link"> Group size</a></li>
      <li class="active op_lw1"><a href="#" class="rm_link"> Price</a></li>
      <li class="op_lw1"><a href="#" class="rm_link"> Contribution</a></li>
      <li class="op_lw1"><a href="#" class="rm_link"> Preparation time</a></li>
      <li class="op_lw1"><a href="#" class="rm_link"> Background info</a></li>
      <li class="op_lw1"><a href="#" class="rm_link"> Packing list</a></li>
    
    </ul>

    <ul class="list-unstyled text-uppercase bold side_menu  space-top-7">
      <li class="op_lw1"><a href="#" class="rm_link"> <span class="icon-lock"></span> REVIEW & SUBMIT</a></li>
    </ul>

  </div><!--  side_bar end -->

  <div class="main_bar pull-left padd5 bg-white text-black">

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
        <p class="h2 bold space-top-8 space-0">Set a price per guest</p>
        <p class="h4 line1 space-2">The price of your experience is entirely up to you. Play with the calculator to see how much you’d earn depending on the number of guests. If you have questions, <a href="#" class="text-rausch rm_link"> read up</a> on tips for setting your price.</p>

        <div class="row space-top-6">
          <div class="col-sm-8">
            <div class="pos_rel input_new2">
              <span class="h3">USD</span>
              <input type="text" name="" class="input_new1" value="500">
            </div>
          </div>
        </div>

        <div class="tabl space-top-3">
          <div class="tb_cell ver_top">
            <input type="checkbox" name="" id="check1" class="chekbox1">
          </div>
          <div class="tb_cell">
            <label class="h4 cursor padd0" for="check1">Free for guests under age 2</label>
          </div>
        </div>        
        
        <a href="16_1" class="rm_link"><button class="btn-primary2 space-top-8 space-8" > Next</button></a>
      </div>

      <div class="pos_fix1 padd1">
        <p class="h3 bold space-top-2">Pricing calculator</p>
        <div class="row space-top-8">
          <div class="col-sm-6">
            <p class="h4">Price per guest</p>
          </div>
          <div class="col-sm-6 text-right">
            <p class="h4">$500</p>
          </div>
        </div>
        <hr>

        <div class="row space-top-3 space-3">
          <div class="col-sm-6">
            <p class="h4 space-0 space-top-1">Number of guests</p>
          </div>
          <div class="col-sm-6">
            <input type="number" name="" value="4" class="wid1 pull-right">
          </div>
        </div>
        <hr>

        <div class="row space-top-5 space-2">
          <div class="col-sm-6">
            <p class="h4 space-0">You’d make</p>
          </div>
          <div class="col-sm-6 text-right">
             <p class="h4">$1600</p>
          </div>
        </div>   
        <small>This is the amount of money you’d make after a 20% <a href="#" class="rm_link text-rausch"> service fee</a> taken by Airbnb.</small>

      </div>

    </div>
  </div><!--  main_bar end -->

</div>

@stop