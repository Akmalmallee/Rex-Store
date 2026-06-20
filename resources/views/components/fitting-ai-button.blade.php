@props(['product'])
@auth
<button onclick="openFittingModal('{{ $product->slug }}')"
        class="fixed bottom-6 right-6 z-40 flex items-center gap-2.5 px-5 py-3 bg-[#0a0a0a] border border-[#C8A951]/30 text-[#C8A951] hover:bg-[#C8A951] hover:text-black transition-all duration-500 shadow-2xl shadow-[#C8A951]/5 group animate-glow-pulse hover:animate-none hover:shadow-[0_0_30px_rgba(200,169,81,0.25)] hover:scale-105">
    <svg class="w-4 h-4 transition-transform duration-500 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
    </svg>
    <span class="text-[10px] tracking-[0.25em] uppercase font-light">AI Fit</span>
    <span class="w-1.5 h-1.5 bg-[#C8A951] rounded-full animate-pulse"></span>
</button>

<button onclick="openFittingModal('{{ $product->slug }}')"
        class="fixed bottom-6 right-6 z-40 hidden group items-center justify-center w-14 h-14 bg-[#0a0a0a] border border-[#C8A951]/30 text-[#C8A951] hover:bg-[#C8A951] hover:text-black transition-all duration-500 shadow-2xl shadow-[#C8A951]/5 animate-glow-pulse hover:animate-none hover:scale-110">
    <svg class="w-6 h-6 transition-transform duration-500 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
    </svg>
</button>
@endauth
