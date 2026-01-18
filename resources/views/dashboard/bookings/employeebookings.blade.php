@extends('dashboard.layout')

@section('content')

@php
    $isRtl = app()->getLocale() == 'ar';
@endphp

<div class="lg:ml-50 px-6 py-8 {{ $isRtl ? 'text-right' : 'text-left' }}" style="direction: {{ $isRtl ? 'rtl' : 'ltr' }}">

    <div class="flex flex-wrap items-center gap-3 w-full justify-center mb-10">
        <div class="flex flex-wrap gap-2 justify-center">
            <a href="{{ route('employee.bookings.my') }}"
               class="px-3 py-1.5 rounded-xl text-sm border shadow-sm {{ !request('status') ? 'bg-indigo-50 text-indigo-700 border-indigo-300 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                 {{ __('messages.booking.all') }} ({{ $counts['all'] }})
            </a>
            @foreach(['pending', 'approved', 'rescheduled', 'completed', 'rejected', 'canceled'] as $st)
                <a href="?status={{ $st }}"
                   class="px-3 py-1.5 rounded-xl text-sm border shadow-sm {{ request('status')==$st ? 'bg-indigo-50 text-indigo-700 border-indigo-300 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                    {{ __("messages.status.{$st}") }} ({{ $counts[$st] }})
                </a>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($bookings as $booking)
            <div class="bg-white border rounded-2xl shadow-sm p-5 hover:shadow-md transition">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="font-semibold text-gray-900 leading-tight">{{ $booking->property->title }}</h3>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $booking->property->city ?? '' }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-lg text-xs border font-medium
                        @if($booking->status=='pending') bg-yellow-50 text-yellow-700 border-yellow-300
                        @elseif($booking->status=='approved') bg-green-50 text-green-700 border-green-300
                        @elseif($booking->status=='rescheduled') bg-blue-50 text-blue-700 border-blue-300
                        @elseif($booking->status=='completed') bg-emerald-50 text-emerald-700 border-emerald-300
                        @else bg-gray-100 text-gray-700 border-gray-300 @endif">
                        {{ __("messages.status.{$booking->status}") }}
                    </span>
                </div>

                <div class="border-t border-gray-200 my-3"></div>

                <div class="flex items-center gap-2">
                    <div class="text-sm">
                        <p class="font-medium text-gray-800">{{ $booking->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $booking->user->email }}</p>
                    </div>
                </div>

                <div class="mt-3 flex items-center gap-2 text-sm text-gray-700">
                    <span class="font-medium">{{ $booking->scheduled_at }}</span>
                </div>

                <div class="mt-6 flex flex-wrap gap-2">
                    <a href="{{ route('employee.bookings.show', $booking->id) }}"
                       class="px-3 py-1.5 bg-blue-50 border border-blue-100 rounded-lg text-sm text-blue-700 hover:bg-blue-100 transition">
                        {{ __('messages.booking.view_details') }}
                    </a>
                    
                    @if($booking->status == 'pending' || $booking->status == 'rescheduled')
                        <form method="POST" action="{{ route('employee.bookings.approve', $booking->id) }}">
                            @csrf 
                            @method('PATCH')
                            <button type="submit" class="px-3 py-1.5 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700 hover:bg-green-100">
                                {{ __('messages.booking.approve') }}
                            </button>
                        </form>
                        
                   
                        <button type="button" onclick="openRejectModal({{ $booking->id }})" class="px-3 py-1.5 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700 hover:bg-red-100">
                            {{ __('messages.booking.reject') }}
                        </button>
                    @endif

                    @if($booking->status == 'approved')
                        <a href="{{ route('employee.reschedule.form', $booking->id) }}" class="px-3 py-1.5 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-700 hover:bg-blue-100">
                            {{ __('messages.booking.reschedule') }}
                        </a>
                        <form method="POST" action="{{ route('employee.bookings.complete', $booking->id) }}">
                            @csrf @method('PATCH')
                            <button class="px-3 py-1.5 bg-emerald-50 border border-emerald-200 rounded-lg text-sm text-emerald-700 hover:bg-emerald-100">
                                {{ __('messages.booking.complete') }}
                            </button>
                        </form>
                    @endif

                    @if(in_array($booking->status, ['pending', 'approved', 'rescheduled']))
                        <form method="POST" action="{{ route('employee.bookings.cancel', $booking->id) }}">
                            @csrf @method('PATCH')
                            <button class="px-3 py-1.5 bg-gray-100 border rounded-lg text-sm text-gray-700 hover:bg-gray-200">
                                {{ __('messages.booking.cancel') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 text-gray-500">
                {{ __('messages.booking.no_bookings_found') }}
            </div>
        @endforelse
    </div>

    <div class="mt-10 flex justify-center">
        {{ $bookings->links() }}
    </div>
</div>

{{-- Reject Modal Script --}}
<div id="rejectModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
        <h3 class="text-xl font-bold mb-4">{{ __('messages.booking.reject_booking') }}</h3>
        <form id="rejectForm" method="POST">
            @csrf @method('PATCH')
            <textarea name="reason" required class="w-full border rounded-xl p-3 text-sm mb-4" placeholder="{{ __('messages.booking.reason_placeholder') }}"></textarea>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-gray-100 rounded-lg">{{ __('messages.booking.cancel') }}</button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg">{{ __('messages.booking.confirm_reject') }}</button>
            </div>
        </form>
    </div>
</div>

<script>
function openRejectModal(id) {
    const form = document.getElementById('rejectForm');
    form.action = "{{ route('employee.bookings.reject', ':id') }}".replace(':id', id);
    document.getElementById('rejectModal').classList.remove('hidden');
}
function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>
@endsection