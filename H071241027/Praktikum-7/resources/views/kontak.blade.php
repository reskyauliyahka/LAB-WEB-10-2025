@extends('layouts.master')

@section('content')
    <h2>Hubungi Kami</h2>
    <p>Punya pertanyaan atau ingin berbagi cerita? Silakan isi form di bawah ini.</p>

    <div class="contact-form">
        <form>
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" placeholder="Masukkan nama Anda">
            </div>
            
            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" id="email" name="email" placeholder="contoh@email.com">
            </div>
            
            <div class="form-group">
                <label for="pesan">Pesan Anda</label>
                <textarea id="pesan" name="pesan" placeholder="Tulis pesan Anda di sini..."></textarea>
            </div>
            
            <button type="submit" class="form-button">Kirim Pesan</button>
        </form>
    </div>
@endsection