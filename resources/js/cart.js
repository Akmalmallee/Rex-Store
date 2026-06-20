import { showToast, setCartCount, getCsrfToken } from './helpers';

export async function addToCart(productId, size = null, color = null, quantity = 1, btn = null) {
    if (btn) {
        btn.disabled = true;
        const original = btn.innerHTML;
        btn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>';
        try {
            await _addToCart(productId, size, color, quantity);
        } finally {
            btn.disabled = false;
            btn.innerHTML = original;
        }
    } else {
        await _addToCart(productId, size, color, quantity);
    }
}

async function _addToCart(productId, size, color, quantity) {
    try {
        const res = await fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify({ product_id: productId, size, color, quantity })
        });
        const data = await res.json();
        if (data.success) {
            showToast('Produk ditambahkan ke keranjang!', 'success');
            setCartCount(data.cart_count);
        } else {
            showToast(data.message || 'Gagal menambahkan ke keranjang', 'error');
        }
    } catch(e) {
        showToast('Terjadi kesalahan', 'error');
    }
}

export function toggleCart() {
    const sidebar = document.getElementById('cart-sidebar');
    const overlay = document.getElementById('cart-sidebar-overlay');
    const isOpen = !sidebar.classList.contains('translate-x-full');
    if (isOpen) {
        sidebar.classList.add('translate-x-full');
        overlay.classList.add('hidden');
        document.body.style.overflow = '';
    } else {
        updateCartSidebar();
        sidebar.classList.remove('translate-x-full');
        overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

export async function updateCartSidebar() {
    try {
        const res = await fetch('/cart/sidebar');
        const html = await res.text();
        document.getElementById('cart-sidebar-content').innerHTML = html;
    } catch(e) {
        console.error('Cart update failed:', e);
    }
}

export async function updateQuantity(itemId, delta) {
    const qtyEl = document.getElementById('qty-' + itemId);
    if (!qtyEl) return;
    const currentQty = parseInt(qtyEl.textContent);
    const newQty = currentQty + delta;
    if (newQty < 1) return;

    try {
        const res = await fetch('/cart/' + itemId, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify({ quantity: newQty })
        });
        const data = await res.json();
        if (data.success) {
            updateCartSidebar();
            const row = document.querySelector(`[data-cart-row="${itemId}"]`);
            if (row) {
                const subtotalEl = row.querySelector('[data-subtotal]');
                if (subtotalEl) subtotalEl.textContent = data.item_subtotal;
                qtyEl.textContent = newQty;
            }
        }
    } catch(e) {}
}

export async function removeItem(itemId) {
    if (!confirm('Hapus item ini dari keranjang?')) return;
    try {
        const res = await fetch('/cart/' + itemId, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': getCsrfToken() }
        });
        const data = await res.json();
        if (data.success) {
            const el = document.getElementById('cart-item-' + itemId);
            if (el) el.remove();
            updateCartSidebar();
            setCartCount(data.cart_count);
            showToast('Item dihapus dari keranjang', 'info');
        }
    } catch(e) {
        showToast('Gagal menghapus item', 'error');
    }
}

export async function removeSidebarItem(itemId) {
    if (!confirm('Hapus item ini dari keranjang?')) return;
    try {
        const res = await fetch('/cart/' + itemId, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': getCsrfToken() }
        });
        const data = await res.json();
        if (data.success) {
            const itemEl = document.getElementById('cart-item-' + itemId);
            if (itemEl) {
                itemEl.remove();
                updateCartSidebar();
            }
            showToast('Item dihapus dari keranjang', 'info');
        }
    } catch(e) {
        showToast('Gagal menghapus item', 'error');
    }
}

export function updateOrderSummary() {
    const summarySubtotal = document.getElementById('summary-subtotal');
    const summaryTotal = document.getElementById('summary-total');
    const summaryCount = document.getElementById('summary-item-count');
    const cartCountLabel = document.getElementById('cart-count-label');
    if (!summarySubtotal || !summaryTotal) return;

    let total = 0;
    let count = 0;
    document.querySelectorAll('[data-cart-row]').forEach(row => {
        const subtotalEl = row.querySelector('[data-subtotal]');
        if (subtotalEl) {
            const val = subtotalEl.textContent.replace(/[^0-9]/g, '');
            total += parseInt(val) || 0;
        }
        const qtyEl = row.querySelector('[id^="qty-"]');
        if (qtyEl) {
            count += parseInt(qtyEl.textContent) || 0;
        }
    });

    const formatted = 'Rp ' + total.toLocaleString('id-ID');
    summarySubtotal.textContent = formatted;
    summaryTotal.textContent = formatted;
    if (summaryCount) summaryCount.textContent = `(${count} items)`;
    if (cartCountLabel) cartCountLabel.textContent = `${count} items`;
}
