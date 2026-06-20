@php $title = 'Body Profile'; @endphp
<x-app-layout>
    <div class="pt-24 pb-16 bg-[#0a0a0a] min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex mb-10 text-[10px] tracking-widest uppercase text-gray-600 font-light reveal">
                <a href="{{ route('home') }}" class="hover:text-[#C8A951] transition-colors">Home</a>
                <span class="mx-2 text-gray-700">·</span>
                <a href="{{ route('fitting.history') }}" class="hover:text-[#C8A951] transition-colors">AI Fitting</a>
                <span class="mx-2 text-gray-700">·</span>
                <span class="text-white/60">Body Profile</span>
            </nav>

            @if(session('success'))
                <div class="mb-8 p-4 border border-[#C8A951]/20 bg-[#C8A951]/5 text-[#C8A951] text-xs font-light">{{ session('success') }}</div>
            @endif

            <div class="reveal">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-12 h-12 border border-[#C8A951]/30 flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#C8A951]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-light text-white">Body Profile</h1>
                        <p class="text-xs font-light text-gray-500 mt-1">Help us find the perfect fit with your measurements.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('fitting.profile.save') }}" class="space-y-8">
                    @csrf
                    <div class="glass-card p-8">
                        <p class="text-xs tracking-widest uppercase font-light text-gray-500 mb-6">Basic Measurements</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="label-luxury">Height (cm)</label>
                                <input type="number" name="height" step="0.1" value="{{ $profile->height ?? '' }}" placeholder="e.g. 170" class="input-luxury">
                                @error('height') <p class="text-[10px] text-red-400 mt-1 font-light">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="label-luxury">Weight (kg)</label>
                                <input type="number" name="weight" step="0.1" value="{{ $profile->weight ?? '' }}" placeholder="e.g. 65" class="input-luxury">
                                @error('weight') <p class="text-[10px] text-red-400 mt-1 font-light">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="glass-card p-8">
                        <p class="text-xs tracking-widest uppercase font-light text-gray-500 mb-6">Body Characteristics</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="label-luxury">Body Type</label>
                                <select name="body_type" class="input-luxury">
                                    <option value="">Select body type...</option>
                                    @foreach(['slim' => 'Slim', 'average' => 'Average', 'athletic' => 'Athletic', 'plus' => 'Plus Size', 'hourglass' => 'Hourglass', 'pear' => 'Pear', 'apple' => 'Apple', 'rectangle' => 'Rectangle'] as $val => $label)
                                        <option value="{{ $val }}" {{ $profile && $profile->body_type === $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('body_type') <p class="text-[10px] text-red-400 mt-1 font-light">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="label-luxury">Preferred Size</label>
                                <select name="preferred_size" class="input-luxury">
                                    <option value="">Select size...</option>
                                    @foreach(['XS', 'S', 'M', 'L', 'XL'] as $size)
                                        <option value="{{ $size }}" {{ $profile && $profile->preferred_size === $size ? 'selected' : '' }}>{{ $size }}</option>
                                    @endforeach
                                </select>
                                @error('preferred_size') <p class="text-[10px] text-red-400 mt-1 font-light">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mt-8">
                            <p class="text-[10px] tracking-widest uppercase text-gray-600 font-light mb-4">Detailed Measurements (for precise fit)</p>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                @php $fields = [['name' => 'shoulder_width', 'label' => 'Shoulders (cm)'], ['name' => 'chest_circumference', 'label' => 'Chest (cm)'], ['name' => 'waist_circumference', 'label' => 'Waist (cm)'], ['name' => 'hip_circumference', 'label' => 'Hips (cm)']]; @endphp
                                @foreach($fields as $f)
                                <div>
                                    <label class="text-[10px] tracking-widest uppercase text-gray-600 font-light">{{ $f['label'] }}</label>
                                    <input type="number" name="{{ $f['name'] }}" step="0.1" value="{{ $profile->{$f['name']} ?? '' }}" class="input-luxury mt-2">
                                    @error($f['name']) <p class="text-[10px] text-red-400 mt-1 font-light">{{ $message }}</p> @enderror
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4">
                        <a href="{{ route('fitting.history') }}" class="text-xs tracking-widest uppercase text-gray-500 hover:text-white transition-colors font-light px-6 py-3">Cancel</a>
                        <button type="submit" class="btn-luxury">Save Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
