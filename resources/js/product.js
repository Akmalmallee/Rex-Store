export function selectSize(btn, size) {
    document.querySelectorAll('#size-selector button').forEach(b => {
        b.classList.remove('border-[#C8A951]', 'bg-[#C8A951]/10');
        b.classList.add('border-gray-200', 'dark:border-gray-700');
    });
    btn.classList.remove('border-gray-200', 'dark:border-gray-700');
    btn.classList.add('border-[#C8A951]', 'bg-[#C8A951]/10');
    window._selectedSize = size;
}

export function selectColor(btn, color) {
    document.querySelectorAll('#color-selector button').forEach(b => {
        b.classList.remove('border-[#C8A951]', 'ring-2', 'ring-[#C8A951]/30');
        b.classList.add('border-gray-300', 'dark:border-gray-600');
    });
    btn.classList.remove('border-gray-300', 'dark:border-gray-600');
    btn.classList.add('border-[#C8A951]', 'ring-2', 'ring-[#C8A951]/30');
    window._selectedColor = color;
}

export function updateQty(delta, maxStock) {
    const input = document.getElementById('quantity');
    if (!input) return;
    const val = parseInt(input.value) + delta;
    if (val >= 1 && val <= maxStock) input.value = val;
}

export function changeImage(btn) {
    const main = document.getElementById('main-image');
    if (!main) return;
    main.src = btn.dataset.src;
    document.querySelectorAll('[data-src]').forEach(b => b.classList.remove('border-[#C8A951]'));
    btn.classList.add('border-[#C8A951]');
}

export function setRating(val) {
    const input = document.getElementById('rating-value');
    if (!input) return;
    input.value = val;
    document.querySelectorAll('.rating-star svg').forEach((svg, i) => {
        svg.classList.toggle('text-yellow-400', i < val);
        svg.classList.toggle('text-gray-300', i >= val);
    });
}

export function zoomImage(event) {
    const img = event.currentTarget;
    const rect = img.getBoundingClientRect();
    const x = ((event.clientX - rect.left) / rect.width) * 100;
    const y = ((event.clientY - rect.top) / rect.height) * 100;
    img.style.transformOrigin = `${x}% ${y}%`;
    img.style.transform = 'scale(2)';
}

export function resetZoom(event) {
    const img = event.currentTarget;
    if (!img) return;
    img.style.transformOrigin = 'center center';
    img.style.transform = 'scale(1)';
}
