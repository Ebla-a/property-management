@extends('dashboard.layout')

@section('content')

<div class="lg:ml-50 px-6 py-8 text-left" style="direction:ltr">


    <h1 class="text-2xl font-bold text-gray-900 mb-4">
        My Bookings
    </h1>

    {{-- ================= Quick Tabs ================= --}}
    <div class="flex flex-wrap items-center gap-3 mb-8">
        @php
            $current = request('status');
            $tabClasses = "px-3 py-1 rounded-full text-sm border transition";
        @endphp

        <a href="{{ route('employee.bookings.my') }}"
           class="{{ $tabClasses }}
           {{ $current=='' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
            All ({{ $counts['all'] ?? 0 }})
        </a>

        <a href="?status=pending"
           class="{{ $tabClasses }}
           {{ $current=='pending' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
            Pending ({{ $counts['pending'] ?? 0 }})
        </a>

        <a href="?status=approved"
           class="{{ $tabClasses }}
           {{ $current=='approved' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
            Approved ({{ $counts['approved'] ?? 0 }})
        </a>

        <a href="?status=rescheduled"
           class="{{ $tabClasses }}
           {{ $current=='rescheduled' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
            Rescheduled ({{ $counts['rescheduled'] ?? 0 }})
        </a>

        <a href="?status=completed"
           class="{{ $tabClasses }}
           {{ $current=='completed' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
            Completed ({{ $counts['completed'] ?? 0 }})
        </a>

        <a href="?status=rejected"
           class="{{ $tabClasses }}
           {{ $current=='rejected' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
            Rejected ({{ $counts['rejected'] ?? 0 }})
        </a>

        <a href="?status=canceled"
           class="{{ $tabClasses }}
           {{ $current=='canceled' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
            Canceled ({{ $counts['canceled'] ?? 0 }})
        </a>

    {{--  Filters select   --}}
        <form method="GET" class="ml-auto">
            <select name="status"
                    onchange="this.form.submit()"
                    class="border rounded-xl px-3 py-2 text-sm bg-white shadow-sm hover:border-gray-400 focus:ring-1 focus:ring-indigo-300 transition">
                <option value="">All Status</option>
                <option value="pending"     {{ request('status')=='pending' ? 'selected' : '' }}>Pending ({{ $counts['pending'] ?? 0 }})</option>
                <option value="approved"    {{ request('status')=='approved' ? 'selected' : '' }}>Approved ({{ $counts['approved'] ?? 0 }})</option>
                <option value="rescheduled" {{ request('status')=='rescheduled' ? 'selected' : '' }}>Rescheduled ({{ $counts['rescheduled'] ?? 0 }})</option>
                <option value="completed"   {{ request('status')=='completed' ? 'selected' : '' }}>Completed ({{ $counts['completed'] ?? 0 }})</option>
                <option value="rejected"    {{ request('status')=='rejected' ? 'selected' : '' }}>Rejected ({{ $counts['rejected'] ?? 0 }})</option>
                <option value="canceled"    {{ request('status')=='canceled' ? 'selected' : '' }}>Canceled ({{ $counts['canceled'] ?? 0 }})</option>
            </select>
        </form>

    </div>

    {{--  Cards Grid  --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

        @if($bookings && $bookings->count() > 0)
            @foreach($bookings as $booking)

            <div class="bg-white border rounded-2xl shadow-sm p-5 hover:shadow-lg transition">

                {{-- Header --}}
                <div class="flex items-start justify-between mb-3">

                    <div>
                        <h3 class="font-semibold text-gray-900 leading-tight">
                            {{ $booking->property->title ?? 'No Property' }}
                        </h3>

                        <p class="text-sm text-gray-500 mt-0.5">
                            {{ $booking->property->city ?? 'No City' }}
                        </p>
                    </div>

                    {{-- Status Badge --}}
                    <span class="px-3 py-1 rounded-lg text-xs border
                        @if($booking->status=='pending')
                            bg-yellow-50 text-yellow-700 border-yellow-300
                        @elseif($booking->status=='approved')
                            bg-green-50 text-green-700 border-green-300
                        @elseif($booking->status=='rescheduled')
                            bg-blue-50 text-blue-700 border-blue-300
                        @elseif($booking->status=='completed')
                            bg-emerald-50 text-emerald-700 border-emerald-300
                        @elseif($booking->status=='rejected')
                            bg-red-50 text-red-700 border-red-300
                        @elseif($booking->status=='canceled')
                            bg-gray-100 text-gray-700 border-gray-300
                        @endif">
                        {{ ucfirst($booking->status) }}
                    </span>

                </div>

                <div class="border-t border-gray-200 my-3"></div>

                {{-- User --}}
                <div class="flex items-center gap-3">
                    <p class="text-sm font-medium text-gray-800">
                        {{ $booking->user->name ?? 'No User' }}
                    </p>
                    <p class="text-xs text-gray-500">
                        {{ $booking->user->email ?? 'No Email' }}
                    </p>
                </div>

                {{-- Schedule --}}
                <div class="mt-3 text-sm text-gray-700">
                    <span class="font-medium">
                        {{ $booking->scheduled_at ?? 'No Date' }}
                    </span>
                </div>

                {{--  Actions --}}
                <div class="mt-6 flex flex-wrap items-center gap-2">

                    @php
                        $isOwner = $booking->employee_id === auth()->id();
                        $status  = $booking->status;
                    @endphp

                    {{-- Always View --}}
                    <a href="{{ route('employee.bookings.show', $booking->id) }}"
                       class="px-2 py-1 rounded-full text-sm
                              bg-blue-50 border border-blue-200 text-blue-700 hover:bg-blue-100 transition">
                        View
                    </a>

                    @if($isOwner)

                        @if($status === 'pending')

                            <form method="POST" action="{{ route('employee.bookings.approve', $booking->id) }}">
                                @csrf @method('PATCH')
                                <button class="px-3 py-1 rounded-full text-sm
                                               bg-green-50 border border-green-200 text-green-700 hover:bg-green-100">
                                    Approve
                                </button>
                            </form>

                            <form method="POST" action="{{ route('employee.bookings.reject', $booking->id) }}">
                                @csrf @method('PATCH')
      <button type="button" 
        onclick="openRejectModal({{ $booking->id }})"
        class="px-3 py-1 rounded-full text-sm bg-red-50 border border-red-200 text-red-700 hover:bg-red-100">
    Reject
</button>
                                
                            </form>

                            <form method="POST" action="{{ route('employee.bookings.cancel', $booking->id) }}">
                                @csrf @method('PATCH')
                                <button class="px-3 py-1 rounded-full text-sm
                                               bg-gray-100 border text-gray-700 hover:bg-gray-200">
                                    Cancel
                                </button>
                            </form>

                        @elseif($status === 'approved')

                            <a href="{{ route('employee.reschedule.form', $booking->id) }}"
                               class="px-3 py-1 rounded-full text-sm
                                      bg-blue-50 border border-blue-200 text-blue-700 hover:bg-blue-100">
                                Reschedule
                            </a>

                            <form method="POST" action="{{ route('employee.bookings.complete', $booking->id) }}">
                                @csrf @method('PATCH')
                                <button class="px-3 py-1 rounded-full text-sm
                                               bg-emerald-50 border border-emerald-200 text-emerald-700 hover:bg-emerald-100">
                                    Complete
                                </button>
                            </form>

                            <form method="POST" action="{{ route('employee.bookings.cancel', $booking->id) }}">
                                @csrf @method('PATCH')
                                <button class="px-3 py-1 rounded-full text-sm
                                               bg-gray-100 border text-gray-700 hover:bg-gray-200">
                                    Cancel
                                </button>
                            </form>

                        @elseif($status === 'rescheduled')

                            <form method="POST" action="{{ route('employee.bookings.approve', $booking->id) }}">
                                @csrf @method('PATCH')
                                <button class="px-3 py-1 rounded-full text-sm
                                               bg-green-50 border border-green-200 text-green-700 hover:bg-green-100">
                                    Approve
                                </button>
                            </form>

                            <form method="POST" action="{{ route('employee.bookings.complete', $booking->id) }}">
                                @csrf @method('PATCH')
                                <button class="px-3 py-1 rounded-full text-sm
                                               bg-emerald-50 border border-emerald-200 text-emerald-700 hover:bg-emerald-100">
                                    Complete
                                </button>
                            </form>

                            <form method="POST" action="{{ route('employee.bookings.cancel', $booking->id) }}">
                                @csrf @method('PATCH')
                                <button class="px-3 py-1 rounded-full text-sm
                                               bg-gray-100 border text-gray-700 hover:bg-gray-200">
                                    Cancel
                                </button>
                            </form>

                        @endif

                    @endif

                </div>

            </div>

            @endforeach
        @else
            <div class="col-span-3">
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 text-gray-300 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-full h-full">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Bookings Found</h3>
                    <p class="text-gray-500 mb-6">
                        @if(request('status'))
                            No {{ request('status') }} bookings found.
                        @else
                            You don't have any bookings assigned yet.
                        @endif
                    </p>
                   
                </div>
            </div>
        @endif

    </div>

    {{-- Pagination --}}
    @if($bookings && $bookings->total() > 0)
        <div class="mt-6">
            {{ $bookings->links() }}
        </div>
    @endif

</div>
<div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        {{-- خلفية معتمة --}}
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeRejectModal()"></div>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="rejectForm" method="POST" action="">
                @csrf
                @method('PATCH')
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Reject Booking</h3>
                    <div class="mt-4">
                        <label for="reason" class="block text-sm font-medium text-gray-700">Reason for rejection</label>
                        <textarea name="reason" id="reason" rows="3" required
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                  placeholder="Please explain why you are rejecting this booking..."></textarea>
                        @error('reason')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Confirm Rejection
                    </button>
                    <button type="button" onclick="closeRejectModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="rejectModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Reject Booking</h3>
        
        <form id="rejectForm" method="POST">
            @csrf
            @method('PATCH')
            
            <div class="mb-4 text-left">
                <label class="block text-sm font-medium text-gray-700 mb-2">Why are you rejecting this?</label>
                <textarea name="reason" required minlength="5"
                          class="w-full border rounded-xl p-3 text-sm focus:ring-2 focus:ring-red-500 outline-none" 
                          placeholder="Minimum 5 characters..."></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeRejectModal()" 
                        class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Confirm Reject
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
    url = url.replace(':id', id);
    
    form.action = url;
    modal.classList.remove('hidden');
}

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }
</script>

@endsection