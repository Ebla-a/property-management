@extends('dashboard.layout')

@section('content')

<div class="lg:ml-50 px-6 py-8 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    <h1 class="text-2xl font-bold text-gray-900 mb-6">
        {{ __('messages.booking.pending_bookings') }}
    </h1>

    <div class="bg-white border rounded-2xl overflow-hidden shadow-sm">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('messages.booking.customer') }}
                    </th>
                    <th class="px-6 py-3 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('messages.booking.property') }}
                    </th>
                    <th class="px-6 py-3 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('messages.booking.scheduled_visit') }}
                    </th>
                    <th class="px-6 py-3 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('messages.user.actions') }}
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-sm">
                @forelse($bookings as $booking)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-700 font-medium">
                        {{ $booking->user->name ?? __('messages.reports.unknown') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                        {{ $booking->property->title ?? __('messages.reports.unknown') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                        {{ $booking->scheduled_at ?? __('messages.reports.no_data') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-4">
                      
                            <a href="{{ route('employee.bookings.show', $booking->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                {{ __('messages.booking.view_details') }}
                            </a>

                            @if($booking->status == 'pending')
                                <form method="POST" action="{{ route('employee.bookings.approve', $booking->id) }}" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-green-600 hover:text-green-700 font-medium">
                                        {{ __('messages.booking.approve') }}
                                    </button>
                                </form>

                                <button type="button" onclick="openRejectModal({{ $booking->id }})" class="text-red-600 hover:text-red-700 font-medium">
                                    {{ __('messages.booking.reject') }}
                                </button>

                                <form method="POST" action="{{ route('employee.bookings.cancel', $booking->id) }}" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-gray-500 hover:text-gray-700 font-medium">
                                        {{ __('messages.booking.cancel') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-10 text-center text-gray-500 font-medium">
                        {{ __('messages.booking.no_pending_bookings') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- مودال الرفض --}}
<div id="rejectModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">{{ __('messages.booking.reject_booking') }}</h3>
        
        <form id="rejectForm" method="POST">
            @csrf 
            @method('PATCH')
            
            <div class="mb-4 text-right">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('messages.booking.rejection_reason_label') }}
                </label>
                <textarea 
                    name="reason" 
                    required 
                    minlength="5" 
                    class="w-full border rounded-xl p-3 text-sm outline-none focus:ring-2 focus:ring-red-500" 
                    placeholder="{{ __('messages.booking.reason_placeholder') }}"></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    {{ __('messages.booking.cancel') }}
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                    {{ __('messages.booking.confirm_reject') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRejectModal(id) {
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');
        
        let url = "{{ route('employee.bookings.reject', ':id') }}";
        form.action = url.replace(':id', id);
        modal.classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }

  
    window.onclick = function(event) {
        const modal = document.getElementById('rejectModal');
        if (event.target == modal) {
            closeRejectModal();
        }
    }
</script>

@endsection