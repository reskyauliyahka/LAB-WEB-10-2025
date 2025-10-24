@extends('template/master')

@section('title', $title)
@section('content')


<div class="bg-gray-100">

  <div class="container max-w-screen-xl mx-auto p-4 md:p-8">
    <div class="flex flex-col md:flex-row md:gap-8">

      <aside class="w-full md:w-1/4 mb-8 md:mb-0" data-aos="fade-right">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
          <h3 class="text-xl font-semibold mb-6">Filters</h3>

          <div class="mb-6">
            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Lokasi Teratas</label>
            <div class="relative">
              <select id="location" class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none">
                <option selected>Bau-Bau</option>
                <option>Kendari</option>
                <option>Wakatobi</option>
              </select>
              <svg class="w-5 h-5 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
              </svg>
            </div>
          </div>

          <div class="mb-6">
            <div class="relative">
              <input type="text" value="Pilih Tanggal Wisata" readonly class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
              <svg class="w-5 h-5 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
              </svg>
            </div>
          </div>

          <div class="mb-6">
            <h4 class="text-base font-semibold text-gray-900 mb-3">Filter Populer</h4>
            <div class="space-y-3">
              <label class="flex items-center justify-between cursor-pointer">
                <span>Tiket Gratis</span>
                <input type="radio" name="popular-filter" class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500" checked>
              </label>
              <label class="flex items-center justify-between cursor-pointer">
                <span>Dekat Parkir</span>
                <input type="radio" name="popular-filter" class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500">
              </label>
              <label class="flex items-center justify-between cursor-pointer">
                <span>Warung Makan</span>
                <input type="radio" name="popular-filter" class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500">
              </label>
            </div>
          </div>
          
          <div>
            <h4 class="text-base font-semibold text-gray-900 mb-3">Rating</h4>
            <div class="space-y-3">
              <label class="flex items-center justify-between cursor-pointer">
                <span class="flex items-center">
                  5 Bintang
                  <svg class="w-4 h-4 text-yellow-400 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 7.09l6.572-.955L10 0l2.939 6.135 6.572.955-4.756 4.455 1.123 6.545z"/></svg>
                </span>
                <input type="radio" name="star-rating" class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500" checked>
              </label>
              <label class="flex items-center justify-between cursor-pointer">
                <span class="flex items-center">4 Bintang</span>
                <input type="radio" name="star-rating" class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500">
              </label>
              <label class="flex items-center justify-between cursor-pointer">
                <span class="flex items-center">3 Bintang</span>
                <input type="radio" name="star-rating" class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500">
              </label>
            </div>
          </div>
        </div>
      </aside>

      <main class="w-full md:w-3/4">

        <h2 class="text-3xl font-bold mb-6" data-aos="fade-up">Rekomendasi Populer</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

          <div class="bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1" data-aos="fade-up">
            <div class="relative group">
              <img src="{{'images/pantai-kamali.jpg'}}" alt="Pantai Kamali" class="w-full h-64 object-cover transition-all duration-300 group-hover:blur-[2px] group-hover:scale-105">
              <div class="absolute inset-0 bg-black/60 p-4 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                <p class="text-white text-center text-sm line-clamp-4">Ikon kota dengan Patung Kepala Naga, sempurna untuk menikmati senja dan kuliner malam. Tempat berkumpul favorit warga lokal.</p>
              </div>
            </div>
            <div class="p-5">
              <h3 class="text-xl font-bold text-gray-900 mb-2">Pantai Kamali</h3>
              <div class="flex items-center text-sm text-gray-600 mb-3">
                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                <span>Wale, Wolio, Kota Bau-Bau</span>
              </div>
              <div class="flex items-center text-sm text-gray-800 mb-4">
                <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 7.09l6.572-.955L10 0l2.939 6.135 6.572.955-4.756 4.455 1.123 6.545z"/></svg>
                <span class="font-medium">4.6</span>
                <span class="text-gray-500 ml-1">(1,230 Ulasan)</span>
              </div>
              <div class="text-xl font-extrabold text-gray-900">
                Gratis
                <span class="text-sm font-normal text-gray-600">/Parkir Saja</span>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1" data-aos="fade-up" data-aos-delay="100">
            <div class="relative group">
              <img src="{{'images/Destinasi-Wisata-Terbaik-di-Baubau-Wajib-Untuk 04.18.59.jpg'}}" alt="Benteng Keraton Buton" class="w-full h-64 object-cover transition-all duration-300 group-hover:blur-[2px] group-hover:scale-105">
              <div class="absolute inset-0 bg-black/60 p-4 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                <p class="text-white text-center text-sm line-clamp-4">Benteng terluas di dunia! Peninggalan Kesultanan Buton ini menawarkan sejarah megah dan pemandangan kota Bau-Bau dari ketinggian.</p>
              </div>
            </div>
            <div class="p-5">
              <h3 class="text-xl font-bold text-gray-900 mb-2">Benteng Keraton Buton</h3>
              <div class="flex items-center text-sm text-gray-600 mb-3">
                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                <span>Melai, Murhum, Kota Bau-Bau</span>
              </div>
              <div class="flex items-center text-sm text-gray-800 mb-4">
                <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 7.09l6.572-.955L10 0l2.939 6.135 6.572.955-4.756 4.455 1.123 6.545z"/></svg>
                <span class="font-medium">4.8</span>
                <span class="text-gray-500 ml-1">(3,100 Ulasan)</span>
              </div>
              <div class="text-xl font-extrabold text-gray-900">
                Rp 5.000
                <span class="text-sm font-normal text-gray-600">/Orang</span>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1" data-aos="fade-up">
            <div class="relative group">
              <img src="{{'images/Air-Terjun-Samparonaa.jpg'}}" alt="Air Terjun Samparona" class="w-full h-64 object-cover transition-all duration-300 group-hover:blur-[2px] group-hover:scale-105">
              <div class="absolute inset-0 bg-black/60 p-4 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                <p class="text-white text-center text-sm line-clamp-4">Surga tersembunyi di tengah hutan. Nikmati kesegaran air terjun bertingkat dengan suasana alam yang masih sangat asri dan sejuk.</p>
              </div>
            </div>
            <div class="p-5">
              <h3 class="text-xl font-bold text-gray-900 mb-2">Air Terjun Samparona</h3>
              <div class="flex items-center text-sm text-gray-600 mb-3">
                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                <span>Kaisabu Baru, Sorawolio</span>
              </div>
              <div class="flex items-center text-sm text-gray-800 mb-4">
                <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 7.09l6.572-.955L10 0l2.939 6.135 6.572.955-4.756 4.455 1.123 6.545z"/></svg>
                <span class="font-medium">4.5</span>
                <span class="text-gray-500 ml-1">(450 Ulasan)</span>
              </div>
              <div class="text-xl font-extrabold text-gray-900">
                Rp 10.000
                <span class="text-sm font-normal text-gray-600">/Orang</span>
              </div>
            </div>
          </div>
          
          <div class="bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1" data-aos="fade-up" data-aos-delay="100">
            <div class="relative group">
              <img src="{{'images/pantai-nirwa.jpg'}}" alt="Pantai Nirwana" class="w-full h-64 object-cover transition-all duration-300 group-hover:blur-[2px] group-hover:scale-105">
              <div class="absolute inset-0 bg-black/60 p-4 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                <p class="text-white text-center text-sm line-clamp-4">Pasir putih super halus dan air laut jernih bergradasi toska. Tempat ideal untuk berenang, snorkeling, dan bersantai menikmati keindahan bahari.</p>
              </div>
            </div>
            <div class="p-5">
              <h3 class="text-xl font-bold text-gray-900 mb-2">Pantai Nirwana</h3>
              <div class="flex items-center text-sm text-gray-600 mb-3">
                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                <span>Sulaa, Betoambari</span>
              </div>
              <div class="flex items-center text-sm text-gray-800 mb-4">
                <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 7.09l6.572-.955L10 0l2.939 6.135 6.572.955-4.756 4.455 1.123 6.545z"/></svg>
                <span class="font-medium">4.7</span>
                <span class="text-gray-500 ml-1">(2,200 Ulasan)</span>
              </div>
              <div class="text-xl font-extrabold text-gray-900">
                Rp 10.000
                <span class="text-sm font-normal text-gray-600">/Orang</span>
              </div>
            </div>
          </div>
        </div>

        <h2 class="text-3xl font-bold mb-6 mt-12" data-aos="fade-up">Destinasi Lainnya</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

          <div class="bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1" data-aos="fade-up">
            <div class="relative group">
              <img src="{{'images/gowa.jpeg'}}" alt="Goa Lakasa" class="w-full h-64 object-cover transition-all duration-300 group-hover:blur-[2px] group-hover:scale-105">
              <div class="absolute inset-0 bg-black/60 p-4 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                <p class="text-white text-center text-sm line-clamp-4">Menjelajahi keajaiban bawah tanah dengan stalaktit dan stalagmit unik. Terdapat 'kamar' dan 'kolam' alami di dalamnya.</p>
              </div>
            </div>
            <div class="p-5">
              <h3 class="text-xl font-bold text-gray-900 mb-2">Goa Lakasa</h3>
              <div class="flex items-center text-sm text-gray-600 mb-3">
                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                <span>Sulaa, Betoambari</span>
              </div>
              <div class="flex items-center text-sm text-gray-800 mb-4">
                <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 7.09l6.572-.955L10 0l2.939 6.135 6.572.955-4.756 4.455 1.123 6.545z"/></svg>
                <span class="font-medium">4.4</span>
                <span class="text-gray-500 ml-1">(310 Ulasan)</span>
              </div>
              <div class="text-xl font-extrabold text-gray-900">
                Rp 15.000
                <span class="text-sm font-normal text-gray-600">/Orang</span>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1" data-aos="fade-up" data-aos-delay="100">
            <div class="relative group">
              <img src="images/1601226794_Jelajah-Wisata-Bau-Bau-Yuk-jelajah-tempat-wisata-Bau-1080x834.jpg" alt="Pantai Lakeba" class="w-full h-64 object-cover transition-all duration-300 group-hover:blur-[2px] group-hover:scale-105">
              <div class="absolute inset-0 bg-black/60 p-4 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                <p class="text-white text-center text-sm line-clamp-4">Pantai yang populer dengan berbagai wahana air dan fasilitas lengkap. Cocok untuk rekreasi keluarga di akhir pekan.</p>
              </div>
            </div>
            <div class="p-5">
              <h3 class="text-xl font-bold text-gray-900 mb-2">Pantai Lakeba</h3>
              <div class="flex items-center text-sm text-gray-600 mb-3">
                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                <span>Sulaa, Betoambari</span>
              </div>
              <div class="flex items-center text-sm text-gray-800 mb-4">
                <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 7.09l6.572-.955L10 0l2.939 6.135 6.572.955-4.756 4.455 1.123 6.545z"/></svg>
                <span class="font-medium">4.3</span>
                <span class="text-gray-500 ml-1">(890 Ulasan)</span>
              </div>
              <div class="text-xl font-extrabold text-gray-900">
                Rp 5.000
                <span class="text-sm font-normal text-gray-600">/Orang</span>
              </div>
            </div>
          </div>
          
          <div class="bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1" data-aos="fade-up" data-aos-delay="200">
            <div class="relative group">
              <img src="images/wantiro.jpeg" alt="Bukit Wantiro" class="w-full h-64 object-cover transition-all duration-300 group-hover:blur-[2px] group-hover:scale-105">
              <div class="absolute inset-0 bg-black/60 p-4 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                <p class="text-white text-center text-sm line-clamp-4">Tempat terbaik di Bau-Bau untuk menyaksikan matahari terbenam (sunset) dengan pemandangan kota dan laut yang memukau dari ketinggian.</p>
              </div>
            </div>
            <div class="p-5">
              <h3 class="text-xl font-bold text-gray-900 mb-2">Bukit Wantiro</h3>
              <div class="flex items-center text-sm text-gray-600 mb-3">
                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                <span>Wantiro, Wolio</span>
              </div>
              <div class="flex items-center text-sm text-gray-800 mb-4">
                <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 7.09l6.572-.955L10 0l2.939 6.135 6.572.955-4.756 4.455 1.123 6.545z"/></svg>
                <span class="font-medium">4.6</span>
                <span class="text-gray-500 ml-1">(215 Ulasan)</span>
              </div>
              <div class="text-xl font-extrabold text-gray-900">
                Gratis
                <span class="text-sm font-normal text-gray-600">/Orang</span>
              </div>
            </div>
          </div>
        </div>

      </main>
    </div>
  </div>

</div>
@endsection