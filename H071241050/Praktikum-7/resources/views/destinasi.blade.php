@extends('layouts.master')
@section('title','Destinasi')
@section('content')
<h2>Destinasi Unggulan</h2>
<div class="grid">
@foreach($destinasi as $d)
<x-card :image="$d['image']" :title="$d['title']">{{ $d['desc'] }}</x-card>
@endforeach
</div>
@endsection