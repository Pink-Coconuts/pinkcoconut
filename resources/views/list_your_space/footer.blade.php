<div class="manage-listing-footer" id="js-manage-listing-footer" data-role="footer">
    <div class="container-brand-dark footer-panel">
      <div class="row row-table"> 
        <div class="col-2 col-foot site"><span class="text-muted">© {{ $site_name }}, Inc.</span></div>
        <div class="col-8 col-foot site-list text-center text-muted">
        @php $i = 0 @endphp
        @foreach($company_pages as $company_page)
          <a href="{{ url($company_page->url) }}" class="text-muted">
            {{ $company_page->name }}
          </a> {{ ($i+1 == $company_pages->count()) ? '|' : '|' }}
          @php $i++ @endphp
        @endforeach
         <a href="{{ url('contact') }}" class="text-muted">{{ trans('messages.contactus.contactus') }}</a>
        </div>
        <div class="col-2 col-foot">
          <div class="language-curr-picker pull-right">
            <div class="select select-large select-block row-space-2">
  <label id="language-selector-label" class="screen-reader-only">
    {{ trans('messages.footer.change_language') }}
  </label>
  {!! Form::select('language',$language, (Session::get('language')) ? Session::get('language') : $default_language[0]->value, ['class' => 'language-selector', 'aria-labelledby' => 'language-selector-label', 'id' => 'language_footer']) !!}
</div>

          </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
    var map_key = "{!! $map_key !!}";

    // var name = "{!! $result->name !!}";

    // var summary = "{!! $result->summary !!}";
    // console.log(summary);
  </script>