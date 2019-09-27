@extends('vendor.installer.layouts.master')

@section('title', trans('messages.install.settings.title'))
@section('container')
{!! Form::open(['url'=>route('LaravelInstaller::database'),'method'=>'post']) !!}
<ul class="list">
    <li class="list__item list__item--settings">
        Site Name<em class="error">*</em>
        {!! Form::text('site_name') !!}
    @if ($errors->has('site_name')) <span class="error">{{ $errors->first('site_name') }}</span> @endif
    </li>
    <li class="list__item list__item--settings">
        Admin Username<em class="error">*</em>
        {!! Form::text('username') !!}
    @if ($errors->has('username')) <span class="error">{{ $errors->first('username') }}</span> @endif
    </li>
    <li class="list__item list__item--settings">
        Admin Email<em class="error">*</em>
        {!! Form::email('email') !!}
    @if ($errors->has('email')) <span class="error">{{ $errors->first('email') }}</span> @endif
    </li>
    <li class="list__item list__item--settings">
        Admin Password<em class="error">*</em>
        {!! Form::text('password') !!}
    @if ($errors->has('password')) <span class="error">{{ $errors->first('password') }}</span> @endif
    </li>
</ul>
<div class="buttons">
    <button class="button" type="submit">
        {{ trans('messages.install.next') }}
    </button>
</div>
{!! Form::close() !!}
@stop
