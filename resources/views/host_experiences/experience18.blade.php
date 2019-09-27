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
      <li class="op_lw1"><a href="#" class="rm_link"> Group size</a></li>
      <li class="op_lw1"><a href="#" class="rm_link"> Price</a></li>
      <li class="op_lw1"><a href="#" class="rm_link"> Preparation time</a></li>
      <li class="op_lw1"><a href="#" class="rm_link"> Background info</a></li>
      <li class="op_lw1"><a href="#" class="rm_link"> Packing list</a></li>
    
    </ul>

    <ul class="list-unstyled text-uppercase bold side_menu  space-top-7">
      <li class="op_lw1 active"><a href="#" class="rm_link"></span> REVIEW & SUBMIT</a></li>
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
        <p class="h1 bold space-top-8">Review your experience one last time</p>
        <p class="h4 line1 space-2">To qualify to be on Airbnb, you’re responsible for your experience, so take one last look and make sure:</p>
        
        <div class="tabl space-top-4">
          <div class="tb_cell ver_top">
            <input type="checkbox" name="" id="check1" class="chekbox1 ">
          </div>
          <div class="tb_cell">
            <label class="h4 cursor padd0" for="check1">  You meet Airbnb’s <a href="#" class="rm_link under_line"> quality standards.</a></label>
          </div>
        </div>

        <div class="tabl space-top-3">
          <div class="tb_cell ver_top">
            <input type="checkbox" name="" id="check2" class="chekbox1 ">
          </div>
          <div class="tb_cell">
            <label class="h4 cursor padd0" for="check2">  Your experience complies with local laws. To learn more, <a href="#" class="rm_link under_line"> read</a> about business licensing and other local laws that may apply to your experience.</label>
          </div>
        </div>

        <div class="tabl space-top-3">
          <div class="tb_cell ver_top">
            <input type="checkbox" name="" id="check3" class="chekbox1 ">
          </div>
          <div class="tb_cell">
            <label class="h4 cursor padd0" for="check3">  You agree to the <a href="#" class="rm_link under_line">  makent Additional Terms of Service</a> and confirm that your listing content, photography, graphics and video accurately reflect your experience.</label>
          </div>
        </div>


        <a href="21" class="rm_link"><button class="btn-primary3 space-top-3 space-8" disabled="">Submit</button></a>
      </div>

      <div class="pos_fix1">
        <div class="phone_eff" style="background-image: url('{{url('images/host_experiences/mb_cover.png')}}');">
          <div class="over_scroll1 text-black">

            <div class="bg2 text-center bg_cover" style="background-image: url('{{url('images/host_experiences/pro2.jpg')}}');">
              
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
            <p class="space-top-3 padd7 space-1 h4">About ADR Infotech</p>
            <p class="space-top-1 padd7 text-muted h4 line1">Tell people about the nonprofit organization that you are partnering with. What cause does it support? What is its mission? Tell people about the nonprofit organization that you are partnering with. What cause does it support? What is its mission?</p>

            <p class="space-top-1 padd7 space-3 text-muted h4 line1">This is a <b class="text-black">social impact experience</b>  where 100% of what you pay for this experience goes to ADR Infotech
            <span class="rm_link text-rausch space-top-1 dis_blk">Learn how your money helps</span> </p>
            </a>
            <div class="padd7"><hr class="mr0"></div>
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

<div class="popup hide">
 <div class="popup_frame popup_frame padd4 popup_frame_sz1">
    <a href="#" class="h5 rm_link close_pop text-muted"><i class="icon icon2-cancel"></i></a>
    <p class="h4 space-top-3 space-3">Are you sure you want to change your submission language? If saved, this will delete your descriptions written in English.</p>
    <a href="#" class="btn-primary2">Yes</a>&nbsp; <a href="#" class="btn-primary3">No, undo</a>
 </div>
</div>

@stop