@extends('template/master')

@section('title', $title)
@section('content')

<div>
    

<div id="gallery" class="relative w-full shadow-lg " data-carousel="slide" data-aos="fade-in">
    <div class="relative h-76 overflow-hidden md:h-146">
         <div class="hidden duration-700 ease-in-out" data-carousel-item="active">
            <img src="{{asset('images/hard-to-reach-but-worth.jpg')}}" class="absolute left-1/2 top-1/2 block h-auto w-full max-w-full -translate-x-1/2 -translate-y-1/2" alt="">
        </div>
        <div class="hidden duration-700 ease-in-out" data-carousel-item>
            <img src="{{asset('images/Destinasi-Wisata-Terbaik-di-Baubau-Wajib-Untuk 04.18.59.jpg')}}" class="absolute left-1/2 top-1/2 block h-auto w-full max-w-full -translate-x-1/2 -translate-y-1/2" alt="">
        </div>
        <div class="hidden duration-700 ease-in-out" data-carousel-item>
            <img src="{{asset('images/hutan-pinus-samparona-baubau 04.18.55.jpg')}}" class="absolute left-1/2 top-1/2 block h-auto w-full max-w-full -translate-x-1/2 -translate-y-1/2" alt="">
        </div>
        <div class="hidden duration-700 ease-in-out" data-carousel-item>
            <img src="{{asset('images/1601226794_Jelajah-Wisata-Bau-Bau-Yuk-jelajah-tempat-wisata-Bau-1080x834.jpg')}}" class="absolute left-1/2 top-1/2 block h-auto w-full max-w-full -translate-x-1/2 -translate-y-1/2" alt="">
        </div>
        <div class="hidden duration-700 ease-in-out" data-carousel-item>
            <img src="{{asset('images/wisata-bau-bau-17.jpg')}}" class="absolute left-1/2 top-1/2 block h-auto w-full max-w-full -translate-x-1/2 -translate-y-1/2" alt="">
        </div>
    </div>
    <button type="button" class="group absolute left-0 top-0 z-30 flex h-full cursor-pointer items-center justify-center px-4 focus:outline-none" data-carousel-prev>
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/30 group-hover:bg-white/50 group-focus:outline-none group-focus:ring-4 group-focus:ring-white dark:bg-gray-800/30 dark:group-hover:bg-gray-800/60 dark:group-focus:ring-gray-800/70">
            <svg class="h-4 w-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
            </svg>
            <span class="sr-only">Previous</span>
        </span>
    </button>
    <button type="button" class="group absolute right-0 top-0 z-30 flex h-full cursor-pointer items-center justify-center px-4 focus:outline-none" data-carousel-next>
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/30 group-hover:bg-white/50 group-focus:outline-none group-focus:ring-4 group-focus:ring-white dark:bg-gray-800/30 dark:group-hover:bg-gray-800/60 dark:group-focus:ring-gray-800/70">
            <svg class="h-4 w-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
            </svg>
            <span class="sr-only">Next</span>
        </span>
    </button>
</div>
  


<div class="flex flex-wrap items-center justify-center py-4 md:py-8" data-aos="fade-up">
    <button type="button" class="me-3 mb-3 rounded-full border border-blue-600 bg-white px-5 py-2.5 text-center text-base font-medium text-blue-700 hover:bg-blue-700 hover:text-white focus:outline-none focus:ring-4 focus:ring-blue-300 dark:border-blue-500 dark:bg-gray-900 dark:text-blue-500 dark:hover:bg-blue-500 dark:hover:text-white dark:focus:ring-blue-800">All categories</button>
    <button type="button" class="me-3 mb-3 rounded-full border border-white bg-white px-5 py-2.5 text-center text-base font-medium text-gray-900 hover:border-gray-200 focus:outline-none focus:ring-4 focus:ring-gray-300 dark:border-gray-900 dark:bg-gray-900 dark:hover:border-gray-700 dark:text-white dark:focus:ring-gray-800">Sea</button>
    <button type="button" class="me-3 mb-3 rounded-full border border-white bg-white px-5 py-2.5 text-center text-base font-medium text-gray-900 hover:border-gray-200 focus:outline-none focus:ring-4 focus:ring-gray-300 dark:border-gray-900 dark:bg-gray-900 dark:hover:border-gray-700 dark:text-white dark:focus:ring-gray-800">Mount</button>
    <button type="button" class="me-3 mb-3 rounded-full border border-white bg-white px-5 py-2.5 text-center text-base font-medium text-gray-900 hover:border-gray-200 focus:outline-none focus:ring-4 focus:ring-gray-300 dark:border-gray-900 dark:bg-gray-900 dark:hover:border-gray-700 dark:text-white dark:focus:ring-gray-800">historical</button>
</div>

<div class="mx-auto max-w-4xl">
    <div class="grid grid-cols-2 gap-4 md:grid-cols-3">
        
        <div class="group aspect-square overflow-hidden rounded-lg" data-aos="zoom-in-up">
            <img class="h-full w-full object-cover transition-transform duration-300 ease-in-out group-hover:scale-105" src="{{asset('images/gowa.jpeg')}}" alt="">
        </div>
        <div class="group aspect-square overflow-hidden rounded-lg" data-aos="zoom-in-up">
            <img class="h-full w-full object-cover transition-transform duration-300 ease-in-out group-hover:scale-105" src="{{asset('images/1601226794_Jelajah-Wisata-Bau-Bau-Yuk-jelajah-tempat-wisata-Bau-1080x834.jpg')}}" alt="">
        </div>
        <div class="group aspect-square overflow-hidden rounded-lg" data-aos="zoom-in-up">
            <img class="h-full w-full object-cover transition-transform duration-300 ease-in-out group-hover:scale-105" src="{{asset('images/danau.jpg')}}" alt="">
        </div>
        <div class="group aspect-square overflow-hidden rounded-lg" data-aos="zoom-in-up">
            <img class="h-full w-full object-cover transition-transform duration-300 ease-in-out group-hover:scale-105" src="{{asset('images/baubau2.jpeg')}}" alt="">
        </div>
        <div class="group aspect-square overflow-hidden rounded-lg" data-aos="zoom-in-up">
            <img class="h-full w-full object-cover transition-transform duration-300 ease-in-out group-hover:scale-105" src="{{asset('images/wisata-bau-bau-25.jpg')}}" alt="">
        </div>
        <div class="group aspect-square overflow-hidden rounded-lg" data-aos="zoom-in-up">
            <img class="h-full w-full object-cover transition-transform duration-300 ease-in-out group-hover:scale-105" src="{{asset('images/wisata-bau-bau-17.jpg')}}" alt="">
        </div>
        <div class="group aspect-square overflow-hidden rounded-lg" data-aos="zoom-in-up">
            <img class="h-full w-full object-cover transition-transform duration-300 ease-in-out group-hover:scale-105" src="{{asset('images/Destinasi-Wisata-Terbaik-di-Baubau-Wajib-Untuk 04.18.59.jpg')}}" alt="">
        </div>
        <div class="group aspect-square overflow-hidden rounded-lg" data-aos="zoom-in-up">
            <img class="h-full w-full object-cover transition-transform duration-300 ease-in-out group-hover:scale-105" src="{{asset('images/hard-to-reach-but-worth.jpg')}}" alt="">
        </div>
        <div class="group aspect-square overflow-hidden rounded-lg" data-aos="zoom-in-up">
            <img class="h-full w-full object-cover transition-transform duration-300 ease-in-out group-hover:scale-105" src="{{asset('images/terjun1.jpg')}}" alt="">
        </div>

</div>
</div>
@endsection