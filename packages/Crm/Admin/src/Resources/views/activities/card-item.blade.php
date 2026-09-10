@props(['activity'])

@php
    $type = strtolower($activity->type ?? 'call');
    $isDone = (int) ($activity->is_done ?? 0);
    $title = $activity->title ?: 'Scheduled Client Appointment';
    $comment = $activity->comment ?: 'Follow-up regarding requirements and next steps.';
    $location = $activity->location;
    $userName = $activity->user_name ?: ($activity->user ? $activity->user->name : 'Team Member');
    $leadTitle = $activity->lead_title;
    $leadId = $activity->lead_id;

    // Time calculations
    $timeRange = '10:00 AM – 11:00 AM';
    if (!empty($activity->schedule_from)) {
        $from = strtotime($activity->schedule_from);
        $to = !empty($activity->schedule_to) ? strtotime($activity->schedule_to) : ($from + 1800);
        $timeRange = date('h:i A', $from) . ' – ' . date('h:i A', $to);
    }

    // Type styling & Icons
    $iconBg = 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-950/50 dark:text-emerald-400 dark:border-emerald-900/50';
    $typeName = 'Call';
    $statusText = $isDone ? 'Completed' : 'Scheduled';
    $statusPill = $isDone 
        ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800' 
        : 'bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200 dark:border-blue-800';

    if ($type === 'meeting' || str_contains(strtolower($title), 'meeting') || str_contains(strtolower($title), 'demo')) {
        $iconBg = 'bg-purple-50 text-purple-600 border-purple-100 dark:bg-purple-950/50 dark:text-purple-400 dark:border-purple-900/50';
        $typeName = 'Meeting';
        if (!$isDone) {
            $statusText = 'Upcoming';
            $statusPill = 'bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200 dark:border-blue-800';
        }
    } elseif ($type === 'lunch') {
        $iconBg = 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-950/50 dark:text-amber-400 dark:border-amber-900/50';
        $typeName = 'Lunch';
        if (!$isDone) {
            $statusText = 'Follow Up';
            $statusPill = 'bg-purple-50 text-purple-700 dark:bg-purple-950/60 dark:text-purple-300 border border-purple-200 dark:border-purple-800';
        }
    } elseif ($type === 'task' || str_contains(strtolower($title), 'update') || str_contains(strtolower($title), 'task')) {
        $iconBg = 'bg-teal-50 text-teal-600 border-teal-100 dark:bg-teal-950/50 dark:text-teal-400 dark:border-teal-900/50';
        $typeName = 'Task';
        if (!$isDone) {
            $statusText = 'Pending';
            $statusPill = 'bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800';
        }
    } elseif (str_contains(strtolower($title), 'proposal') || str_contains(strtolower($title), 'quotation') || str_contains(strtolower($title), 'email') || $type === 'note') {
        $iconBg = 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-950/50 dark:text-amber-400 dark:border-amber-900/50';
        $typeName = 'Email';
        if (!$isDone) {
            $statusText = 'Pending';
            $statusPill = 'bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800';
        }
    }
@endphp

<article
    id="activity-card-{{ $activity->id }}"
    data-type="{{ $type }}"
    data-done="{{ $isDone }}"
    data-broker="{{ strtolower($userName) }}"
    data-date="{{ substr($activity->schedule_from ?? '', 0, 10) }}"
    class="activity-card activity-card-item bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/80 dark:border-gray-800 p-4 sm:p-5 shadow-2xs hover:shadow-sm hover:border-blue-300/80 dark:hover:border-gray-700 transition-all group flex items-center justify-between gap-4 cursor-pointer"
    onclick="if(!event.target.closest('button') && !event.target.closest('a')) window.location.href='{{ route('admin.activities.edit', $activity->id) }}'"
>
    <div class="flex items-center gap-4 flex-1 min-w-0">
        <!-- Colored Icon Container -->
        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl {{ $iconBg }} border flex items-center justify-center shrink-0 shadow-2xs">
            @if ($typeName === 'Call')
                <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                </svg>
            @elseif ($typeName === 'Meeting')
                <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                    <path d="M9 3v18"/>
                    <path d="M14 9h4"/>
                    <path d="M14 15h4"/>
                </svg>
            @elseif ($typeName === 'Email')
                <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                    <polyline points="10 9 9 9 8 9"/>
                </svg>
            @else
                <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            @endif
        </div>

        <!-- Main Content -->
        <div class="flex-1 min-w-0 space-y-1">
            <!-- Title & Status Pill -->
            <div class="flex items-center gap-2.5 flex-wrap">
                <h3 class="text-sm sm:text-[15px] font-bold text-gray-600dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors truncate max-w-md">
                    {{ $title }}
                </h3>

                <span id="status-pill-{{ $activity->id }}" class="text-[11px] font-bold px-2.5 py-0.5 rounded-full {{ $statusPill }}">
                    {{ $statusText }}
                </span>
            </div>

            <!-- Notes / Comment Brief -->
            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium truncate">
                {{ $comment }}
            </p>

            <!-- Meta Row -->
            <div class="flex items-center gap-4 text-xs text-slate-400 dark:text-slate-500 font-semibold pt-0.5 flex-wrap">
                <!-- Time -->
                <span class="flex items-center gap-1.5 text-slate-600 dark:text-slate-300">
                    <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                    <span>{{ $timeRange }}</span>
                </span>

                <!-- Assignee / User -->
                <span class="flex items-center gap-1.5 text-slate-600 dark:text-slate-300">
                    <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    <span>{{ $userName }}</span>
                </span>

                <!-- Type -->
                <span class="flex items-center gap-1.5 text-slate-600 dark:text-slate-300">
                    <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                        <line x1="7" y1="7" x2="7.01" y2="7"/>
                    </svg>
                    <span>{{ $typeName }}</span>
                </span>

                @if ($leadTitle)
                    <span class="flex items-center gap-1 text-purple-600 dark:text-purple-400 font-bold">
                        <span>🎯 {{ Str::limit($leadTitle, 20) }}</span>
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Chevron & Quick Complete -->
    <div class="flex items-center gap-2 shrink-0">
        <button
            type="button"
            onclick="toggleActivityStatus({{ $activity->id }}, {{ $isDone }}, this)"
            class="w-8 h-8 rounded-xl flex items-center justify-center transition-colors cursor-pointer {{ $isDone ? 'bg-emerald-500 text-white' : 'border border-slate-200 hover:border-blue-500 hover:bg-blue-50 text-slate-400 dark:border-gray-700' }}"
            title="{{ $isDone ? 'Mark as Incomplete' : 'Mark as Completed' }}"
        >
            @if ($isDone)
                <svg class="w-4 h-4 stroke-white stroke-[2.5]" fill="none" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            @else
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"></circle></svg>
            @endif
        </button>

        <a
            href="{{ route('admin.activities.edit', $activity->id) }}"
            class="w-7 h-7 flex items-center justify-center text-slate-300 group-hover:text-blue-600 dark:text-slate-600 dark:group-hover:text-blue-400 transition-colors"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <polyline points="9 18 15 12 9 6"/>
            </svg>
        </a>
    </div>
</article>