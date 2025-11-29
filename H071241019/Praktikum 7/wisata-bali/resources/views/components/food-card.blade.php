<div class="reveal group rounded-3xl overflow-hidden shadow-xl transition-all duration-500 
            bg-white/10 backdrop-blur-xl border border-white/20 
            hover:scale-[1.04] hover:shadow-2xl hover:bg-white/20 dark:bg-white/5">

    {{-- Image --}}
    <div class="relative w-full h-56 overflow-hidden">
        <img src="{{ $img }}" alt="{{ $title }}" 
             class="w-full h-full object-cover transition duration-500 group-hover:scale-110 group-hover:brightness-110">
    </div>

    {{-- Text Content --}}
    <div class="p-6 text-white space-y-2">
        <h3 class="text-2xl font-bold font-playfair drop-shadow-lg">
            {{ $title }} 🍽️
        </h3>

        <p class="text-sm opacity-95 leading-relaxed">
            {{ $desc }}
        </p>

        {{-- Accent Line --}}
        <div class="h-[3px] w-20 bg-gradient-to-r from-yellow-300 to-orange-500 rounded-full mt-3"></div>
    </div>
</div>
