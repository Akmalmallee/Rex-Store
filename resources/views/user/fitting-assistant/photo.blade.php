@php $title = 'Virtual Try-On'; @endphp
<x-app-layout>
    <div class="pt-24 pb-16 bg-[#0a0a0a] min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex mb-10 text-[10px] tracking-widest uppercase text-gray-600 font-light reveal">
                <a href="{{ route('home') }}" class="hover:text-[#C8A951] transition-colors">Home</a>
                <span class="mx-2 text-gray-700">·</span>
                <a href="{{ route('fitting.history') }}" class="hover:text-[#C8A951] transition-colors">AI Fitting</a>
                <span class="mx-2 text-gray-700">·</span>
                <span class="text-white/60">Virtual Try-On</span>
            </nav>

            @if(session('success'))
                <div class="mb-8 p-4 border border-[#C8A951]/20 bg-[#C8A951]/5 text-[#C8A951] text-xs font-light">{{ session('success') }}</div>
            @endif

            <div class="reveal">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-12 h-12 border border-[#C8A951]/30 flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#C8A951]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-light text-white">Virtual Try-On</h1>
                        <p class="text-xs font-light text-gray-500 mt-1">Upload a full-body photo for proportion analysis and fit recommendations.</p>
                    </div>
                </div>

                <form id="photo-form" class="space-y-8" enctype="multipart/form-data">
                    @csrf
                    <div class="glass-card p-8">
                        <div id="photo-upload-wrapper">
                            <div id="photo-dropzone" class="border-2 border-dashed border-white/10 hover:border-[#C8A951]/30 transition-colors p-16 text-center cursor-pointer">
                                <div class="flex flex-col items-center gap-4">
                                    <svg class="w-16 h-16 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-light text-gray-400">Drop your photo here or <span class="text-[#C8A951] underline underline-offset-4">browse</span></p>
                                        <p class="text-xs font-light text-gray-600 mt-2">Full-body, well-lit photo recommended (JPEG/PNG, max 5MB)</p>
                                    </div>
                                    <input type="file" name="photo" id="photo-file" accept="image/jpeg,image/png" class="hidden">
                                </div>
                            </div>
                        </div>

                        <div id="photo-preview-wrapper" class="hidden">
                            <div class="relative aspect-video bg-[#111] overflow-hidden">
                                <img id="preview-image" class="w-full h-full object-contain" alt="Your photo">
                                <canvas id="proportion-canvas" class="absolute inset-0 w-full h-full pointer-events-none"></canvas>
                            </div>
                            <div class="flex items-center justify-between mt-4">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                    <span id="photo-status" class="text-xs font-light text-gray-400">Photo loaded</span>
                                </div>
                                <button id="change-photo-btn" type="button" class="text-[10px] tracking-widest uppercase text-gray-500 hover:text-[#C8A951] transition-colors font-light">Change</button>
                            </div>
                            <div id="analysis-result" class="hidden mt-6 p-4 border border-[#C8A951]/20 bg-[#C8A951]/5">
                                <p class="text-xs font-light text-[#C8A951]">
                                    <span class="text-white/80">Proportion Analysis:</span> Upload successful. Connect AI provider for detailed analysis.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4">
                        <a href="{{ route('fitting.history') }}" class="text-xs tracking-widest uppercase text-gray-500 hover:text-white transition-colors font-light px-6 py-3">Cancel</a>
                        <button type="submit" class="btn-luxury" id="upload-btn">Upload Photo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropzone = document.getElementById('photo-dropzone');
            const fileInput = document.getElementById('photo-file');
            const previewWrapper = document.getElementById('photo-preview-wrapper');
            const uploadWrapper = document.getElementById('photo-upload-wrapper');
            const previewImg = document.getElementById('preview-image');
            const changeBtn = document.getElementById('change-photo-btn');
            const photoForm = document.getElementById('photo-form');
            const uploadBtn = document.getElementById('upload-btn');
            const canvas = document.getElementById('proportion-canvas');
            const ctx = canvas.getContext('2d');

            dropzone.addEventListener('click', () => fileInput.click());

            dropzone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropzone.classList.add('border-[#C8A951]/50');
            });

            dropzone.addEventListener('dragleave', () => {
                dropzone.classList.remove('border-[#C8A951]/50');
            });

            dropzone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropzone.classList.remove('border-[#C8A951]/50');
                if (e.dataTransfer.files.length > 0) {
                    fileInput.files = e.dataTransfer.files;
                    handleFile(e.dataTransfer.files[0]);
                }
            });

            fileInput.addEventListener('change', () => {
                if (fileInput.files.length > 0) {
                    handleFile(fileInput.files[0]);
                }
            });

            changeBtn.addEventListener('click', () => {
                previewWrapper.classList.add('hidden');
                uploadWrapper.classList.remove('hidden');
                fileInput.value = '';
            });

            function handleFile(file) {
                if (!file.type.startsWith('image/')) return;
                const reader = new FileReader();
                reader.onload = (e) => {
                    previewImg.src = e.target.result;
                    previewImg.onload = () => drawProportionLines();
                    uploadWrapper.classList.add('hidden');
                    previewWrapper.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }

            function drawProportionLines() {
                const rect = canvas.parentElement.getBoundingClientRect();
                canvas.width = rect.width;
                canvas.height = rect.height;
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

                const headRatio = 0.13;
                const torsoRatio = 0.30;
                const legRatio = 0.50;

                const topY = h * 0.05;
                const headEnd = topY + (h * headRatio);
                const torsoEnd = headEnd + (h * torsoRatio);
                const legEnd = torsoEnd + (h * legRatio);

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

            window.addEventListener('resize', () => {
                if (!previewWrapper.classList.contains('hidden')) {
                    drawProportionLines();
                }
            });

            photoForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(photoForm);
                uploadBtn.disabled = true;
                uploadBtn.textContent = 'Uploading...';

                try {
                    const resp = await fetch('{{ route("fitting.photo.upload") }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: formData,
                    });
                    const data = await resp.json();
                    if (resp.ok) {
                        const statusEl = document.getElementById('photo-status');
                        statusEl.textContent = '✓ ' + data.message;
                        document.getElementById('analysis-result').classList.remove('hidden');
                    } else {
                        alert('Upload failed: ' + (data.message || 'Unknown error'));
                    }
                } catch (err) {
                    alert('Upload failed. Please try again.');
                } finally {
                    uploadBtn.disabled = false;
                    uploadBtn.textContent = 'Upload Photo';
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
