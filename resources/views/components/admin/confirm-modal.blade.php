@props([
    'title' => 'Confirm Action',
    'message' => 'Are you sure you want to proceed?',
    'confirmText' => 'Delete',
    'cancelText' => 'Cancel',
    'show' => false,
])

<div
    x-data="{
        show: @js($show),
        form: null,
        confirmAction(formEl) {
            this.form = formEl;
            this.show = true;
        },
        execute() {
            if (this.form) this.form.submit();
            this.show = false;
        }
    }"
    x-on:open-confirm.window="confirmAction($event.detail)"
    x-show="show"
    class="fixed inset-0 z-50 flex items-center justify-center px-4"
    style="display: none;"
>
    <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/60 backdrop-blur-sm" x-on:click="show = false"></div>
    <div
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        class="relative w-full max-w-md"
    >
        <div class="glass-strong p-8">
            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-500/10 border border-red-500/20">
                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            </div>
            <h3 class="text-lg font-light text-white text-center mb-2">{{ $title }}</h3>
            <p class="text-sm font-light text-gray-400 text-center mb-6">{{ $message }}</p>
            <div class="flex justify-center gap-3">
                <button x-on:click="show = false" class="btn-luxury-outline px-6 py-2.5 text-xs">{{ $cancelText }}</button>
                <button x-on:click="execute()" class="px-6 py-2.5 text-xs tracking-widest uppercase font-light text-white bg-red-500/20 border border-red-500/30 hover:bg-red-500/30 transition-all duration-500">{{ $confirmText }}</button>
            </div>
        </div>
    </div>
</div>
