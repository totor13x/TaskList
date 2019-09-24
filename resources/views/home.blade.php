@extends('layouts.app')

@section('content')

  {!! csrf_field() !!}
  <div id="app">
    <app></app>
  </div>

@endsection