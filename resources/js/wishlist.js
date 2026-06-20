import { showToast, getCsrfToken } from './helpers';

export async function toggleWishlist(productId, btn) {
    try {
        const res = await fetch('/wishlist/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify({ product_id: productId })
        });
        const data = await res.json();
        if (data.wishlisted) {
            btn.classList.add('text-red-500');
            btn.innerHTML = '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>';
            showToast('Ditambahkan ke wishlist!', 'success');
        } else {
            btn.classList.remove('text-red-500');
            btn.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>';
            showToast('Dihapus dari wishlist', 'info');
        }
    } catch(e) {
        showToast('Gagal memproses wishlist', 'error');
    }
}
