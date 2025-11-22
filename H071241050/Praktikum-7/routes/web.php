<?php


use Illuminate\Support\Facades\Route;


Route::get('/', function () {
$intro = "Selamat datang di Eksplor Pariwisata Nusantara - Yogyakarta (contoh). Temukan destinasi, kuliner, dan budaya lokal kami.";
return view('home', compact('intro'));
});


Route::get('/destinasi', function () {
$destinasi = [
[
'title' => 'Candi Prambanan',
'desc' => 'Kompleks candi Hindu terbesar di Indonesia, penuh relief dan arsitektur indah.',
'image' => asset('images/prambanan.jpg')
],
[
'title' => 'Keraton Yogyakarta',
'desc' => 'Pusat kebudayaan Jawa, tempat tinggal Sultan dan tempat pertunjukan tradisi.',
'image' => asset('images/keraton.jpg')
],
[
'title' => 'Taman Sari',
'desc' => 'Bekas taman kerajaan dengan kolam dan lorong-lorong sejarah.',
'image' => asset('images/tamansari.jpg')
],
];
return view('destinasi', compact('destinasi'));
});


Route::get('/kuliner', function () {
$kuliner = [
['name'=>'Gudeg', 'desc'=>'Makanan khas Yogyakarta berbahan nangka muda dan santan.', 'image'=>asset('images/gudeg.jpg')],
['name'=>'Bakpia Pathok', 'desc'=>'Kue kecil isi kacang hijau, oleh-oleh populer.', 'image'=>asset('images/bakpia.jpg')],
['name'=>'Mie Lethek', 'desc'=>'Mie tradisional khas Bantul.', 'image'=>asset('images/mielethek.jpg')],
];
return view('kuliner', compact('kuliner'));
});


Route::get('/galeri', function () {
$photos = [
asset('images/galeri1.jpg'),
asset('images/galeri2.jpg'),
asset('images/galeri3.jpg'),
asset('images/galeri4.jpg'),
];
return view('galeri', compact('photos'));
});


Route::get('/kontak', function () {
$contact = [
'phone' => '+62 853-9456-1496',
'email' => 'info@eksplornusantara.example',
'address' => 'Jl. Malioboro No.1, Yogyakarta'
];
return view('kontak', compact('contact'));
});