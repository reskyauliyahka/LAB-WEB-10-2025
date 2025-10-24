@extends('template/master')

@section('title', $title)

@section('content')

<section class="py-16 md:py-24 ">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="mb-16 text-center" data-aos="fade-down">
            <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">
                Stay Connected With Us
            </h1>
            <p class="mt-4 text-lg text-gray-600">
                Contact us for questions, support, or collaboration we're ready to hear from you!
            </p>
        </div>

        <div class="grid grid-cols-1 gap-12 md:grid-cols-2">
            
            <div data-aos="fade-right">
                <form action="#" method="POST" class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" id="name" placeholder="Enter Your Namea..." class="mt-1  p-2 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" placeholder="Enter your email..." class="mt-1 p-2 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="tel" id="phone" placeholder="Enter your phone number..." class="mt-1 p-2 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                        <textarea id="message" rows="4" placeholder="Write your message..." class="mt-1 p-2 resize-none block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm focus:border-sky-500 focus:ring-sky-500"></textarea>
                    </div>

                    <fieldset>
                        <legend class="text-sm font-medium text-gray-700">Help Us to Develop</legend>
                        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="flex items-center">
                                <input id="service-design" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-sky-600 focus:ring-sky-500">
                                <label for="service-design" class="ml-3 text-sm text-gray-700">criticism</label>
                            </div>
                            <div class="flex items-center">
                                <input id="service-content" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-sky-600 focus:ring-sky-500">
                                <label for="service-content" class="ml-3 text-sm text-gray-700">Suggestion</label>
                            </div>
                            <div class="flex items-center">
                                <input id="service-ux" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-sky-600 focus:ring-sky-500">
                                <label for="service-ux" class="ml-3 text-sm text-gray-700">Feedback</label>
                            </div>
                            <div class="flex items-center">
                                <input id="service-strategy" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-sky-600 focus:ring-sky-500">
                                <label for="service-strategy" class="ml-3 text-sm text-gray-700">Strategy & Consulting</label>
                            </div>
                        </div>
                    </fieldset>

                    <div>
                        <button type="submit" class="flex w-full items-center justify-center rounded-md border border-transparent bg-sky-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                            Send Message
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="ml-2 h-5 w-5 -rotate-45">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.875L6 12z" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <div class="h-full min-h-[500px] w-full" data-aos="fade-left">
                <iframe 
                    class="h-full w-full rounded-lg shadow-sm"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15873.96783938481!2d122.5843453871582!3d-5.466038899999996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2da4788a8d11487f%3A0x803738cb2e92c249!2sBenteng%20Keraton%20Buton!5e0!3m2!1sid!2sid!4v1729727774211!5m2!1sid!2sid" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>

        <div class="mt-20 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            
            <div class="rounded-lg bg-sky-600 p-6 text-center text-white shadow-lg" data-aos="fade-up" data-aos-delay="0">
                <svg class="mx-auto h-10 w-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193l-3.72 3.72a.75.75 0 01-1.06 0l-3.72-3.72H9.75a.75.75 0 01-.75-.75V11.25a.75.75 0 01.75-.75h.008v.007c.012 0 .024 0 .036 0c.012 0 .024 0 .036 0h.008v.007c.012 0 .024 0 .036 0c.012 0 .024 0 .036 0h.008v.007c.012 0 .024 0 .036 0c.012 0 .024 0 .036 0h.008v.007c.012 0 .024 0 .036 0c.012 0 .024 0 .036 0h.008v.007c.012 0 .024 0 .036 0c.012 0 .024 0 .036 0h.008v.007c.012 0 .024 0 .036 0c.012 0 .024 0 .036 0h.008v.007c.012 0 .024 0 .036 0c.012 0 .024 0 .036 0h.008v.007c.012 0 .024 0 .036 0c.012 0 .024 0 .036 0H12a.75.75 0 01.75.75v.231l3.011 3.011 3.72-3.72a.75.75 0 01.98-.193zM3.75 21a.75.75 0 01-.75-.75V11.25a.75.75 0 01.75-.75h.008v.007c.012 0 .024 0 .036 0c.012 0 .024 0 .036 0h.008v.007c.012 0 .024 0 .036 0c.012 0 .024 0 .036 0h.008v.007c.012 0 .024 0 .036 0c.012 0 .024 0 .036 0h.008v.007c.012 0 .024 0 .036 0c.012 0 .024 0 .036 0h.008v.007c.012 0 .024 0 .036 0c.012 0 .024 0 .036 0h.008v.007c.012 0 .024 0 .036 0c.012 0 .024 0 .036 0h.008v.007c.012 0 .024 0 .036 0c.012 0 .024 0 .036 0h.008v.007c.012 0 .024 0 .036 0c.012 0 .024 0 .036 0H6a.75.75 0 01.75.75v.231l3.011 3.011 3.72-3.72a.75.75 0 01.98-.193c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193l-3.72 3.72a.75.75 0 01-1.06 0l-3.72-3.72H3.75z" />
                </svg>
                <h3 class="mt-4 text-lg font-semibold">Info Pemesanan</h3>
                <p class="mt-1 text-sm text-sky-100">Hubungi tim sales kami</p>
                <a href="mailto:sales@wisatabuton.com" class="mt-4 block w-full rounded-md bg-white px-4 py-2 text-center font-medium text-sky-600 shadow-sm hover:bg-gray-100">
                    sales@wisatabuton.com
                </a>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-6 text-center shadow-sm" data-aos="fade-up" data-aos-delay="100">
                <svg class="mx-auto h-10 w-10 text-sky-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785A5.969 5.969 0 006 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337z" />
                </svg>
                <h3 class="mt-4 text-lg font-semibold text-gray-900">Dukungan Teknis</h3>
                <p class="mt-1 text-sm text-gray-600">Kami siap membantu Anda</p>
                <a href="mailto:support@wisatabuton.com" class="mt-4 block w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-center font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                    support@wisatabuton.com
                </a>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-6 text-center shadow-sm" data-aos="fade-up" data-aos-delay="200">
                <svg class="mx-auto h-10 w-10 text-sky-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                </svg>
                <h3 class="mt-4 text-lg font-semibold text-gray-900">Kunjungi Kami</h3>
                <p class="mt-1 text-sm text-gray-600">Kunjungi kantor kami di Bau-Bau</p>
                <a href="#" class="mt-4 block w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-center font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                    Lihat di Peta
                </a>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-6 text-center shadow-sm" data-aos="fade-up" data-aos-delay="300">
                <svg class="mx-auto h-10 w-10 text-sky-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.211-.998-.552-1.347l-3.468-3.468c-.34-.34-.83.08-1.1c.36-.26.63.14.7.45l-.03.11c-.01.04-.01.08-.02.12c-.03.12-.06.23-.1.35c-.07.21-.16.42-.28.6c-.14.23-.31.45-.5.64c-.2.2-.42.37-.67.5c-.24.13-.5.23-.77.3c-.27.07-.54.1-.82.11c-.28.01-.56 0-.84-.03c-.28-.03-.56-.07-.83-.14c-.28-.07-.55-.16-.81-.28c-.26-.11-.51-.25-.75-.4c-.24-.15-.47-.32-.69-.51c-.22-.19-.43-.39-.62-.61c-.2-.23-.37-.47-.52-.73c-.15-.26-.28-.53-.38-.82c-.1-.28-.18-.58-.24-.88c-.06-.3-.09-.6-.1-.91c-.01-.3-.01-.6 0-.89c.01-.3.03-.6.07-.89c.04-.3.1-.6.18-.89c.08-.29.18-.57.3-.84c.12-.27.26-.54.42-.79c.16-.25.34-.5.54-.73c.2-.24.42-.46.66-.67c.24-.21.5-.4.77-.57c.27-.17.56-.32.86-.45c.3-.13.6-.23.91-.3c.3-.08.6-.13.91-.16c.3-.03.6-.04.9-.04c.3 0 .6 0 .89.01c.29.01.58.04.86.08c.28.04.55.1.81.18c.26.08.51.18.75.3c.24.12.47.26.68.42c.21.16.41.34.59.54c.18.2.35.41.5.63c.15.22.29.45.41.7c.12.25.23.51.31.79c.08.27.14.55.18.84c.04.28.07.57.08.86c.01.29.02.58.02.88z" />
                </svg>
                <h3 class="mt-4 text-lg font-semibold text-gray-900">Hubungi Kami</h3>
                <p class="mt-1 text-sm text-gray-600">Senin - Jumat, 08:00 - 17:00</p>
                <a href="tel:+628123456789" class="mt-4 block w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-center font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                    +62 812-3456-7890
                </a>
            </div>

        </div> </div> </section>
@endsection