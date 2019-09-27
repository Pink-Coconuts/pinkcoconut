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
      <li class="op_lw1"><a href="#" class="rm_link"> Price</a></li>
      <li class="active op_lw1"><a href="#" class="rm_link"> Contribution</a></li>
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
        
        <p class="h2 bold space-top-8">Explain what the guests’ money benefits</p>
        <p class="h4 line1 space-2">Give guests insight into how the nonprofit organization will use their money. What will they be funding or enabling the nonprofit organization to do?</p>
        
        <textarea class="input_new1 space-top-4" placeholder="What we’ll do" rows="6" >Give guests insight into how the nonprofit organization will use their money. What will they be funding or enabling the nonprofit organization to do?</textarea> 

        <button class="btn-primary2 space-top-8 space-8" > <a href="17" class="rm_link"> Next</a></button>

      </div>


    </div>
  </div><!--  main_bar end -->

</div>


@stop