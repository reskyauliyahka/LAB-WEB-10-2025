@extends('layouts.master')
@section('title','Kuliner')
@section('content')
<h2>Makanan Khas</h2>
<div class="grid">
@foreach($kuliner as $k)
<x-card :image="$k['image']" :title="$k['name']">{{ $k['desc'] }}</x-card>
@endforeach
</div>
@endsection