@extends('template/master' )

@section('title', $title)
@section('content')
    <section class="relative h-screen bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1470770841072-f978cf4d019e?q=80&w=2070&auto=format&fit=crop')">
        
        <div class="absolute inset-0 bg-gray-900/60"></div>

        <div class="relative z-10 mx-auto flex h-full max-w-screen-xl flex-col p-4 md:p-8">

            <nav class="absolute left-4 top-4 md:left-8 md:top-8" data-aos="fade-down">
                <a href="#3" class="rounded-full bg-white/30 px-4 py-2 text-sm font-medium text-white backdrop-blur-sm hover:bg-white/50">
                    Adventure
                </a>
            </nav>

            <main class="flex flex-grow flex-col items-center justify-center text-center">
                
                <h1 class="mb-10 text-5xl font-bold leading-tight text-white md:text-7xl lg:text-8xl" data-aos="fade-up">
                    Let's Explore Buton's <br> Heaven Together
                </h1>

                <form class="flex w-full max-w-3xl items-center divide-x divide-gray-200 rounded-full bg-white p-2 shadow-xl" data-aos="fade-up" data-aos-delay="200">
                    
                    <div class="flex-1 px-4 py-2">
                        <label for="destination" class="block text-left text-xs font-semibold text-gray-700 md:text-sm">Where</label>
                        <input type="text" id="destination" placeholder="Search Destination" class="w-full border-none p-0 text-sm text-gray-900 placeholder-gray-400 focus:ring-0">
                    </div>

                    <div class="flex-1 px-4 py-2">
                        <label for="check-in" class="block text-left text-xs font-semibold text-gray-700 md:text-sm">Check In</label>
                        <input type="date" id="check-in" placeholder="Add Dates" class="w-full border-none p-0 text-sm text-gray-900 placeholder-gray-400 focus:ring-0">
                    </div>

                    <div class="flex-1 px-4 py-2">
                        <label for="check-out" class="block text-left text-xs font-semibold text-gray-700 md:text-sm">Check Out</label>
                        <input type="date" id="check-out" placeholder="Add Dates" class="w-full border-none p-0 text-sm text-gray-900 placeholder-gray-400 focus:ring-0">
                    </div>

                    <div class="flex-1 px-4 py-2">
                        <label for="amenities" class="block text-left text-xs font-semibold text-gray-700 md:text-sm">Amenities</label>
                        <input type="text" id="amenities" placeholder="Add Amenities" class="w-full border-none p-0 text-sm text-gray-900 placeholder-gray-400 focus:ring-0">
                    </div>

                    <button type="submit" aria-label="Search" class="flex-shrink-0 rounded-full bg-gray-900 p-3 text-white hover:bg-gray-700 md:p-4">
                        <svg class="h-5 w-5 md:h-6 md:w-6" xmlns="http://www.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </button>
                </form>

                <div class="mt-6 flex flex-wrap items-center justify-center gap-x-3 gap-y-2 text-white" data-aos="fade-up" data-aos-delay="300">
                    <span class="text-sm md:text-base">Popular:</span> 
                    <ul class="flex flex-wrap items-center gap-x-3 gap-y-2">
                        <li><a href="#" class="text-sm font-semibold hover:underline md:text-base">Kamali Beach</a></li>
                        <li><a href="#" class="text-sm font-semibold hover:underline md:text-base">Benteng Kraton</a></li>
                        <li><a href="#" class="text-sm font-semibold hover:underline md:text-base">Air Terjun</a></li>
                        <li><a href="#" class="text-sm font-semibold hover:underline md:text-base">Nirwana Beach</a></li>
                    </ul>
                </div>

            </main>

    

            <div id="3"></div>
        </div>
     </section>

    <section class="relative h-screen bg-cover bg-center" style="background-image: url('{{asset('images/gowa.jpeg')}}')">
        
        <div class="absolute inset-0 bg-gray-900/60"></div>

        <div class="relative z-10 mx-auto flex h-full max-w-screen-xl flex-col p-4 md:p-8">

            
            <main class="flex flex-col text-white items-center justify-center text-center">
                
                    <h1 class="mb-10 text-4xl font-sans leading-tight md:text-4xl lg:text-6xl" data-aos="fade-right">
                        Find the perfect trip for you and discover <br> extraordinary adventures with us!
                    </h1>
                    
                    <p class="text-white text-2xl" data-aos="fade-left" data-aos-delay="100">
                        "Explore Bau-Bau, the heart of Buton Island: home to the world's largest palace fortress, exotic beaches, and the legendary historical heritage of the Buton Sultanate."
                    </p>
             </main>
                
            <div class="rounded-md mt-52" data-aos="zoom-in" data-aos-delay="300">
                <div class="flex justify-center items-center p-2">

                    <a href="#" class="bg-white inline-flex items-center rounded-full overflow-hidden shadow-lg transition-all hover:shadow-xl p-1">
    
                        <span class="bg-white text-black text-base font-semibold pl-6 pr-3 py-3">
                            Explore More
                        </span>

                        <span class="flex items-center justify-center bg-black text-white p-3 rounded-full">
                            <svg xmlns="http://www.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                            </svg>
                        </span>
                        
                    </a>
                </div>
                </div>
            
          
    

        </div> </section>

@endsection