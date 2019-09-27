@extends('template')
@section('main')
<main id="site-content" role="main">
      
<div class="page-container page-container-responsive row-space-top-4 row-space-4">
  <div class="row">
    <div class="col-4 col-center">
      <div id="account_recovery_panel" class="security-check-panel panel text-center">
        <div class="panel-body">
         <p>
          </p><div class="icon-circle">
           <i class="icon icon-user icon-size-4"></i>
          </div>
         <p></p>
          <h3 class="text-special">
            {{ trans('messages.profile.account_disabled') }}
          </h3>
          <p>
          {{ trans('messages.profile.pls_email_us') }}
          </p>
          <form action="mailto:{{ $admin_email }}" method="GET">
            <button class="search-button form-inline btn btn-primary btn-large">
              {{ trans('messages.profile.email_us') }}
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

    </main>
    @stop