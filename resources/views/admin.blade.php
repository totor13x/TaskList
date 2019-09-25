@extends('layouts.admin')

@section('content')
  {!! csrf_field() !!}
  <div id="app">
    <app :admin="{{ Auth::user()->toJson() }}"></app>
  </div>

@endsection