@extends('dashboard.layout')

@section('content')

<div class="lg:ml-50 px-6 py-8 text-left" style="direction:ltr">

    {{-- ================= Filters Bar ================= --}}
    <div class="flex flex-wrap items-center gap-3 w-full justify-center mb-10">

        {{-- Status Filter --}}
        <form method="GET">
            <select name="status"
                    onchange="this.form.submit()"
                    class="border rounded-xl px-3 py-2 text-sm bg-white shadow-sm hover:border-gray-400 focus:ring-1 focus:ring-indigo-300 transition">

                <option value="">All Status</option>
                <option value="pending"      {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved"     {{ request('status')=='approved' ? 'selected' : '' }}>Approved</option>
                <option value="rescheduled"  {{ request('status')=='rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                <option value="completed"    {{ request('status')=='completed' ? 'selected' : '' }}>Completed</option>
                <option value="rejected"     {{ request('status')=='rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="canceled"     {{ request('status')=='canceled' ? 'selected' : '' }}>Canceled</option>

            </select>
        </form>

        {{-- Quick Tabs --}}
        <div class="flex flex-wrap gap-2 justify-center">

            <a href="{{ url('dashboard/bookings') }}"
               class="px-3 py-1.5 rounded-xl text-sm border shadow-sm
               {{ !request('status')
                    ? 'bg-indigo-50 text-indigo-700 border-indigo-300 font-semibold'
                    : 'text-gray-600 hover:bg-gray-50' }}">
                All
            </a>

            <a href="?status=pending"
               class="px-3 py-1.5 rounded-xl text-sm border shadow-sm
               {{ request('status')=='pending'
                    ? 'bg-yellow-50 text-yellow-700 border-yellow-300 font-semibold'
                    : 'text-gray-600 hover:bg-gray-50' }}">
                Pending
            </a>

            <a href="?status=approved"
               class="px-3 py-1.5 rounded-xl text-sm border shadow-sm
               {{ request('status')=='approved'
                    ? 'bg-green-50 text-green-700 border-green-300 font-semibold'
                    : 'text-gray-600 hover:bg-gray-50' }}">
                Approved
            </a>

            <a href="?status=rejected"
               class="px-3 py-1.5 rounded-xl text-sm border shadow-sm
               {{ request('status')=='rejected'
                    ? 'bg-red-50 text-red-700 border-red-300 font-semibold'
                    : 'text-gray-600 hover:bg-gray-50' }}">
                Rejected
            </a>

            <a href="?status=canceled"
               class="px-3 py-1.5 rounded-xl text-sm border shadow-sm
               {{ request('status')=='canceled'
                    ? 'bg-gray-100 text-gray-700 border-gray-300 font-semibold'
                    : 'text-gray-600 hover:bg-gray-50' }}">
                Canceled
            </a>

        </div>

    </div>

    {{-- ================= Cards Grid ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

        @forelse($bookings as $booking)

            <div class="bg-white border rounded-2xl shadow-sm p-5 hover:shadow-md hover:border-gray-300 transition">

                {{-- Card Header --}}
                <div class="flex items-start justify-between mb-4">

                    <div>
                        <h3 class="font-semibold text-gray-900 leading-tight">
                            {{ $booking->property->title }}
                        </h3>

                        <p class="text-sm text-gray-500 mt-0.5">
                            {{ $booking->property->city ?? '' }}
                        </p>
                    </div>
       


                    {{-- Status Badge --}}
                    <span class="px-3 py-1 rounded-lg text-xs border font-medium
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

                {{-- Customer --}}
                <div class="flex items-center gap-2">

                    <svg class="w-5 h-5 text-gray-600" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M12 2.25a4.5 4.5 0 014.5 4.5v.75a4.5 4.5 0 11-9 0V6.75a4.5 4.5 0 014.5-4.5zm-7.5 17.1a7.5 7.5 0 0115 0v.15A2.25 2.25 0 0117.25 21h-10.5A2.25 2.25 0 014.5 19.5v-.15z"
                              clip-rule="evenodd" />
                    </svg>

                    <div>
                        <p class="text-sm font-medium text-gray-800">
                            {{ $booking->user->name }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $booking->user->email }}
                        </p>
                    </div>

                </div>

                {{-- Admin Assigned Employee --}}
                @if(auth()->user()->hasRole('admin') && $booking->employee)
                    <div class="mt-2 text-sm text-indigo-700 font-semibold">
                        Assigned To: {{ $booking->employee->name }}
                    </div>
                @endif

                {{-- Schedule --}}
                <div class="mt-3 flex items-center gap-2">

                    <svg class="w-5 h-5 text-gray-600" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M6.75 2.25a.75.75 0 01.75.75V4.5h9V3a.75.75 0 011.5 0v1.5h.75A2.25 2.25 0 0121 6.75v12A2.25 2.25 0 0118.75 21H5.25A2.25 2.25 0 013 18.75v-12A2.25 2.25 0 015.25 4.5H6V3a.75.75 0 01.75-.75zM3.75 9h16.5v9.75a.75.75 0 01-.75.75H4.5a.75.75 0 01-.75-.75V9z"
                              clip-rule="evenodd" />
                    </svg>

                    <p class="text-sm text-gray-700">
                        <span class="font-medium">{{ $booking->scheduled_at }}</span>
                    </p>

                </div>

                {{-- ================= Actions ================= --}}
                 <div class="mt-6 flex flex-wrap gap-2">

                    {{-- Always available --}}
                    <a href="{{ route('employee.bookings.show', $booking->id) }}"
                       class="px-3 py-1.5 bg-blue-50 border border-blue-100 rounded-lg text-sm text-blue-700 hover:bg-blue-100 transition">
                        View Details
                    </a>

                    {{-- Admin OR Assigned Employee --}}
                    @if( $booking->employee_id == auth()->id())

                        {{-- PENDING --}}
                        @if($booking->status == 'pending')

                            <form method="POST" action="{{ route('employee.bookings.approve', $booking->id) }}">
                                @csrf @method('PATCH')
                                <button class="px-3 py-1.5 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700 hover:bg-green-100">
                                    Approve
                                </button>
                            </form>

                            <form method="POST" action="{{ route('employee.bookings.reject', $booking->id) }}">
                                @csrf @method('PATCH')
                                <button class="px-3 py-1.5 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700 hover:bg-red-100">
                                    Reject
                                </button>
                            </form>

                            <form method="POST" action="{{ route('employee.bookings.cancel', $booking->id) }}">
                                @csrf @method('PATCH')
                                <button class="px-3 py-1.5 bg-gray-100 border rounded-lg text-sm text-gray-700 hover:bg-gray-200">
                                    Cancel
                                </button>
                            </form>

                        {{-- APPROVED --}}
                        @elseif($booking->status == 'approved')

                            <a href="{{ route('employee.reschedule.form', $booking->id) }}"
                               class="px-3 py-1.5 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-700 hover:bg-blue-100">
                                Reschedule
                            </a>

                            <form method="POST" action="{{ route('employee.bookings.complete', $booking->id) }}">
                                @csrf @method('PATCH')
                                <button class="px-3 py-1.5 bg-emerald-50 border border-emerald-200 rounded-lg text-sm text-emerald-700 hover:bg-emerald-100">
                                    Complete
                                </button>
                            </form>

                            <form method="POST" action="{{ route('employee.bookings.cancel', $booking->id) }}">
                                @csrf @method('PATCH')
                                <button class="px-3 py-1.5 bg-gray-100 border rounded-lg text-sm text-gray-700 hover:bg-gray-200">
                                    Cancel
                                </button>
                            </form>

                        {{-- RESCHEDULED --}}
                        @elseif($booking->status == 'rescheduled')

                            <form method="POST" action="{{ route('employee.bookings.approve', $booking->id) }}">
                                @csrf @method('PATCH')
                                <button class="px-3 py-1.5 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700 hover:bg-green-100">
                                    Approve
                                </button>
                            </form>

                            <form method="POST" action="{{ route('employee.bookings.complete', $booking->id) }}">
                                @csrf @method('PATCH')
                                <button class="px-3 py-1.5 bg-emerald-50 border border-emerald-200 rounded-lg text-sm text-emerald-700 hover:bg-emerald-100">
                                    Complete
                                </button>
                            </form>

                            <form method="POST" action="{{ route('employee.bookings.cancel', $booking->id) }}">
                                @csrf @method('PATCH')
                                <button class="px-3 py-1.5 bg-gray-100 border rounded-lg text-sm text-gray-700 hover:bg-gray-200">
                                    Cancel
                                </button>
                            </form>

                        @endif

                    @endif

                </div>

            </div> 

        @empty
        @endforelse

    </div>

    {{-- ================= Pagination ================= --}}
    <div class="mt-10 flex justify-center">
        <div class="bg-white border rounded-xl px-5 py-3 shadow-sm">
            {{ $bookings->links() }}
        </div>
    </div>

</div>

@endsection
