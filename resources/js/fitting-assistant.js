import { FittingMannequin } from './fitting-mannequin';

let fittingCurrentStep = 1;
let fittingProductSlug = '';
let fittingUploadedPhoto = null;
let fittingMannequinInstance = null;

export function openFittingModal(slug) {
    fittingProductSlug = slug;
    const modal = document.getElementById('fitting-modal-overlay');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        setFittingStep(1);
        if (!window.fittingRecommendations?.size_recommendation) {
            loadFittingRecommendations(slug);
        }
    }
}

export function closeFittingModal() {
    const modal = document.getElementById('fitting-modal-overlay');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
    destroyFittingMannequin();
}

function setFittingStep(step) {
    const prevStep = fittingCurrentStep;
    fittingCurrentStep = step;
    document.querySelectorAll('[data-fitting-panel]').forEach(p => p.classList.add('hidden'));
    const panel = document.querySelector(`[data-fitting-panel="${step}"]`);
    if (panel) panel.classList.remove('hidden');

    if (step === 3) {
        const recs = window.fittingRecommendations;
        if (recs && recs.size_recommendation) {
            document.getElementById('recommendation-loading')?.classList.add('hidden');
            document.getElementById('recommendation-results')?.classList.remove('hidden');
            displaySizeRecommendation(recs);
            displayOutfitRecommendations(recs);
            initFittingMannequin();
        }
    }

    if (prevStep === 3 && step !== 3) {
        destroyFittingMannequin();
    }

    document.querySelectorAll('[data-fitting-step]').forEach((el, i) => {
        const num = i + 1;
        if (num === step) {
            el.classList.add('text-[#C8A951]');
            el.classList.remove('text-gray-600');
        } else if (num < step) {
            el.classList.add('text-green-500');
            el.classList.remove('text-gray-600', 'text-[#C8A951]');
        } else {
            el.classList.add('text-gray-600');
            el.classList.remove('text-[#C8A951]', 'text-green-500');
        }
    });
}

function initFittingMannequin() {
    if (fittingMannequinInstance) return;
    const container = document.getElementById('fitting-mannequin-container');
    if (!container) return;
    fittingMannequinInstance = new FittingMannequin('fitting-mannequin-container', window.fittingProfile);
    fittingMannequinInstance.init();
}

function destroyFittingMannequin() {
    if (fittingMannequinInstance) {
        fittingMannequinInstance.destroy();
        fittingMannequinInstance = null;
    }
}

async function loadFittingRecommendations(slug) {
    try {
        const resp = await fetch(`/fitting/${slug}/recommendations`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        });
        if (!resp.ok) throw new Error('Failed to load recommendations');
        const data = await resp.json();

        if (data.requires_profile) {
            setFittingStep(1);
            return;
        }

        window.fittingRecommendations = data;

        if (fittingCurrentStep === 3) {
            document.getElementById('recommendation-loading')?.classList.add('hidden');
            document.getElementById('recommendation-results')?.classList.remove('hidden');
            displaySizeRecommendation(data);
            displayOutfitRecommendations(data);
        }
    } catch (err) {
        console.error('Fitting recommendation error:', err);
    }
}

function displaySizeRecommendation(data) {
    const sizeEl = document.getElementById('size-result');
    const reasonEl = document.getElementById('size-reason');
    const dotsEl = document.getElementById('fit-score-dots');

    if (!sizeEl || !data.size_recommendation) return;

    const rec = data.size_recommendation;
    sizeEl.querySelector('span').textContent = rec.recommended_size || '--';
    if (reasonEl) reasonEl.textContent = rec.reason || '';
    if (dotsEl) {
        dotsEl.innerHTML = '';
        const score = rec.fit_score || 0;
        const numDots = 5;
        for (let i = 0; i < numDots; i++) {
            const dot = document.createElement('span');
            const fill = i / numDots < score;
            dot.className = `w-4 h-1 ${fill ? 'bg-[#C8A951]' : 'bg-white/10'}`;
            dotsEl.appendChild(dot);
        }
    }
}

function displayOutfitRecommendations(data) {
    const grid = document.getElementById('outfit-grid');
    if (!grid) return;

    grid.innerHTML = '';

    const outfits = data.outfit_recommendations || [];
    if (outfits.length === 0) {
        grid.innerHTML = '<p class="text-sm font-light text-gray-500 col-span-full text-center py-8">No outfit combinations available.</p>';
        return;
    }

    outfits.forEach(outfit => {
        const card = document.createElement('div');
        card.className = 'glass-card p-4';
        const scorePct = Math.round((outfit.score || 0) * 100);
        card.innerHTML = `
            <div class="flex items-center justify-between mb-3">
                <span class="text-[10px] tracking-widest uppercase font-light ${outfit.style === 'complete' ? 'text-[#C8A951]' : 'text-gray-500'}">${outfit.style} look</span>
                <span class="text-[10px] font-light text-gray-500">${scorePct}% match</span>
            </div>
            <p class="text-xs font-light text-gray-400 mb-3">${outfit.reason || ''}</p>
            <div class="flex items-center gap-2">
                <span class="text-[10px] font-light text-gray-600">${outfit.items.length} items</span>
            </div>
        `;
        grid.appendChild(card);
    });
}

function handlePhotoUpload(file) {
    fittingUploadedPhoto = file;
    const preview = document.getElementById('photo-preview');
    const previewImg = document.getElementById('photo-preview-img');
    if (preview && previewImg && file) {
        preview.classList.remove('hidden');
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImg.src = e.target.result;
            drawProportionLines();
        };
        reader.readAsDataURL(file);
    }
}

function drawProportionLines() {
    const canvas = document.getElementById('proportion-canvas');
    if (!canvas) return;
    const rect = canvas.parentElement.getBoundingClientRect();
    canvas.width = rect.width;
    canvas.height = rect.height;
    const ctx = canvas.getContext('2d');
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    const w = canvas.width;
    const h = canvas.height;

    ctx.strokeStyle = 'rgba(200, 169, 81, 0.3)';
    ctx.lineWidth = 1;
    ctx.setLineDash([6, 4]);

    const midX = w / 2;
    ctx.beginPath();
    ctx.moveTo(midX, 0);
    ctx.lineTo(midX, h);
    ctx.stroke();

    const headEnd = h * 0.18;
    const torsoEnd = h * 0.48;
    const legEnd = h * 0.88;

    ctx.beginPath();
    ctx.moveTo(midX - 40, headEnd);
    ctx.lineTo(midX + 40, headEnd);
    ctx.stroke();

    ctx.beginPath();
    ctx.moveTo(midX - 50, torsoEnd);
    ctx.lineTo(midX + 50, torsoEnd);
    ctx.stroke();

    ctx.setLineDash([]);
    ctx.fillStyle = 'rgba(200, 169, 81, 0.6)';
    ctx.font = '10px sans-serif';
    ctx.fillText('Head', midX + 10, headEnd - 4);
    ctx.fillText('Torso', midX + 10, (headEnd + torsoEnd) / 2);
    ctx.fillText('Legs', midX + 10, (torsoEnd + legEnd) / 2);
}

document.addEventListener('DOMContentLoaded', () => {
    // Close handlers
    document.querySelectorAll('[data-fitting-close]').forEach(btn => {
        btn.addEventListener('click', closeFittingModal);
    });

    // Next step handlers
    document.querySelectorAll('[data-fitting-next]').forEach(btn => {
        btn.addEventListener('click', () => {
            if (fittingCurrentStep < 3) {
                if (fittingCurrentStep === 1) {
                    const form = document.getElementById('fitting-profile-form');
                    if (form) {
                        const formData = new FormData(form);
                        fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        }).catch(() => {}).finally(() => {
                            setFittingStep(fittingCurrentStep + 1);
                        });
                        return;
                    }
                }
                setFittingStep(fittingCurrentStep + 1);
            }
        });
    });

    // Prev step handlers
    document.querySelectorAll('[data-fitting-prev]').forEach(btn => {
        btn.addEventListener('click', () => {
            if (fittingCurrentStep > 1) setFittingStep(fittingCurrentStep - 1);
        });
    });

    // Photo upload handling
    const photoZone = document.getElementById('photo-upload-zone');
    const photoInput = document.getElementById('photo-input');
    if (photoZone && photoInput) {
        photoZone.addEventListener('click', () => photoInput.click());
        photoZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            photoZone.classList.add('border-[#C8A951]/50');
        });
        photoZone.addEventListener('dragleave', () => {
            photoZone.classList.remove('border-[#C8A951]/50');
        });
        photoZone.addEventListener('drop', (e) => {
            e.preventDefault();
            photoZone.classList.remove('border-[#C8A951]/50');
            if (e.dataTransfer.files.length > 0) handlePhotoUpload(e.dataTransfer.files[0]);
        });
        photoInput.addEventListener('change', () => {
            if (photoInput.files.length > 0) handlePhotoUpload(photoInput.files[0]);
        });
    }

    // Change photo
    document.getElementById('change-photo-btn')?.addEventListener('click', () => {
        document.getElementById('photo-preview')?.classList.add('hidden');
        document.getElementById('photo-upload-zone')?.classList.remove('hidden');
        fittingUploadedPhoto = null;
    });

    // Auto-show results if recommendations passed
    const recs = window.fittingRecommendations;
    if (recs && recs.size_recommendation) {
        document.getElementById('recommendation-loading')?.classList.add('hidden');
        document.getElementById('recommendation-results')?.classList.remove('hidden');
        displaySizeRecommendation(recs);
        displayOutfitRecommendations(recs);
    }
});

window.openFittingModal = openFittingModal;
window.closeFittingModal = closeFittingModal;
