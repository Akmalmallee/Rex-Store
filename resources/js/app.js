import Alpine from 'alpinejs';
import { showToast, setCartCount } from './helpers';
import { addToCart, toggleCart, updateCartSidebar, updateQuantity, removeItem, removeSidebarItem } from './cart';
import { toggleWishlist } from './wishlist';
import { selectSize, selectColor, updateQty, changeImage, setRating, zoomImage, resetZoom } from './product';
import { removeFilter, clearAllFilters, initRecentlyViewed, saveToRecentlyViewed } from './shop';
import { initScrollReveal, initHeroAnimation, initNavScroll, initParallax, initPageTransition, initStaggerGrid } from './animation';
import { open3DViewer } from './3d-viewer-init';
import { openFittingModal, closeFittingModal } from './fitting-assistant';

window.Alpine = Alpine;
Alpine.start();

window.showToast = showToast;
window.setCartCount = setCartCount;
window.addToCart = addToCart;
window.toggleCart = toggleCart;
window.updateCartSidebar = updateCartSidebar;
window.updateQuantity = updateQuantity;
window.removeItem = removeItem;
window.removeSidebarItem = removeSidebarItem;
window.toggleWishlist = toggleWishlist;
window.selectSize = selectSize;
window.selectColor = selectColor;
window.updateQty = updateQty;
window.changeImage = changeImage;
window.setRating = setRating;
window.zoomImage = zoomImage;
window.resetZoom = resetZoom;
window.removeFilter = removeFilter;
window.clearAllFilters = clearAllFilters;
window.initRecentlyViewed = initRecentlyViewed;
window.saveToRecentlyViewed = saveToRecentlyViewed;
window.open3DViewer = open3DViewer;
window.openFittingModal = openFittingModal;
window.closeFittingModal = closeFittingModal;

document.addEventListener('DOMContentLoaded', () => {
    initScrollReveal();
    initHeroAnimation();
    initNavScroll();
    initParallax();
    initPageTransition();
    initStaggerGrid();
});
