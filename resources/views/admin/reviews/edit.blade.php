@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Edit Review
      </h1>
      <ol class="breadcrumb">
        <li><a href="../dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="../reviews">Reviews</a></li>
        <li class="active">Edit</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- right column -->
        <div class="col-md-8 col-sm-offset-2">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Edit Review Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            {!! Form::open(['url' => ADMIN_URL.'/edit_review/'.$result[0]->id, 'class' => 'form-horizontal']) !!}
              <div class="box-body">
              <span class="text-danger">(*)Fields are Mandatory</span>
                <div class="form-group">
                  <label for="input_reservation_id" class="col-sm-3 control-label">Reservation Id</label>
                  <div class="col-sm-6">
                    {!! Form::text('reservation_id', $result[0]->reservation_id, ['class' => 'form-control', 'id' => 'input_reservation_id', 'readonly' => 'true']) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_room_name" class="col-sm-3 control-label">Room Name</label>
                  <div class="col-sm-6">
                    {!! Form::text('room_name', $result[0]->room_name, ['class' => 'form-control', 'id' => 'input_room_name', 'readonly' => 'true']) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_user_from" class="col-sm-3 control-label">User From</label>
                  <div class="col-sm-6">
                    {!! Form::text('user_from', $result[0]->user_from, ['class' => 'form-control', 'id' => 'input_user_from', 'readonly' => 'true']) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_user_to" class="col-sm-3 control-label">User To</label>
                  <div class="col-sm-6">
                    {!! Form::text('user_to', $result[0]->user_to, ['class' => 'form-control', 'id' => 'input_user_to', 'readonly' => 'true']) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_review_by" class="col-sm-3 control-label">Review By</label>
                  <div class="col-sm-6">
                    {!! Form::text('review_by', ucfirst($result[0]->review_by), ['class' => 'form-control', 'id' => 'input_review_by', 'readonly' => 'true']) !!}
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_comments" class="col-sm-3 control-label">Comments<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::textarea('comments', $result[0]->comments, ['class' => 'form-control', 'id' => 'input_comments', 'placeholder' => 'Comments']) !!}
                    <span class="text-danger">{{ $errors->first('comments') }}</span>
                  </div>
                </div>
                @if($result[0]->review_by=='host')
                <div class="form-group">
                  <label for="input_private_feedback" class="col-sm-3 control-label">Private Guest Feedback</label>
                  <div class="col-sm-6">
                    {!! Form::textarea('private_feedback', $result[0]->private_feedback, ['class' => 'form-control', 'id' => 'input_private_feedback', 'readonly' => 'true']) !!}
                    <span class="text-danger">{{ $errors->first('private_feedback') }}</span>
                  </div>
                </div>

 <div class="form-group">
   <label for="input_cleanliness" class="col-sm-3 control-label">Cleanliness</label>
   <div class="star-rating">
    <input type="radio" value="5" name="cleanliness_5" id="review_cleanliness_5" class="star-rating-input needsclick" {{ ($result[0]->cleanliness == 5) ? 'checked="true"' : '' }} disabled />
    <label for="review_cleanliness_5" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="4" name="cleanliness_4" id="review_cleanliness_4" class="star-rating-input needsclick" {{ ($result[0]->cleanliness == 4) ? 'checked="true"' : '' }} disabled />
    <label for="review_cleanliness_4" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="3" name="cleanliness_3" id="review_cleanliness_3" class="star-rating-input needsclick" {{ ($result[0]->cleanliness == 3) ? 'checked="true"' : '' }} disabled />
    <label for="review_cleanliness_3" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="2" name="cleanliness_2" id="review_cleanliness_2" class="star-rating-input needsclick" {{ ($result[0]->cleanliness == 2) ? 'checked="true"' : '' }} disabled />
    <label for="review_cleanliness_2" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="1" name="cleanliness_1" id="review_cleanliness_1" class="star-rating-input needsclick" {{ ($result[0]->cleanliness == 1) ? 'checked="true"' : '' }} disabled />
    <label for="review_cleanliness_1" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
</div>
</div>

<div class="form-group">
   <label for="input_communication" class="col-sm-3 control-label">Communication</label>
   <div class="star-rating">
    <input type="radio" value="5" name="communication_5" id="review_communication_5" class="star-rating-input needsclick" {{ ($result[0]->communication == 5) ? 'checked="true"' : '' }} disabled />
    <label for="review_communication_5" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="4" name="communication_4" id="review_communication_4" class="star-rating-input needsclick" {{ ($result[0]->communication == 4) ? 'checked="true"' : '' }} disabled />
    <label for="review_communication_4" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="3" name="communication_3" id="review_communication_3" class="star-rating-input needsclick" {{ ($result[0]->communication == 3) ? 'checked="true"' : '' }} disabled />
    <label for="review_communication_3" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="2" name="communication_2" id="review_communication_2" class="star-rating-input needsclick" {{ ($result[0]->communication == 2) ? 'checked="true"' : '' }} disabled />
    <label for="review_communication_4" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="1" name="communication_1" id="review_communication_1" class="star-rating-input needsclick" {{ ($result[0]->communication == 1) ? 'checked="true"' : '' }} disabled />
    <label for="review_communication_5" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    </div>
  </div>

  <div class="form-group">
   <label for="input_respect_house_rules" class="col-sm-3 control-label">Observance of House Rules</label>
   <div class="star-rating">
    <input type="radio" value="5" name="respect_house_rules_5" id="review_respect_house_rules_5" class="star-rating-input needsclick" {{ ($result[0]->respect_house_rules == 5) ? 'checked="true"' : '' }} disabled />
    <label for="review_respect_house_rules_5" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="4" name="respect_house_rules_4" id="review_respect_house_rules_4" class="star-rating-input needsclick" {{ ($result[0]->respect_house_rules == 4) ? 'checked="true"' : '' }} disabled />
    <label for="review_respect_house_rules_4" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="3" name="respect_house_rules_3" id="review_respect_house_rules_3" class="star-rating-input needsclick" {{ ($result[0]->respect_house_rules == 3) ? 'checked="true"' : '' }} disabled />
    <label for="review_respect_house_rules_3" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="2" name="respect_house_rules_2" id="review_respect_house_rules_2" class="star-rating-input needsclick" {{ ($result[0]->respect_house_rules == 2) ? 'checked="true"' : '' }} disabled />
    <label for="review_respect_house_rules_2" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="1" name="respect_house_rules_1" id="review_respect_house_rules_1" class="star-rating-input needsclick" {{ ($result[0]->respect_house_rules == 1) ? 'checked="true"' : '' }} disabled />
    <label for="review_respect_house_rules_1" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
      </div>
    </div>
                
@else
<div class="form-group">
                  <label for="input_love_comments" class="col-sm-3 control-label">Love Comments</label>
                  <div class="col-sm-6">
                    {!! Form::textarea('love_comments', $result[0]->love_comments, ['class' => 'form-control', 'id' => 'input_love_comments', 'readonly' => 'true']) !!}
                    <span class="text-danger">{{ $errors->first('love_comments') }}</span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="input_improve_comments" class="col-sm-3 control-label">Improve Comments</label>
                  <div class="col-sm-6">
                    {!! Form::textarea('improve_comments', $result[0]->improve_comments, ['class' => 'form-control', 'id' => 'input_improve_comments', 'readonly' => 'true']) !!}
                    <span class="text-danger">{{ $errors->first('improve_comments') }}</span>
                  </div>
                </div>

          <div class="form-group">
           <label for="input_rating" class="col-sm-3 control-label">Overall Experience</label>
           <div class="star-rating">
            <input type="radio" value="5" name="rating_5" id="review_rating_5" class="star-rating-input needsclick" {{ ($result[0]->rating == 5) ? 'checked="true"' : '' }} disabled />
            <label for="review_rating_5" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
            <input type="radio" value="4" name="rating_4" id="review_rating_4" class="star-rating-input needsclick" {{ ($result[0]->rating == 4) ? 'checked="true"' : '' }} disabled />
            <label for="review_rating_4" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
            <input type="radio" value="3" name="rating_3" id="review_rating_3" class="star-rating-input needsclick" {{ ($result[0]->rating == 3) ? 'checked="true"' : '' }} disabled />
            <label for="review_rating_3" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
            <input type="radio" value="2" name="rating_2" id="review_rating_2" class="star-rating-input needsclick" {{ ($result[0]->rating == 2) ? 'checked="true"' : '' }} disabled />
            <label for="review_rating_2" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
            <input type="radio" value="1" name="rating_1" id="review_rating_1" class="star-rating-input needsclick" {{ ($result[0]->rating == 1) ? 'checked="true"' : '' }} disabled />
            <label for="review_rating_1" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
        </div>
        </div>

        <div class="form-group">
           <label for="input_rating" class="col-sm-3 control-label">Pride Inclusion Score</label>
           <div class="star-rating">
             @for ($i = 5; $i >= 1; $i--)
            <input type="radio" value="{{ $i }}" name="pride_comments" id="pride_comments_{{ $i }}" class="star-rating-input needsclick valid" {{ ($result[0]->pride_comments == $i) ? 'checked="true"' : '' }} disabled>
            <label for="pride_comments_{{ $i }}" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
            @endfor
           </div>
       </div>


<div class="form-group">
   <label for="input_accuracy" class="col-sm-3 control-label">Accuracy</label>
   <div class="star-rating">
    <input type="radio" value="5" name="accuracy_5" id="review_accuracy_5" class="star-rating-input needsclick" {{ ($result[0]->accuracy == 5) ? 'checked="true"' : '' }} disabled />
    <label for="review_accuracy_5" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="4" name="accuracy_4" id="review_accuracy_4" class="star-rating-input needsclick" {{ ($result[0]->accuracy == 4) ? 'checked="true"' : '' }} disabled />
    <label for="review_accuracy_4" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="3" name="accuracy_3" id="review_accuracy_3" class="star-rating-input needsclick" {{ ($result[0]->accuracy == 3) ? 'checked="true"' : '' }} disabled />
    <label for="review_accuracy_3" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="2" name="accuracy_2" id="review_accuracy_2" class="star-rating-input needsclick" {{ ($result[0]->accuracy == 2) ? 'checked="true"' : '' }} disabled />
    <label for="review_accuracy_2" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="1" name="accuracy_1" id="review_accuracy_1" class="star-rating-input needsclick" {{ ($result[0]->accuracy == 1) ? 'checked="true"' : '' }} disabled />
    <label for="review_accuracy_1" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
</div>
</div>

<div class="form-group">
                  <label for="input_accuracy_comments" class="col-sm-3 control-label">Accuracy feedback</label>
                  <div class="col-sm-6">
                    {!! Form::textarea('accuracy_comments', $result[0]->accuracy_comments, ['class' => 'form-control', 'id' => 'input_accuracy_comments', 'readonly' => 'true']) !!}
                    <span class="text-danger">{{ $errors->first('accuracy_comments') }}</span>
                  </div>
                </div>

                <div class="form-group">
   <label for="input_private_feedback" class="col-sm-3 control-label">Cleanliness</label>
   <div class="star-rating">
    <input type="radio" value="5" name="cleanliness_5" id="review_cleanliness_5" class="star-rating-input needsclick" {{ ($result[0]->cleanliness == 5) ? 'checked="true"' : '' }} disabled />
    <label for="review_cleanliness_5" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="4" name="cleanliness_4" id="review_cleanliness_4" class="star-rating-input needsclick" {{ ($result[0]->cleanliness == 4) ? 'checked="true"' : '' }} disabled />
    <label for="review_cleanliness_4" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="3" name="cleanliness_3" id="review_cleanliness_3" class="star-rating-input needsclick" {{ ($result[0]->cleanliness == 3) ? 'checked="true"' : '' }} disabled />
    <label for="review_cleanliness_3" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="2" name="cleanliness_2" id="review_cleanliness_2" class="star-rating-input needsclick" {{ ($result[0]->cleanliness == 2) ? 'checked="true"' : '' }} disabled />
    <label for="review_cleanliness_2" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="1" name="cleanliness_1" id="review_cleanliness_1" class="star-rating-input needsclick" {{ ($result[0]->cleanliness == 1) ? 'checked="true"' : '' }} disabled />
    <label for="review_cleanliness_1" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
</div>
</div>

                <div class="form-group">
                  <label for="input_cleanliness_comments" class="col-sm-3 control-label">Cleanliness feedback</label>
                  <div class="col-sm-6">
                    {!! Form::textarea('cleanliness_comments', $result[0]->cleanliness_comments, ['class' => 'form-control', 'id' => 'input_cleanliness_comments', 'readonly' => 'true']) !!}
                    <span class="text-danger">{{ $errors->first('cleanliness_comments') }}</span>
                  </div>
                </div>

                <div class="form-group">
   <label for="input_checkin" class="col-sm-3 control-label">Arrival</label>
   <div class="star-rating">
    <input type="radio" value="5" name="checkin_5" id="review_checkin_5" class="star-rating-input needsclick" {{ ($result[0]->checkin == 5) ? 'checked="true"' : '' }} disabled />
    <label for="review_checkin_5" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="4" name="checkin_4" id="review_checkin_4" class="star-rating-input needsclick" {{ ($result[0]->checkin == 4) ? 'checked="true"' : '' }} disabled />
    <label for="review_checkin_4" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="3" name="checkin3" id="review_checkin_3" class="star-rating-input needsclick" {{ ($result[0]->checkin == 3) ? 'checked="true"' : '' }} disabled />
    <label for="review_checkin_3" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="2" name="checkin_2" id="review_checkin_2" class="star-rating-input needsclick" {{ ($result[0]->checkin == 2) ? 'checked="true"' : '' }} disabled />
    <label for="review_checkin_2" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="1" name="checkin_1" id="review_checkin_1" class="star-rating-input needsclick" {{ ($result[0]->checkin == 1) ? 'checked="true"' : '' }} disabled />
    <label for="review_checkin_1" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
</div>
</div>

                <div class="form-group">
                  <label for="input_checkin_comments" class="col-sm-3 control-label">Arrival feedback</label>
                  <div class="col-sm-6">
                    {!! Form::textarea('checkin_comments', $result[0]->checkin_comments, ['class' => 'form-control', 'id' => 'input_checkin_comments', 'readonly' => 'true']) !!}
                    <span class="text-danger">{{ $errors->first('checkin_comments') }}</span>
                  </div>
                </div>

                <div class="form-group">
   <label for="input_amenities" class="col-sm-3 control-label">Amenities</label>
   <div class="star-rating">
    <input type="radio" value="5" name="amenities_5" id="review_amenities_5" class="star-rating-input needsclick" {{ ($result[0]->amenities == 5) ? 'checked="true"' : '' }} disabled />
    <label for="review_amenities_5" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="4" name="amenities_4" id="review_amenities_4" class="star-rating-input needsclick" {{ ($result[0]->amenities == 4) ? 'checked="true"' : '' }} disabled />
    <label for="review_amenities_4" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="3" name="amenities_3" id="review_amenities_3" class="star-rating-input needsclick" {{ ($result[0]->amenities == 3) ? 'checked="true"' : '' }} disabled />
    <label for="review_amenities_3" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="2" name="amenities_2" id="review_amenities_2" class="star-rating-input needsclick" {{ ($result[0]->amenities == 2) ? 'checked="true"' : '' }} disabled />
    <label for="review_amenities_2" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="1" name="amenities_1" id="review_amenities_1" class="star-rating-input needsclick" {{ ($result[0]->amenities == 1) ? 'checked="true"' : '' }} disabled />
    <label for="review_amenities_1" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
</div>
</div>

                <div class="form-group">
                  <label for="input_checkin_comments" class="col-sm-3 control-label">Amenities feedback</label>
                  <div class="col-sm-6">
                    {!! Form::textarea('amenities_comments', $result[0]->amenities_comments, ['class' => 'form-control', 'id' => 'input_amenities_comments', 'readonly' => 'true']) !!}
                    <span class="text-danger">{{ $errors->first('amenities_comments') }}</span>
                  </div>
                </div>

                <div class="form-group">
   <label for="input_communication" class="col-sm-3 control-label">Communication</label>
   <div class="star-rating">
    <input type="radio" value="5" name="communication_5" id="review_communication_5" class="star-rating-input needsclick" {{ ($result[0]->communication == 5) ? 'checked="true"' : '' }} disabled />
    <label for="review_communication_5" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="4" name="communication_4" id="review_communication_4" class="star-rating-input needsclick" {{ ($result[0]->communication == 4) ? 'checked="true"' : '' }} disabled />
    <label for="review_communication_4" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="3" name="communication_3" id="review_communication_3" class="star-rating-input needsclick" {{ ($result[0]->communication == 3) ? 'checked="true"' : '' }} disabled />
    <label for="review_communication_3" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="2" name="communication_2" id="review_communication_2" class="star-rating-input needsclick" {{ ($result[0]->communication == 2) ? 'checked="true"' : '' }} disabled />
    <label for="review_communication_2" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="1" name="communication_1" id="review_communication_1" class="star-rating-input needsclick" {{ ($result[0]->communication == 1) ? 'checked="true"' : '' }} disabled />
    <label for="review_communication_1" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
</div>
</div>

                <div class="form-group">
                  <label for="input_communication_comments" class="col-sm-3 control-label">Communication feedback</label>
                  <div class="col-sm-6">
                    {!! Form::textarea('communication_comments', $result[0]->communication_comments, ['class' => 'form-control', 'id' => 'input_communication_comments', 'readonly' => 'true']) !!}
                    <span class="text-danger">{{ $errors->first('communication_comments') }}</span>
                  </div>
                </div>

                <div class="form-group">
   <label for="input_location" class="col-sm-3 control-label">Location</label>
   <div class="star-rating">
    <input type="radio" value="5" name="location_5" id="review_location_5" class="star-rating-input needsclick" {{ ($result[0]->location == 5) ? 'checked="true"' : '' }} disabled />
    <label for="review_location_5" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="4" name="location_4" id="review_location_4" class="star-rating-input needsclick" {{ ($result[0]->location == 4) ? 'checked="true"' : '' }} disabled />
    <label for="review_location_4" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="3" name="location_3" id="review_location_3" class="star-rating-input needsclick" {{ ($result[0]->location == 3) ? 'checked="true"' : '' }} disabled />
    <label for="review_location_3" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="2" name="location_2" id="review_location_2" class="star-rating-input needsclick" {{ ($result[0]->location == 2) ? 'checked="true"' : '' }} disabled />
    <label for="review_location_2" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="1" name="location_1" id="review_location_1" class="star-rating-input needsclick" {{ ($result[0]->location == 1) ? 'checked="true"' : '' }} disabled />
    <label for="review_location_1" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
</div>
</div>

<div class="form-group">
                  <label for="input_location_comments" class="col-sm-3 control-label">Location feedback</label>
                  <div class="col-sm-6">
                    {!! Form::textarea('location_comments', $result[0]->location_comments, ['class' => 'form-control', 'id' => 'input_location_comments', 'readonly' => 'true']) !!}
                    <span class="text-danger">{{ $errors->first('location_comments') }}</span>
                  </div>
                </div>

                <div class="form-group">
   <label for="input_value" class="col-sm-3 control-label">Value</label>
   <div class="star-rating">
    <input type="radio" value="5" name="value_5" id="review_value_5" class="star-rating-input needsclick" {{ ($result[0]->value == 5) ? 'checked="true"' : '' }} disabled />
    <label for="review_value_5" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="4" name="value_4" id="review_value_4" class="star-rating-input needsclick" {{ ($result[0]->value == 4) ? 'checked="true"' : '' }} disabled />
    <label for="review_value_4" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="3" name="value_3" id="review_value_3" class="star-rating-input needsclick" {{ ($result[0]->value == 3) ? 'checked="true"' : '' }} disabled />
    <label for="review_value_3" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="2" name="value_2" id="review_value_2" class="star-rating-input needsclick" {{ ($result[0]->value == 2) ? 'checked="true"' : '' }} disabled />
    <label for="review_value_2" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
    <input type="radio" value="1" name="value_1" id="review_value_1" class="star-rating-input needsclick" {{ ($result[0]->value == 1) ? 'checked="true"' : '' }} disabled />
    <label for="review_value_1" class="star-rating-star js-star-rating needsclick"><i class="icon icon-star icon-size-2 needsclick"></i>&nbsp;</label>
</div>
</div>

                <div class="form-group">
                  <label for="input_value_comments" class="col-sm-3 control-label">Value feedback</label>
                  <div class="col-sm-6">
                    {!! Form::textarea('value_comments', $result[0]->value_comments, ['class' => 'form-control', 'id' => 'input_value_comments', 'readonly' => 'true']) !!}
                    <span class="text-danger">{{ $errors->first('value_comments') }}</span>
                  </div>
                </div>
                 @endif

              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right" name="submit" value="submit">Submit</button>
                 <button type="submit" class="btn btn-default pull-left" name="cancel" value="cancel">Cancel</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @push('scripts')
<script>
  $('#input_dob').datepicker({ 'format': 'dd-mm-yyyy'});
</script>
@endpush
@stop