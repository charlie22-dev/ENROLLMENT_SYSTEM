@props(['approvals'])
<div class="flow-root">
    <ul class="-mb-8">
        @foreach($approvals as $approval)
        <li>
            <div class="relative pb-8">
                @if(!$loop->last)
                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></span>
                @endif
                <div class="relative flex space-x-3">
                    <div>
                        @php
                            $dotColor = match($approval->action) {
                                'approved'  => 'bg-green-500',
                                'returned'  => 'bg-amber-500',
                                'rejected'  => 'bg-red-500',
                                'cancelled' => 'bg-red-400',
                                default     => 'bg-blue-500',
                            };
                        @endphp
                        <span class="h-8 w-8 rounded-full {{ $dotColor }} flex items-center justify-center ring-8 ring-white shadow-sm">
                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($approval->action === 'approved')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                @elseif($approval->action === 'returned')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                @elseif($approval->action === 'rejected')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                @endif
                            </svg>
                        </span>
                    </div>
                    <div class="min-w-0 flex-1 pt-1.5">
                        <p class="text-sm text-gray-800">
                            <strong class="font-semibold text-gray-900">{{ $approval->actor?->name ?? 'System' }}</strong>
                            <span class="font-medium text-gray-600">({{ $approval->actionLabel() }})</span>
                            <span class="text-xs text-gray-500 uppercase tracking-wider block sm:inline">stage: {{ $approval->stage }}</span>
                        </p>
                        @if($approval->remarks)
                            <div class="mt-1 p-2 rounded bg-amber-50 border border-amber-200 text-xs text-amber-900">
                                <strong>Remarks:</strong> {{ $approval->remarks }}
                            </div>
                        @endif
                        <p class="text-xs text-gray-400 mt-1">{{ $approval->acted_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
            </div>
        </li>
        @endforeach
    </ul>
</div>
