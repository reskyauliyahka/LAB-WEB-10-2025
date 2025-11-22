@extends('layouts.master')

@section('title', 'Kontak')

@section('content')
  <h2>Kontak Kami</h2>
  <p><strong>Telepon:</strong> {{ $contact['phone'] }}</p>
  <p><strong>Email:</strong> {{ $contact['email'] }}</p>
  <p><strong>Alamat:</strong> {{ $contact['address'] }}</p>

  <h3>Form Kontak (tidak berfungsi)</h3>
  <form>
    <label>Nama</label>
    <input type="text" placeholder="Nama Anda">
    <label>Email</label>
    <input type="email" placeholder="email@example.com">
    <label>Pesan</label>
    <textarea placeholder="Tulis pesan..."></textarea>
    <button type="submit" class="btn">Kirim</button>
  </form>
@endsection
