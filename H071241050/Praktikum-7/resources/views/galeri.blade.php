@extends('layouts.master')
@section('title','Galeri')
@section('content')
<h2>Galeri Foto</h2>
<div class="gallery">
@foreach($photos as $p)
<div class="gallery-item"><img src="{{ $p }}" alt="foto"></div>
@endforeach
</div>
@endsection