@extends('vendor.installer.layouts.master')

@section('title', trans('messages.install.welcome.title'))
@section('container')
    <p class="paragraph">{{ trans('messages.install.welcome.message') }}</p>
    <div class="buttons">
        <a href="{{ route('LaravelInstaller::environment') }}" class="button">{{ trans('messages.install.next') }}</a>
    </div>
@stop