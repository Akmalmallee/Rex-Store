<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Rex Fashion')) - Rex Fashion</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-white dark:bg-[#0a0a0a] text-gray-900 dark:text-white">
    <x-user-navbar />

    <main>
        {{ $slot }}
    </main>

    <x-user-footer />

    <div id="cart-sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden transition-opacity duration-300" onclick="toggleCart()"></div>
    <div id="cart-sidebar" class="fixed top-0 right-0 h-full w-full max-w-md bg-white dark:bg-[#1a1a1a] z-50 transform translate-x-full transition-transform duration-300 shadow-2xl overflow-y-auto">
        <div id="cart-sidebar-content"></div>
    </div>

    <div id="toast-container" class="fixed top-4 right-4 z-[100] space-y-2"></div>

    @stack('scripts')

    <script>
        // Toast notification
        function showToast(message, type = 'success') {
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

        // Cart sidebar toggle
        function toggleCart() {
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

        async function updateCartSidebar() {
            try {
                const res = await fetch('/cart/sidebar');
                const html = await res.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                document.getElementById('cart-sidebar-content').innerHTML = html;
            } catch(e) {
                console.error('Cart update failed:', e);
            }
        }

        // Wishlist toggle
        async function toggleWishlist(productId, btn) {
            try {
                const res = await fetch('/wishlist/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
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

        // Add to cart
        async function addToCart(productId, size = null, color = null, quantity = 1) {
            try {
                const res = await fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
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

        function setCartCount(n) {
            document.querySelectorAll('[data-cart-count]').forEach(el => {
                el.textContent = n;
            });
        }

        async function updateQuantity(itemId, delta) {
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
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ quantity: newQty })
                });
                const data = await res.json();
                if (data.success) {
                    updateCartSidebar();
                }
            } catch(e) {}
        }
    </script>
</body>
</html>
