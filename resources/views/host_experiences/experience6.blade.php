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
      <li class="active op_lw1"><a href="#" class="rm_link"> Where we’ll be </a></li>
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
        <p class="h2 bold space-top-8">Mention where you’ll be</p>
        <p class="h4 line1 space-2">Name all the locations you’ll visit. Give guests a glimpse of why they’re meaningful.
Tips and examples</p>
        <a href="#" class="rm_link text-rausch h4 pop_link" data-id="popup1"> <span class="icon icon2-light-bulb h3"></span> Tips and examples</a>
        <br>
        <textarea class="input_new1 space-top-6" placeholder="What we’ll do" rows="5" ></textarea> 
        <p class="text-warning space-top-2 focus_show" >583 characters needed</p>

        <button class="btn-primary2 space-top-8 space-8" > <a href="9" class="rm_link"> Next</a></button>

      </div>

      <div class="pos_fix1">
        <div class="phone_eff" style="background-image: url('{{url('images/host_experiences/mb_cover.png')}}');">
          <div class="over_scroll1">

            <div class="bg2 text-center">
              <span class="icon icon2-photo-camera cam_adj"></span>
            </div>

            <p class="h3 bold padd7 space-0 hover1" id="scrl_ttl">Experience</p>
            <a href="#" class="h4 padd7 space-0 hover1 rm_link dis_blk">New York City &sdot; <span class="text-muted">Arts</span></a>
            <div class="padd7"><hr class="mr0"></div>

            <div class="tabl padd7">
              <div class="tb_cell col_sm_8">
                <p class="h4 space-top-3 space-3">Arts experience Hosted by <a href="#" class="rm_link text-rausch">Arun</a></p>
              </div>
              <div class="tb_cell col_sm_4">
                <div class="prof2 pull-right" style="background-image: url('{{url('images/host_experiences/pro-1.jpg')}}');"></div>
              </div>
            </div>

            <p class="space-top-3 padd7 space-3"><span class="icon icon2-clock h4"></span>&nbsp;&nbsp; <span class="h4">15 hours total</span></p>

            <div class="padd7"><hr class="mr0"></div>
            <a href="#" class="hover1 dis_blk rm_link">
              <p class="space-top-3 padd7 space-1 h4">What we’ll do</p>
              <p class="space-top-1 padd7 space-3 text-muted">Teaching about Arts and Design with my drawing skills. It will be useful to make the guest feel free and live with peaceful environment. Interested can get the job with My partners organization and get money from them</p>
            </a>

            <div class="padd7"><hr class="mr0"></div>
            <a href="#" class="hover1 dis_blk rm_link">
            <p class="space-top-3 padd7 space-1 h4">What I’ll provide</p>
            <p class="space-top-1 padd7 space-3 text-muted">Let your guests know if you’ll be including anything for this experience.</p>
            </a>

            <div class="padd7"><hr class="mr0"></div>
            <a href="#" class="hover1 dis_blk rm_link">
            <p class="space-top-3 padd7 space-1 h4">Where we’ll be</p>
            <p class="space-top-1 padd7 space-3 text-muted">Teaching about Arts and Design with my drawing skills. It will be useful to make the guest feel free and live with peaceful environment. Interested can get the job with My partners organization and get money from them at My Home in London</p>
            </a>

            <div class="padd7"><hr class="mr0"></div>
            <a href="#" class="hover1 dis_blk rm_link">
            <p class="space-top-3 padd7 space-1 h4">Notes</p>
            <p class="space-top-1 padd7 space-1 text-muted">Food, drink, and transportation not included.</p>
            <p class="space-top-1 padd7 space-3 text-muted">Is there anything else you’d like guests to know before booking</p>
            </a>

            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d16186.716752637065!2d77.19792643805573!3d28.613310818104065!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390cfd5b347eb62d%3A0x52c2b7494e204dce!2sNew+Delhi%2C+Delhi!5e0!3m2!1sen!2sin!4v1506170612199" width="100%" height="300" frameborder="0" style="border:0" allowfullscreen></iframe>

            <a href="#" class="hover1 dis_blk rm_link">
            <p class="space-top-3 padd7 space-1 h4">About your host, Arun</p>
            <p class="space-top-1 padd7 space-3 text-muted">Teaching about Arts and Design with my drawing skills. It will be useful to make the guest feel free and live with peaceful environment. Interested can get the job with My partners organization and get money from them at My Home in London</p>
            </a>
            
            <div class="padd7"><hr class="mr0"></div>
            <a href="#" class="hover1 dis_blk rm_link padd7">
            <p class="space-0 text-muted">Group size up to 4 guests</p>    
            </a>

          </div>
        </div>
      </div>

    </div>
  </div><!--  main_bar end -->

</div>

<div class="popup hide1">
 <div class="popup_frame popup_frame " data-id="popup1">

  <div class="clearfix pos_rel">

    <div class="col_sm_6 padd4">
      <a href="#" class="h5 rm_link close_pop text-muted"><i class="icon icon2-cancel"></i></a>

      <p class="h2 space-top-8 space-3 bold">Tips</p>
      <p class="h3 space-top-7 space-1 bold">List every place</p>    
      <p class="h4 space-top-1 space-3 line1">Mention any places you’ll visit and hint at why they’re meaningful to you and the overall experience.</p>

      <p class="h3 space-top-5 space-1 bold">Don’t give an address</p>    
      <p class="h4 space-top-1 space-3 line1">Guests will see the exact address once they book. It’s more important to explain what the place is and why it’s worthwhile.</p>      
    </div>

    <div class="col_sm_6 padd4 bg1 text-white pos_abs1 hide_sm">

      <div id="slider_own">
            <ul>
                <li>
                  <div class="tabl space-top-8">
                    <div class="tb_cell">
                      <div class="prof3 pull-right" style="background-image: url('{{url('images/host_experiences/original.jpg')}}');"></div>       
                    </div>
                    <div class="tb_cell">
                      <p class="h4 space-1 bold"> &nbsp; &nbsp;Massimiliano</p>
                      <p class="h5 space-0"> &nbsp; &nbsp;The Florentine Foodie</p> 
                    </div>
                  </div>

                  <p class="h4 space-top-8 space-3 line1">My hometown of Colle Val d’Elsa, about an hour south of Florence, has a local market, where I’ll introduce you to all my favorite producers. Join me as we greet and shop amongst the cheese vendors and local fishmongers and my family’s longtime butcher, as well as the guys behind Tuscany’s best fruit and vegetable stalls.</p>

                  <div class="row space-top-8">
                    <div class="col-sm-6">
                      <p class="h5 space-top-0 space-1">Example 1 of 2</p> 
                    </div>
                    <div class="col-sm-6 text-right">
                      <span class="icon-chevron-left control_prev cursor"></span> &nbsp;&nbsp;<span class="icon-chevron-right control_next cursor"></span>   
                    </div>
                  </div>

                </li>

                <li>
                  <div class="tabl space-top-8">
                    <div class="tb_cell">
                      <div class="prof3 pull-right" style="background-image: url('{{url('images/host_experiences/original1.jpg')}}');"></div>       
                    </div>
                    <div class="tb_cell">
                      <p class="h4 space-1 bold"> &nbsp; &nbsp;Ayanda Cuba</p>
                      <p class="h5 space-0"> &nbsp; &nbsp;Sport is Power</p> 
                    </div>
                  </div>

                  <p class="h4 space-top-8 space-3 line1">You’ll jump right into the action of our weekly basketball coaching clinics, where we do drills and plays for two hours with 60 kids. There are only three of us for all of the kids (girls and boys ages 10-17), so you’ll be helping us with the drills and coaching. Be prepared to be active, sweat, and definitely be on your toes while having fun with the kids.</p>

                  <div class="row space-top-8">
                    <div class="col-sm-6">
                      <p class="h5 space-top-0 space-1">Example 2 of 2</p> 
                    </div>
                    <div class="col-sm-6 text-right">
                      <span class="icon-chevron-left control_prev cursor"></span> &nbsp;&nbsp;<span class="icon-chevron-right control_next cursor"></span>   
                    </div>
                  </div>

                </li>

            </ul>       
        </div>

      

    </div>

  </div>


 </div>
</div>

@stop