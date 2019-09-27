<!DOCTYPE html>
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>

         {!! Html::style('css/common.css?v='.$version) !!}
      {!! Html::style('css/slider/default.css?v='.$version) !!}
      {!! Html::style('css/slider/jquery.ad-gallery.css?v='.$version) !!}
      {!! Html::style('css/slider/main.css?v='.$version) !!}

    </head>

    <body style="background:rgba(0,0,0,0)">


    <div id="gallery" class="ad-gallery">
      <div class="ad-image-wrapper">
      </div>
      <div class="ad-nav">
        <div class="ad-controls">
        </div>

        <div class="ad-thumbs slider-thumbs">
          <ul class="ad-thumb-list">
          @foreach($rooms_photos as $row_photos)
            <li>
              <a href="{{ $row_photos->slider_image_name }}" class="image0">
                
              <img src="{{ $row_photos->slider_image_name }}" title="{{ $row_photos->highlights }}" >
              </a>
            </li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>       

    </body>
</html>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{ $map_key }}&sensor=false&libraries=places&language={{ (Session::get('language')) ? Session::get('language') : $default_language[0]->value }}"></script>
{!! Html::script('js/jquery-1.11.3.js') !!}

{!! Html::script('js/angular.js') !!}
 <script> 
    var app = angular.module('App', [], function($interpolateProvider) {
        /*$interpolateProvider.startSymbol('<%');
        $interpolateProvider.endSymbol('%>');*/
    });
    var show_photo_list = '{!! trans('messages.rooms.show_photo_list') !!}';
    var hide_photo_list = '{!! trans('messages.rooms.hide_photo_list') !!}';
    

    </script>
{!! Html::script('js/rooms.js?v='.$version) !!}

<style type="text/css">

@media (max-width: 767px) {
.ad-gallery .ad-image-wrapper .ad-image{
        width: 70% !important;
    left: 16% !important;
}
.ad-gallery .ad-image-wrapper .ad-next .ad-next-image {
    background: url(../../images/ad_next.png) !important;
    width: 48px;
    height: 34px !important;
    right: -22px !important;
    color: #fff;
    background-size: 73% !important;
    background-position: center center !important;
    left: auto !important;
}
.ad-gallery .ad-image-wrapper .ad-prev .ad-prev-image, .ad-gallery .ad-image-wrapper .ad-next .ad-next-image {
    background: url(../../images/ad_prev.png);
    width: 48px;
    height: 35px;
    /* display: none; */
    position: absolute;
    top: 47%;
    left: -13px;
    z-index: 101;
    color: #fff;
    background-size: 73%;
    background-position: center center;
}
.ad-thumbs{top:90px !important;}
}
</style>