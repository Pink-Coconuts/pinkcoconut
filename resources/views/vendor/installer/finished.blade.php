@extends('vendor.installer.layouts.master')

@section('title', trans('messages.install.final.title'))
@section('container')
    <p class="paragraph">{{ session('message')['message'] }}</p>
    <div class="buttons">
        <a href="{{ url('/') }}" class="button">{{ trans('messages.install.final.exit') }}</a>
    </div>
@stop