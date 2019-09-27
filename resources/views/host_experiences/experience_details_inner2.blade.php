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
  		<div class="step">Step 2 of 3</div>

  	</div>

  	<div class="firtpage">

	  	<div class="col-md-7">

	  		<div class="reguest reguest1">

	  			<h1> Who's coming?</h1>

	  			<div class="username">
	  				<div class="usename">Yasmin</div>

	  				<div class="useimg">
	  					<div class="forleft">
	  						<img src="{{url('images/host_experiences/pro-1.jpg')}}">
	  					</div>
	  				</div>

	  			</div>

	  			<div class="foradd">

	  				<div class="guest">

	  					<span class="guestadd">Guest<span>2</span></span>
	  					<span class="remover"><a href="#">Remove</a></span>

	  				</div>

	  				<div class="formfill">
	  						
	  					<p>Keep your guests in the loop. Add their email and we'll send them the itinerary.</p>

	  					<div class="formlist">

	  						<div class="listfor">

	  							<label>First Name</label>
	  							<input type="text" name="">

	  						</div>

	  						<div class="listfor1">

	  							<label>Last Name</label>
	  							<input type="text" name="">
	  							
	  						</div>

	  						<div class="listfor2">

	  							<label>Email (optional)</label>
	  							<input type="text" name="">
	  							
	  						</div>
	  					</div>

	  				</div>

	  				<a href="#" class="formore">

	  					<span class="adduse">Add another guest</span>
	  					<span class="plus"><span>+</span></span>

	  				</a>

	  			</div>
	  			
	  		</div>

	  		<div class="whocan reguest">
	  	

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
