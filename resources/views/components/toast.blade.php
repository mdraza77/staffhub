@if (session('success') || session('error') || session('status') || (isset($errors) && $errors->any()))
    @php
        $isError = session()->has('error') || (isset($errors) && $errors->any());
        if (isset($errors) && $errors->any()) {
            $message = implode("\n", $errors->all());
        } else {
            $message = session('success') ?? session('status') ?? session('error');
        }
    @endphp

    <div id="toast-container" class="fixed top-5 right-5 z-[9999] flex flex-col gap-3">
        <div id="session-toast"
            class="flex items-center gap-3 bg-white dark:bg-zinc-900 border border-gray-150 dark:border-zinc-800 shadow-xl rounded-xl p-4 min-w-[320px] max-w-sm transition-all duration-300 transform opacity-0 -translate-y-2">
            @if (!$isError)
                <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-green-50 dark:bg-green-950/30 flex items-center justify-center text-green-600 dark:text-green-400">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                </div>
                <div class="flex-grow">
                    <h4 class="text-sm font-semibold text-gray-800 dark:text-zinc-100">Success</h4>
                    <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5 whitespace-pre-line">{{ $message }}</p>
                </div>
            @else
                <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-red-50 dark:bg-red-950/30 flex items-center justify-center text-red-600 dark:text-red-400">
                    <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                </div>
                <div class="flex-grow">
                    <h4 class="text-sm font-semibold text-gray-800 dark:text-zinc-100">Error</h4>
                    <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5 whitespace-pre-line text-left">{{ $message }}</p>
                </div>
            @endif
            <button onclick="dismissToast()"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-zinc-200 transition-colors ml-2 focus:outline-none">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
    </div>

    <script>
        (function() {
            const toast = document.getElementById('session-toast');
            const container = document.getElementById('toast-container');
            
            // Slide down and fade in
            setTimeout(() => {
                if (toast) {
                    toast.classList.remove('opacity-0', '-translate-y-2');
                }
            }, 100);

            // Auto dismiss after 4 seconds
            const timer = setTimeout(() => {
                dismissToast();
            }, 4000);

            window.dismissToast = function() {
                clearTimeout(timer);
                if (toast) {
                    toast.classList.add('opacity-0', '-translate-y-2');
                    setTimeout(() => {
                        if (container) {
                            container.remove();
                        }
                    }, 300);
                }
            };
        })();
    </script>
@endif