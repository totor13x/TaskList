@extends('layouts.admin')

@section('content')
    {{ Auth::user()->toJson() }}
  {!! csrf_field() !!}
  <div id="app">
    <app></app>
  </div>

@endsection