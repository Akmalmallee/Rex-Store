export function removeFilter(param, value) {
    const url = new URL(window.location.href);
    if (value !== undefined && value !== null) {
        if (param.endsWith('[]')) {
            const values = url.searchParams.getAll(param);
            url.searchParams.delete(param);
            values.filter(v => v !== value).forEach(v => url.searchParams.append(param, v));
        } else {
            url.searchParams.delete(param);
        }
    } else {
        url.searchParams.delete(param);
    }
    window.location.href = url.toString();
}

export function clearAllFilters() {
    window.location.href = window.location.pathname;
}

export function initRecentlyViewed() {
    const container = document.getElementById('recently-viewed');
    if (!container) return;
    const items = JSON.parse(localStorage.getItem('rex_recently_viewed') || '[]');
    if (items.length === 0) {
        container.remove();
        return;
    }
    const template = document.getElementById('recently-viewed-template');
    if (!template) return;
    const grid = container.querySelector('.recently-viewed-grid');
    if (!grid) return;
    grid.innerHTML = '';
    items.forEach(item => {
        const clone = template.content.cloneNode(true);
        const link = clone.querySelector('.rv-link');
        const img = clone.querySelector('.rv-img');
        const name = clone.querySelector('.rv-name');
        const price = clone.querySelector('.rv-price');
        if (link) link.href = '/product/' + item.slug;
        if (img) {
            img.src = item.thumbnail || 'https://picsum.photos/seed/' + item.slug + '/400/500';
            img.alt = item.name;
        }
        if (name) name.textContent = item.name;
        if (price) price.textContent = 'Rp ' + Number(item.price).toLocaleString('id-ID');
        grid.appendChild(clone);
    });
}

export function saveToRecentlyViewed(product) {
    if (!product || !product.slug) return;
    const key = 'rex_recently_viewed';
    const items = JSON.parse(localStorage.getItem(key) || '[]');
    const filtered = items.filter(i => i.slug !== product.slug);
    filtered.unshift({ slug: product.slug, name: product.name, thumbnail: product.thumbnail, price: product.price });
    localStorage.setItem(key, JSON.stringify(filtered.slice(0, 10)));
}
