@extends('template')

@section('main')


<div class="inner">

  <div class="page-container-responsive">

  	<div class="linkpath">

  		<ul>

  			<li><a href="#"`	>1. Review guest requirements</a></li>>
  			<li><a href="#">2. Who’s coming</a></li>>
  			<li><a href="#">3. Confirm and pay</a></li>

  		</ul>

  		<div class="step">Step 1 of 3</div>

  	</div>

  	<div class="firtpage">

	  	<div class="col-md-7">

	  		<div class="reguest">

	  			<h1> 	Review guest requirements</h1>

	  			<h4> From the host</h4>

	  			<p>All ages welcome for lessons and dancing in the Mojito Room. Guests need to be of legal drinking age to stay after the lessons in the nightclub.Dress to impress like you are out for a night of dinner & dancing. Comfortable shoes.Experience it all in one fantastic night. Restaurant has food & drink available.</p>

	  		</div>

	  		<div class="whocan reguest">
	  		<h4> Who can come</h4>

	  		<p> Guests ages 18 and up can attend.</p>

	  		<div class="butget"><button>Next</button></div>

	  		</div>

	  	</div>


	  	<div class="col-md-5">

	  		<div class="vibrate">

	  			<div class="holevib">

		  			<div class="vibsale">

		  				<h3> A VIBRANT SALSA NIGHT</h3>
		  				<div class="hours">2 hour experience</div>
		  				<div class="hostdate">Hosted by Alberto, David, Flor, Nayara</div>

		  			</div>

		  			<div class="vibimg">
		  			
		  					<img src="{{url('images/host_experiences/img4.jpg')}}">

		  			</div>

		  		</div>

	  			<div class="vibtime">

		  			<div class="timeer hours">Thu, 9th Nov</div>

		  			<div class="timeout hours">20.00-22.00</div>

	  			</div>

	  			<div class="vibtime1">

	  				<div class="timer hours">Drink and ticket provided</div>

	  			</div>

	  			<div class="valed">

	  				<span class="val">₹3,268 x 1 guest</span>
	  				<span class="val1">₹3,268</span>

	  			</div>

	  			<div class="valed1">

	  				<span class="val">Total(INR)</span>
	  				<span class="val2">₹3,268</span>
	  				<div class="val3">The adjusted exchange rate for this purchase is ₹1.00 INR to $0.0150 USD.</div>

	  			</div>

	  			<div class="cancel">

	  				<a href="#">Cancellation Policy</a>
	  				<div class="getpol">Get a <span>full refund</span> if you cancel within 24 hours of purchase</div>

	  			</div>

	  		</div>

	  		

	  	</div>

	</div>

	<div class="forbot">

		<div class="holeslider">
			<div class="amut"><span>₹3,456</span>for 1 guest</div>
			<div class="seelink"><a href="#">See details</a></div>
		</div>

		<div class="nxt">Next</div>

	</div>



	</div>

  </div>

 

<div class="vib">

		<div class="vibrate1">

	  			<div class="holevib">

	  			<div class="newe"><i class="fa fa-times" aria-hidden="true"></i></div>

		  			<div class="vibsale">

		  				<h3> A VIBRANT SALSA NIGHT</h3>
		  				<div class="hours">2 hour experience</div>
		  				<div class="hostdate">Hosted by Alberto, David, Flor, Nayara</div>

		  			</div>

		  			<div class="vibimg">
		  			
		  					<img src="{{url('images/host_experiences/img4.jpg')}}">

		  			</div>

		  		</div>

	  			<div class="vibtime">

		  			<div class="timeer hours">Thu, 9th Nov</div>

		  			<div class="timeout hours">20.00-22.00</div>

	  			</div>

	  			<div class="vibtime1">

	  				<div class="timer hours">Drink and ticket provided</div>

	  			</div>

	  			<div class="valed">

	  				<span class="val">₹3,268 x 1 guest</span>
	  				<span class="val1">₹3,268</span>

	  			</div>

	  			<div class="valed1">

	  				<span class="val">Total(INR)</span>
	  				<span class="val2">₹3,268</span>
	  				<div class="val3">The adjusted exchange rate for this purchase is ₹1.00 INR to $0.0150 USD.</div>

	  			</div>

	  			<div class="cancel">

	  				<a href="#">Cancellation Policy</a>
	  				<div class="getpol">Get a <span>full refund</span> if you cancel within 24 hours of purchase</div>

	  			</div>

	  		</div>

	  	</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script>
$(document).ready(function(){
    $(".seelink a,.newe").click(function(){
        $(".vib").slideToggle();
    });
   });
</script>
@stop
