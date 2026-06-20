@props(['product', 'profile' => null, 'recommendations' => null])

<div id="fitting-modal-overlay" class="fixed inset-0 z-[60] hidden items-center justify-center bg-[#0a0a0a]/95 backdrop-blur-xl">
    <div class="relative w-full max-w-6xl mx-4 lg:mx-auto max-h-[90vh] overflow-y-auto bg-[#0a0a0a] border border-white/5">
        <!-- Close -->
        <button data-fitting-close class="absolute top-4 right-4 z-10 w-10 h-10 border border-white/10 flex items-center justify-center text-gray-500 hover:text-white hover:border-white/30 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <!-- Stepper -->
        <div class="sticky top-0 z-10 bg-[#0a0a0a] border-b border-white/5 px-8 py-6">
            <div class="flex items-center justify-center gap-0">
                @php $steps = [['icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'label' => 'Profile'], ['icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Photo'], ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Result']]; @endphp
                @foreach($steps as $i => $step)
                    <div class="flex items-center">
                        <div data-fitting-step="{{ $i + 1 }}" class="flex items-center gap-3 px-6 py-2 {{ $i === 0 ? 'text-[#C8A951]' : 'text-gray-600' }} transition-colors duration-300 cursor-pointer hover:text-[#C8A951]/60">
                            <div class="w-8 h-8 border border-current flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $step['icon'] }}"/></svg>
                            </div>
                            <span class="text-[10px] tracking-[0.25em] uppercase font-light hidden sm:block">{{ $step['label'] }}</span>
                        </div>
                        @if(!$loop->last)
                            <div class="w-12 h-px bg-white/5"></div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Content -->
        <div class="p-8 lg:p-10">
            <!-- Step 1: Profile -->
            <div data-fitting-panel="1" class="fitting-panel">
                <div class="max-w-2xl mx-auto">
                    <div class="text-center mb-10">
                        <div class="w-16 h-16 mx-auto mb-4 border border-[#C8A951]/30 flex items-center justify-center">
                            <svg class="w-8 h-8 text-[#C8A951]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-light text-white mb-2">Your Body Profile</h2>
                        <p class="text-xs font-light text-gray-500">Help us find the perfect fit by sharing your measurements.</p>
                    </div>

                    <form id="fitting-profile-form" method="POST" action="{{ route('fitting.profile.save') }}" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="label-luxury">Height (cm)</label>
                                <input type="number" name="height" step="0.1" value="{{ $profile->height ?? '' }}" placeholder="e.g. 170" class="input-luxury">
                            </div>
                            <div>
                                <label class="label-luxury">Weight (kg)</label>
                                <input type="number" name="weight" step="0.1" value="{{ $profile->weight ?? '' }}" placeholder="e.g. 65" class="input-luxury">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="label-luxury">Body Type</label>
                                <select name="body_type" class="input-luxury">
                                    <option value="">Select body type...</option>
                                    @foreach(['slim' => 'Slim', 'average' => 'Average', 'athletic' => 'Athletic', 'plus' => 'Plus Size', 'hourglass' => 'Hourglass', 'pear' => 'Pear', 'apple' => 'Apple', 'rectangle' => 'Rectangle'] as $val => $label)
                                        <option value="{{ $val }}" {{ isset($profile) && $profile->body_type === $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="label-luxury">Preferred Size</label>
                                <select name="preferred_size" class="input-luxury">
                                    <option value="">Select size...</option>
                                    @foreach(['XS', 'S', 'M', 'L', 'XL'] as $size)
                                        <option value="{{ $size }}" {{ isset($profile) && $profile->preferred_size === $size ? 'selected' : '' }}>{{ $size }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <p class="text-[10px] tracking-widest uppercase text-gray-500 font-light mb-4">Detailed Measurements (optional)</p>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                @php $fields = [['name' => 'shoulder_width', 'label' => 'Shoulders (cm)'], ['name' => 'chest_circumference', 'label' => 'Chest (cm)'], ['name' => 'waist_circumference', 'label' => 'Waist (cm)'], ['name' => 'hip_circumference', 'label' => 'Hips (cm)']]; @endphp
                                @foreach($fields as $field)
                                <div>
                                    <label class="text-[10px] tracking-widest uppercase text-gray-600 font-light">{{ $field['label'] }}</label>
                                    <input type="number" name="{{ $field['name'] }}" step="0.1" value="{{ $profile->{$field['name']} ?? '' }}" class="input-luxury mt-2">
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex justify-end gap-4 pt-4">
                            <button type="button" data-fitting-next class="btn-luxury">Save & Continue</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Step 2: Photo -->
            <div data-fitting-panel="2" class="fitting-panel hidden">
                <div class="max-w-2xl mx-auto">
                    <div class="text-center mb-10">
                        <div class="w-16 h-16 mx-auto mb-4 border border-[#C8A951]/30 flex items-center justify-center">
                            <svg class="w-8 h-8 text-[#C8A951]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-light text-white mb-2">Virtual Try-On</h2>
                        <p class="text-xs font-light text-gray-500">Upload a full-body photo for proportion analysis.</p>
                    </div>

                    <div id="photo-upload-zone" class="border-2 border-dashed border-white/10 hover:border-[#C8A951]/30 transition-colors p-12 text-center cursor-pointer">
                        <div class="flex flex-col items-center gap-4">
                            <svg class="w-12 h-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-light text-gray-400">Drop your photo here or <span class="text-[#C8A951] underline underline-offset-4">browse</span></p>
                                <p class="text-[10px] font-light text-gray-600 mt-2">Full-body, well-lit photo recommended (max 5MB)</p>
                            </div>
                            <input type="file" id="photo-input" accept="image/jpeg,image/png" class="hidden">
                        </div>
                    </div>

                    <div id="photo-preview" class="hidden mt-6">
                        <div class="relative aspect-video bg-[#111] overflow-hidden">
                            <img id="photo-preview-img" class="w-full h-full object-contain" alt="Your photo">
                            <canvas id="proportion-canvas" class="absolute inset-0 w-full h-full pointer-events-none"></canvas>
                        </div>
                        <div class="mt-4 flex items-center justify-between">
                            <p class="text-xs font-light text-gray-500">
                                <span class="text-[#C8A951]">✓</span> Photo uploaded successfully
                            </p>
                            <button id="change-photo-btn" class="text-[10px] tracking-widest uppercase text-gray-500 hover:text-[#C8A951] transition-colors font-light">Change Photo</button>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 mt-8">
                        <button type="button" data-fitting-prev class="text-[10px] tracking-widest uppercase text-gray-500 hover:text-white transition-colors font-light">Back</button>
                        <button type="button" data-fitting-next class="btn-luxury">Continue</button>
                    </div>
                </div>
            </div>

            <!-- Step 3: Result -->
            <div data-fitting-panel="3" class="fitting-panel hidden">
                <div class="text-center mb-6">
                    <div class="w-12 h-12 mx-auto mb-3 border border-[#C8A951]/30 flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#C8A951]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-light text-white mb-1">AI Recommendations</h2>
                    <p class="text-xs font-light text-gray-500">Personalized fit suggestions based on your profile.</p>
                </div>

                <!-- Loading State -->
                <div id="recommendation-loading" class="flex flex-col items-center justify-center py-16">
                    <div class="w-12 h-12 border border-[#C8A951]/30 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-[#C8A951] animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <p class="text-sm font-light text-gray-400">Analyzing your profile...</p>
                    <div class="w-48 h-0.5 bg-white/5 mt-4">
                        <div class="h-full bg-[#C8A951] transition-all duration-1000" style="width: 0%"></div>
                    </div>
                </div>

                <!-- Results -->
                <div id="recommendation-results" class="hidden">
                    @if($product)
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- 3D Mannequin -->
                        <div id="fitting-mannequin-container"
                             class="glass-card aspect-[3/4] lg:aspect-auto lg:min-h-[400px] relative overflow-hidden">
                            <div class="absolute inset-0 flex items-center justify-center text-gray-700 text-xs font-light">
                                Loading 3D preview...
                            </div>
                        </div>

                        <!-- Results Sidebar -->
                        <div class="space-y-6">
                            <!-- Size Recommendation -->
                            <div id="size-recommendation" class="glass-card p-6">
                                <div class="flex items-center gap-3 mb-4">
                                    <svg class="w-5 h-5 text-[#C8A951]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                                    </svg>
                                    <span class="text-xs tracking-widest uppercase font-light text-white/80">Recommended Size</span>
                                </div>
                                <div id="size-result" class="flex items-center gap-6">
                                    <span class="text-5xl font-light text-[#C8A951]">--</span>
                                    <div>
                                        <p id="size-reason" class="text-sm font-light text-gray-400"></p>
                                        <div class="flex items-center gap-2 mt-2">
                                            <span class="text-xs font-light text-gray-600">Fit Score:</span>
                                            <div class="flex gap-0.5" id="fit-score-dots"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Outfit Recommendations -->
                            <div id="outfit-recommendations">
                                <div class="flex items-center gap-3 mb-4">
                                    <svg class="w-5 h-5 text-[#C8A951]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <span class="text-xs tracking-widest uppercase font-light text-white/80">Complete the Look</span>
                                </div>
                                <div id="outfit-grid" class="space-y-3">
                                    <!-- Populated by JS -->
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" data-fitting-prev class="text-[10px] tracking-widest uppercase text-gray-500 hover:text-white transition-colors font-light">Back</button>
                    <button type="button" data-fitting-close class="btn-luxury">Done</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.fittingProductSlug = '{{ $product->slug ?? '' }}';
    window.fittingRecommendations = @json($recommendations);
    window.fittingProfile = @json($profile);
</script>
@endpush
