@if (session('success') || session('error'))
    @php
        $isSuccess = session()->has('success');

        $message = $isSuccess ? session('success') : session('error');

        $bgColor = $isSuccess ? 'bg-green-500' : 'bg-red-500';

        $icon = $isSuccess ? 'check_circle' : 'error';
    @endphp

    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 3000)"
        x-show="show"
        x-transition
        class="fixed top-4 right-4 z-50">
        <div
            class="{{ $bgColor }} text-white px-5 py-4 rounded-xl shadow-xl">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined">
                    {{ $icon }}
                </span>

                <span>{{ $message }}</span>

                <button
                    @click="show = false"
                    class="ml-2">
                    ✕
                </button>
            </div>
        </div>
    </div>
@endif
