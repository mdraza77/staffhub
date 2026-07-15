@extends('layouts.main')

@section('title', 'Break Room Lounge | StaffHub')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-mug-hot text-indigo-600"></i> Break Room Lounge
                </h1>
                <p class="text-sm text-gray-500">Take a refreshment, look around, and see who's in the lounge.</p>
            </div>
            <a href="{{ route('breaks.history') }}"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center gap-1.5 border border-gray-200">
                <i class="fa-solid fa-clock-rotate-left"></i> My Break Logs
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Side: User Control & Quick Stats -->
            <div class="space-y-6">
                <!-- User Break Console -->
                <div class="bg-white rounded-xl shadow border border-gray-100 p-6 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-50 rounded-full -mr-8 -mt-8 opacity-50"></div>

                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i
                            class="fa-solid fa-circle text-[8px] {{ $activeBreak ? 'text-green-500 animate-pulse' : 'text-gray-400' }}"></i>
                        Lounge Console
                    </h2>

                    @if ($activeBreak)
                        <!-- User IS on break -->
                        <div class="space-y-6">
                            <div class="p-4 bg-indigo-50 border border-indigo-100 rounded-xl flex items-start gap-4">
                                <div
                                    class="w-12 h-12 rounded-lg bg-indigo-600 text-white flex items-center justify-center text-xl shadow-sm">
                                    <i class="{{ $activeBreak->breakType->icon ?? 'fa-solid fa-mug-hot' }}"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-800 text-base">{{ $activeBreak->breakType->name }}</h3>
                                    <p class="text-xs text-indigo-600 font-semibold mt-0.5">Started at
                                        {{ $activeBreak->started_at->format('h:i A') }}</p>
                                    @if ($activeBreak->remark)
                                        <p class="text-xs text-gray-500 italic mt-2">"{{ $activeBreak->remark }}"</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Live Timer Panel -->
                            <div class="text-center py-6 px-4 bg-gray-50 border border-gray-100 rounded-xl">
                                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Time
                                    Remaining</span>
                                <div class="text-3xl font-black text-gray-850 tracking-tight font-mono" id="user-countdown"
                                    data-end-time="{{ $activeBreak->expected_end_time->toISOString() }}">
                                    --:--
                                </div>
                                <span class="text-[10px] text-gray-450 block mt-2">Expected return by
                                    {{ $activeBreak->expected_end_time->format('h:i A') }}</span>
                            </div>

                            <form action="{{ route('break-room.end') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-bold transition-all flex items-center justify-center gap-2 shadow hover:shadow-lg">
                                    <i class="fa-solid fa-person-walking-arrow-loop-left"></i> End Break & Return
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- User IS NOT on break -->
                        <form action="{{ route('break-room.start') }}" method="POST" class="space-y-4">
                            @csrf

                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase">Select Break
                                    Type</label>
                                <div class="grid grid-cols-1 gap-2.5">
                                    @forelse($breakTypes as $type)
                                        <label
                                            class="flex items-center justify-between p-3.5 border border-gray-200 rounded-xl cursor-pointer hover:bg-indigo-50/40 hover:border-indigo-200 transition-all select-none">
                                            <div class="flex items-center gap-3">
                                                <input type="radio" name="break_type_id" value="{{ $type->id }}"
                                                    class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                                    required>
                                                <div class="flex items-center gap-2">
                                                    <i class="{{ $type->icon }} text-indigo-600 text-base"></i>
                                                    <span
                                                        class="font-semibold text-gray-800 text-sm">{{ $type->name }}</span>
                                                </div>
                                            </div>
                                            <span
                                                class="text-xs font-semibold text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">{{ $type->duration_minutes }}m</span>
                                        </label>
                                    @empty
                                        <div
                                            class="p-4 border border-dashed border-gray-200 rounded-xl text-center text-xs text-gray-400">
                                            No active break categories configured by admin.
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            @if ($breakTypes->isNotEmpty())
                                <div>
                                    <label for="remark"
                                        class="block text-xs font-bold text-gray-700 mb-1.5 uppercase">Leave a note
                                        (optional)</label>
                                    <input type="text" name="remark" id="remark"
                                        placeholder="e.g. coffee run, step out for water..."
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <button type="submit"
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-lg font-bold transition-all flex items-center justify-center gap-2 shadow hover:shadow-lg">
                                    <i class="fa-solid fa-mug-saucer"></i> Go on Break
                                </button>
                            @endif
                        </form>
                    @endif
                </div>

                <!-- Recent Activities -->
                <div class="bg-white rounded-xl shadow border border-gray-100 p-6">
                    <h2 class="text-sm font-bold text-gray-900 mb-4 uppercase tracking-wider text-gray-400">Recently Back
                    </h2>
                    <div class="space-y-3.5">
                        @forelse($recentCompletedBreaks as $comp)
                            <div class="flex items-center justify-between gap-3 text-xs">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-7 h-7 rounded-full bg-gray-100 text-gray-650 flex items-center justify-center font-bold text-[9px] border border-gray-200">
                                        {{ strtoupper(substr($comp->user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-800">{{ $comp->user->name }}</span>
                                        <span class="text-gray-400 block text-[10px]">{{ $comp->breakType->name }}</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span
                                        class="text-gray-500 block text-[10px]">{{ $comp->ended_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 italic text-center py-4">No completions logged today.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Side: The Live Break Room (Other Employees) -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow border border-gray-100 p-6 min-h-[500px]">
                    <div class="flex justify-between items-center pb-4 border-b border-gray-100 mb-6">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Lounge Directory</h2>
                            <p class="text-xs text-gray-400">Real-time status updates of active break participants.</p>
                        </div>
                        <span
                            class="bg-indigo-50 text-indigo-750 font-bold px-2.5 py-1 rounded-full text-xs border border-indigo-150"
                            id="total-participants">
                            {{ $otherActiveBreaks->count() }} active
                        </span>
                    </div>

                    @if ($otherActiveBreaks->isEmpty())
                        <div class="flex flex-col items-center justify-center py-20 text-center text-gray-400">
                            <div
                                class="w-16 h-16 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center mb-3">
                                <i class="fa-solid fa-mug-hot text-2xl text-gray-300"></i>
                            </div>
                            <h3 class="font-bold text-gray-700 text-sm">The lounge is empty</h3>
                            <p class="text-xs text-gray-400 mt-1">Everyone is currently hard at work.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($otherActiveBreaks as $break)
                                <div
                                    class="p-4 border border-gray-150 rounded-xl bg-white hover:border-indigo-300 hover:shadow-md transition-all space-y-4">
                                    <!-- User & Break Header -->
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-full bg-indigo-50 border border-indigo-150 text-indigo-650 flex items-center justify-center font-black text-sm">
                                                {{ strtoupper(substr($break->user->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-800 text-sm">{{ $break->user->name }}</h4>
                                                <span
                                                    class="text-[10px] text-gray-400 block">{{ $break->user->department->name ?? 'Staff' }}</span>
                                            </div>
                                        </div>
                                        <span
                                            class="bg-indigo-50 border border-indigo-100 text-indigo-700 text-[10px] font-semibold px-2 py-0.5 rounded flex items-center gap-1">
                                            <i class="{{ $break->breakType->icon }} text-[9px]"></i>
                                            {{ $break->breakType->name }}
                                        </span>
                                    </div>

                                    @if ($break->remark)
                                        <p class="text-xs text-gray-500 bg-gray-50 px-2.5 py-1.5 rounded-lg italic">
                                            "{{ $break->remark }}"
                                        </p>
                                    @endif

                                    <!-- Countdown Progress -->
                                    <div class="pt-2 border-t border-gray-100">
                                        <div class="flex justify-between items-center text-xs mb-1">
                                            <span class="text-gray-400">Status</span>
                                            <span class="font-mono font-bold text-indigo-650 lounge-countdown"
                                                id="timer-{{ $break->id }}"
                                                data-end-time="{{ $break->expected_end_time->toISOString() }}">
                                                --:--
                                            </span>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                            <div class="bg-indigo-600 h-1.5 rounded-full lounge-progress"
                                                id="progress-{{ $break->id }}"
                                                data-start-time="{{ $break->started_at->toISOString() }}"
                                                data-end-time="{{ $break->expected_end_time->toISOString() }}"
                                                style="width: 0%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function updateTimers() {
            const now = new Date();

            // 1. Update user countdown timer
            const userCountdown = document.getElementById('user-countdown');
            if (userCountdown) {
                const endTime = new Date(userCountdown.getAttribute('data-end-time'));
                const diffMs = endTime - now;

                if (diffMs > 0) {
                    const totalSecs = Math.floor(diffMs / 1000);
                    const mins = Math.floor(totalSecs / 60);
                    const secs = totalSecs % 60;
                    userCountdown.innerText = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                    userCountdown.className = "text-3xl font-black text-indigo-650 tracking-tight font-mono";
                } else {
                    const absDiffMs = Math.abs(diffMs);
                    const totalSecs = Math.floor(absDiffMs / 1000);
                    const mins = Math.floor(totalSecs / 60);
                    const secs = totalSecs % 60;
                    userCountdown.innerText = `Exceeded by ${mins}m ${secs}s`;
                    userCountdown.className = "text-lg font-bold text-red-600 tracking-tight";
                }
            }

            // 2. Update other active timers & progress bars
            document.querySelectorAll('.lounge-countdown').forEach((timer) => {
                const endTime = new Date(timer.getAttribute('data-end-time'));
                const diffMs = endTime - now;
                const id = timer.id.replace('timer-', '');
                const progressBar = document.getElementById(`progress-${id}`);

                if (diffMs > 0) {
                    const totalSecs = Math.floor(diffMs / 1000);
                    const mins = Math.floor(totalSecs / 60);
                    const secs = totalSecs % 60;
                    timer.innerText = `${mins}m ${secs}s left`;
                    timer.className = "font-mono font-bold text-indigo-650 lounge-countdown";

                    if (progressBar) {
                        const startTime = new Date(progressBar.getAttribute('data-start-time'));
                        const totalDuration = endTime - startTime;
                        const elapsed = now - startTime;
                        const percent = Math.min(100, Math.max(0, (elapsed / totalDuration) * 100));
                        progressBar.style.width = `${percent}%`;
                        progressBar.className = "bg-indigo-600 h-1.5 rounded-full lounge-progress";
                    }
                } else {
                    const absDiffMs = Math.abs(diffMs);
                    const totalSecs = Math.floor(absDiffMs / 1000);
                    const mins = Math.floor(totalSecs / 60);
                    timer.innerText = `Overdue by ${mins}m`;
                    timer.className = "font-mono font-bold text-red-600 lounge-countdown animate-pulse";

                    if (progressBar) {
                        progressBar.style.width = `100%`;
                        progressBar.className = "bg-red-50 h-1.5 rounded-full lounge-progress";
                    }
                }
            });
        }

        // Initialize and tick
        updateTimers();
        setInterval(updateTimers, 1000);
    </script>
@endpush
