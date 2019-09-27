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
      <li class="active op_lw1"><a href="#" class="rm_link"> Guest requirements</a></li>      
      <li class="op_lw1"><a href="#" class="rm_link"> Group size</a></li>
      <li class="op_lw1"><a href="#" class="rm_link"> Price</a></li>
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
        <p class="h2 bold space-top-8">Who can attend your experience?</p>
        <p class="h4 line1 space-2">Someone booking your experience might also reserve spots for other guests. Add information about age requirements, skill levels, or certifications required to attend your experience.</p>

        <p class="h4 bold line1 space-2 space-top-6">Alcohol</p>
        <div class="tabl space-top-3">
          <div class="tb_cell ver_top">
            <input type="checkbox" name="" id="check1" class="chekbox1">
          </div>
          <div class="tb_cell">
            <label class="h4 cursor padd0" for="check1">My experience includes alcohol. Only guests that meet the legal drinking age will be served.</label>
          </div>
        </div>

        <p class="h4 bold line1 space-1 space-top-6">Minimum age</p>
        <small>Set age limits for guests. Minors can only attend with their legal guardian.</small>

        <div class="row space-top-1">
          <div class="col-sm-5">
            <div class="drp_dwn_cng1 pos_rel dropdown2">
                <i class="icon-chevron-down"></i>
                <select class="" id="lang1">
                  <option value="Select item">18</option>
                  <option value="Meal">17</option>
                  <option value="Drink">16</option>
                  <option value="Tickets">15</option>
                </select>            
            </div>
          </div>
        </div>
        
        <div class="tabl space-top-3">
          <div class="tb_cell ver_top">
            <input type="checkbox" name="" id="check2" class="chekbox1">
          </div>
          <div class="tb_cell">
            <label class="h4 cursor padd0" for="check2">Parents can bring kids under 2 years</label>
          </div>
        </div>

        <p class="h4 bold line1 space-1 space-top-8">Special certifications</p>        
        <textarea class="input_new1 space-top-3" placeholder="Guests must have a scuba diving certificate" rows="3" ></textarea>  

        <p class="h4 bold line1 space-1 space-top-7">Additional requirements</p>        
        <textarea class="input_new1 space-top-3" placeholder="For example, let guests know if they need to be a certain age to get into a venue, need scuba certification, or be able to run 6 miles." rows="3" ></textarea>         

        <p class="h4 bold line1 space-1 space-top-6">Verified ID</p>
        <small>The primary booker has to successfully complete verified ID in order for them and their guests to attend your experience.</small>

        <div class="tabl space-top-2">
          <div class="tb_cell ver_top">
            <input type="checkbox" name="" id="check3" class="chekbox1">
          </div>
          <div class="tb_cell">
            <label class="h4 cursor padd0" for="check3">Require the primary booker to <a href="#" class="text-rausch rm_link"> verify their ID.</a></label>
          </div>
        </div>  


        <button class="btn-primary2 space-top-8 space-8" > <a href="15" class="rm_link"> Next</a></button>

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
 <div class="popup_frame popup_frame padd4 popup_frame_sz1">

    <a href="#" class="h5 rm_link close_pop text-muted"><i class="icon icon2-cancel"></i></a>
    <p class="h3 space-top-3 space-3 bold">Hosting younger guests</p>
    <p class="h4">If you state that your experience is suitable for children, it’s up to you to make sure the activities planned are age-appropriate. <a href="#" class="rm_link text-rausch"> Learn more</a></p>

    <a href="#" class="btn-primary3 space-top-2 btn_sm">I understand</a> 
    <a href="#" class="btn-primary2 space-top-2 btn_sm">Cancel</a>

 </div>
</div>

@stop