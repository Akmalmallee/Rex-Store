export function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        warning: 'bg-yellow-500',
        info: 'bg-blue-500'
    };
    toast.className = `${colors[type] || colors.success} text-white px-6 py-4 rounded-xl shadow-lg transform transition-all duration-300 translate-x-0 opacity-100 flex items-center gap-3 min-w-[300px]`;
    toast.innerHTML = `
        <span>${message}</span>
        <button onclick="this.parentElement.remove()" class="ml-auto text-white/80 hover:text-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    `;
    container.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

export function setCartCount(n) {
    document.querySelectorAll('[data-cart-count]').forEach(el => {
        el.textContent = n;
    });
}

export function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content || '';
}
