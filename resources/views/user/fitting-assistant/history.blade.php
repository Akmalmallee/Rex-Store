@php $title = 'AI Fitting History'; @endphp
<x-app-layout>
    <div class="pt-24 pb-16 bg-[#0a0a0a] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex mb-10 text-[10px] tracking-widest uppercase text-gray-600 font-light reveal">
                <a href="{{ route('home') }}" class="hover:text-[#C8A951] transition-colors">Home</a>
                <span class="mx-2 text-gray-700">·</span>
                <span class="text-white/60">AI Fitting</span>
            </nav>

            <div class="reveal">
                <div class="flex items-center justify-between mb-10">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 border border-[#C8A951]/30 flex items-center justify-center">
                            <svg class="w-6 h-6 text-[#C8A951]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-light text-white">AI Fitting Assistant</h1>
                            <p class="text-xs font-light text-gray-500 mt-1">Your personalized fit sessions and recommendations.</p>
                        </div>
                    </div>
                    <a href="{{ route('fitting.profile') }}" class="btn-luxury text-xs">
                        {{ auth()->user()->bodyProfile ? 'Edit Profile' : 'Create Profile' }}
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-8 p-4 border border-[#C8A951]/20 bg-[#C8A951]/5 text-[#C8A951] text-xs font-light">{{ session('success') }}</div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Sidebar: Style Tips -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="glass-card p-6 reveal">
                        <div class="flex items-center gap-3 mb-4">
                            <svg class="w-4 h-4 text-[#C8A951]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <span class="text-[10px] tracking-widest uppercase font-light text-white/80">Style Tips</span>
                        </div>
                        @if(!empty($styleTips))
                            <div class="space-y-3">
                                <div>
                                    <p class="text-[10px] uppercase text-gray-600 font-light mb-2">Recommended for you</p>
                                    <ul class="space-y-2">
                                        @foreach($styleTips['recommended_styles'] ?? [] as $tip)
                                        <li class="flex items-start gap-2 text-xs font-light text-gray-400">
                                            <span class="text-[#C8A951] mt-0.5">▸</span>
                                            {{ $tip }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @if(!empty($styleTips['avoid']))
                                <div>
                                    <p class="text-[10px] uppercase text-gray-600 font-light mb-2">Try to avoid</p>
                                    <ul class="space-y-2">
                                        @foreach($styleTips['avoid'] as $tip)
                                        <li class="flex items-start gap-2 text-xs font-light text-gray-500">
                                            <span class="text-red-400/60 mt-0.5">▸</span>
                                            {{ $tip }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>
                        @else
                            <p class="text-xs font-light text-gray-600">Complete your body profile to get style tips.</p>
                            <a href="{{ route('fitting.profile') }}" class="text-[10px] tracking-widest uppercase text-[#C8A951] hover:text-white transition-colors font-light mt-3 inline-block">Set Up Profile →</a>
                        @endif
                    </div>

                    <div class="glass-card p-6 reveal">
                        <div class="flex items-center gap-3 mb-4">
                            <svg class="w-4 h-4 text-[#C8A951]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-[10px] tracking-widest uppercase font-light text-white/80">Quick Actions</span>
                        </div>
                        <div class="space-y-3">
                            <a href="{{ route('fitting.profile') }}" class="flex items-center justify-between text-xs font-light text-gray-400 hover:text-white transition-colors py-2 border-b border-white/5">
                                <span>Edit Body Profile</span>
                                <span class="text-[#C8A951]">→</span>
                            </a>
                            <a href="{{ route('fitting.photo') }}" class="flex items-center justify-between text-xs font-light text-gray-400 hover:text-white transition-colors py-2 border-b border-white/5">
                                <span>Upload Photo</span>
                                <span class="text-[#C8A951]">→</span>
                            </a>
                            <a href="{{ route('shop') }}" class="flex items-center justify-between text-xs font-light text-gray-400 hover:text-white transition-colors py-2">
                                <span>Browse Products</span>
                                <span class="text-[#C8A951]">→</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Main: Sessions + Recommendations -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Fitting Sessions -->
                    <div class="reveal">
                        <h2 class="text-xs tracking-[0.3em] uppercase text-gray-500 font-light mb-6">Recent Fitting Sessions</h2>
                        @if($sessions->isNotEmpty())
                            <div class="space-y-4">
                                @foreach($sessions as $session)
                                <div class="glass-card p-5 flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        @if($session->product)
                                        <div class="w-14 h-14 bg-[#111] border border-white/5 overflow-hidden shrink-0">
                                            <img src="{{ $session->product->thumbnail ? (str_starts_with($session->product->thumbnail, 'http') ? $session->product->thumbnail : Storage::url($session->product->thumbnail)) : 'https://picsum.photos/seed/' . $session->product->id . '/200/200' }}"
                                                 alt="" class="w-full h-full object-cover">
                                        </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-light text-white">{{ $session->product->name ?? 'Unknown Product' }}</p>
                                            <div class="flex items-center gap-3 mt-1">
                                                @if($session->size_recommended)
                                                <span class="text-[10px] uppercase text-[#C8A951] font-light">Size: {{ $session->size_recommended }}</span>
                                                @endif
                                                @if($session->fit_score)
                                                <span class="text-[10px] text-gray-500 font-light">Score: {{ number_format($session->fit_score * 100) }}%</span>
                                                @endif
                                                <span class="text-[10px] text-gray-600 font-light">{{ $session->created_at->diffForHumans() }}</span>
                                            </div>
                                            @if($session->user_feedback)
                                            <p class="text-[10px] text-gray-600 font-light mt-1">Feedback: "{{ $session->user_feedback }}"</p>
                                            @endif
                                        </div>
                                    </div>
                                    @if(!$session->user_feedback)
                                    <button onclick="showFeedback({{ $session->id }})" class="text-[10px] tracking-widest uppercase text-gray-500 hover:text-[#C8A951] transition-colors font-light">Feedback</button>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="glass-card p-12 text-center">
                                <svg class="w-12 h-12 mx-auto text-gray-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-sm font-light text-gray-500">No fitting sessions yet.</p>
                                <p class="text-xs font-light text-gray-600 mt-2">Browse products and use the AI Fit button to get started.</p>
                                <a href="{{ route('shop') }}" class="btn-luxury mt-6 inline-block">Browse Shop</a>
                            </div>
                        @endif
                    </div>

                    <!-- Recommendations -->
                    <div class="reveal">
                        <h2 class="text-xs tracking-[0.3em] uppercase text-gray-500 font-light mb-6">Active Recommendations</h2>
                        @if($recommendations->isNotEmpty())
                            <div class="space-y-4">
                                @foreach($recommendations as $rec)
                                <div class="glass-card p-5 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <span class="px-2 py-0.5 text-[10px] tracking-widest uppercase border border-[#C8A951]/20 text-[#C8A951] font-light">{{ $rec->type }}</span>
                                        <div>
                                            <p class="text-xs font-light text-gray-400">{{ $rec->reason }}</p>
                                            <p class="text-[10px] text-gray-600 mt-1">Score: {{ number_format($rec->score * 100) }}% · {{ $rec->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <form method="POST" action="{{ route('fitting.dismiss', $rec->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-[10px] tracking-widest uppercase text-gray-500 hover:text-red-400 transition-colors font-light">Dismiss</button>
                                    </form>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="glass-card p-8 text-center">
                                <p class="text-sm font-light text-gray-500">No active recommendations.</p>
                                <p class="text-xs font-light text-gray-600 mt-2">Sessions recommendations appear here after using the AI Fit assistant.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        window.showFeedback = function (sessionId) {
            const feedback = prompt('How was the fit suggestion? (e.g., "Perfect fit!", "A bit tight", "True to size")');
            if (feedback && feedback.trim()) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/fitting/sessions/' + sessionId + '/feedback';
                form.innerHTML = `
                    @csrf
                    <input type="hidden" name="feedback" value="${feedback.trim()}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        };
    </script>
    @endpush
</x-app-layout>
