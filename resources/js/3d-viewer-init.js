export async function init3DViewer(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return null;

    const modelUrl = container.dataset.modelUrl;
    if (!modelUrl) return null;

    try {
        const { ProductViewer3D } = await import('./3d-viewer/index.js');
        const viewer = new ProductViewer3D(container, { autoRotate: true });
        viewer.init();
        return { viewer, modelUrl };
    } catch (err) {
        console.warn('3D viewer could not be initialized:', err);
        return null;
    }
}

export function setupViewerControls(viewer, options = {}) {
    const container = options.container;
    if (!container || !viewer) return;

    const colorSwatches = container.querySelectorAll('[data-3d-color]');
    colorSwatches.forEach((swatch) => {
        swatch.addEventListener('click', () => {
            const color = swatch.dataset['3dColor'];
            const texture = swatch.dataset['3dTexture'];

            colorSwatches.forEach((s) => {
                s.classList.remove('border-[#C8A951]', 'ring-2', 'ring-[#C8A951]/30');
                s.classList.add('border-white/10');
            });
            swatch.classList.remove('border-white/10');
            swatch.classList.add('border-[#C8A951]', 'ring-2', 'ring-[#C8A951]/30');

            if (texture) {
                viewer.switchTexture(texture);
            } else if (color) {
                viewer.setColor(color);
            }
        });
    });

    const rotateBtn = container.querySelector('[data-3d-rotate]');
    if (rotateBtn) {
        rotateBtn.addEventListener('click', () => {
            viewer.autoRotate(!viewer.controls?.autoRotate);
            rotateBtn.classList.toggle('text-[#C8A951]');
            rotateBtn.classList.toggle('text-gray-500');
        });
    }

    const resetBtn = container.querySelector('[data-3d-reset]');
    if (resetBtn) {
        resetBtn.addEventListener('click', () => {
            viewer.resetCamera();
        });
    }

    const sizeButtons = container.querySelectorAll('[data-3d-size]');
    sizeButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            const size = parseFloat(btn.dataset['3dSize']) || 1;
            viewer.setScale(size);
            sizeButtons.forEach((b) => {
                b.classList.remove('border-[#C8A951]', 'bg-[#C8A951]/10');
                b.classList.add('border-white/10');
            });
            btn.classList.remove('border-white/10');
            btn.classList.add('border-[#C8A951]', 'bg-[#C8A951]/10');
        });
    });
}

export function open3DViewer(productData) {
    const overlay = document.getElementById('3d-viewer-overlay');
    if (!overlay) return;

    overlay.classList.remove('hidden');
    overlay.classList.add('flex');
    document.body.style.overflow = 'hidden';

    const container = overlay.querySelector('#three-canvas');
    const progressBar = overlay.querySelector('[data-3d-progress]');
    const progressFill = overlay.querySelector('[data-3d-progress-fill]');
    const errorEl = overlay.querySelector('[data-3d-error]');
    const controlsEl = overlay.querySelector('[data-3d-controls]');

    if (errorEl) errorEl.classList.add('hidden');
    if (controlsEl) controlsEl.classList.add('opacity-0');
    if (progressBar) progressBar.classList.remove('hidden');

    import('./3d-viewer/index.js').then(({ ProductViewer3D }) => {
        const viewer = new ProductViewer3D(container, { autoRotate: true });
        viewer.init();

        viewer.load(productData.model_url, (progress) => {
            if (progressFill) {
                progressFill.style.width = `${Math.round(progress * 100)}%`;
            }
        }).then(() => {
            if (progressBar) progressBar.classList.add('hidden');
            if (controlsEl) {
                controlsEl.classList.remove('opacity-0');
                controlsEl.classList.add('opacity-100', 'transition-opacity', 'duration-500');
            }
            setupViewerControls(viewer, { container: overlay });
            overlay._viewer = viewer;
        }).catch((err) => {
            console.error('3D model load failed:', err);
            if (progressBar) progressBar.classList.add('hidden');
            if (errorEl) errorEl.classList.remove('hidden');
        });
    }).catch((err) => {
        console.error('3D viewer module load failed:', err);
        if (progressBar) progressBar.classList.add('hidden');
        if (errorEl) errorEl.classList.remove('hidden');
    });

    const closeBtn = overlay.querySelector('[data-3d-close]');
    if (closeBtn) {
        closeBtn.addEventListener('click', () => close3DViewer(overlay), { once: true });
    }

    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) close3DViewer(overlay);
    }, { once: true });
}

function close3DViewer(overlay) {
    if (overlay._viewer) {
        overlay._viewer.destroy();
        delete overlay._viewer;
    }
    overlay.classList.add('hidden');
    overlay.classList.remove('flex');
    document.body.style.overflow = '';
}
