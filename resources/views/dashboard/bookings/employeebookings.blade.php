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

        <a href="{{ route('employee.bookings.index') }}"
           class="{{ $tabClasses }}
           {{ $current=='' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
            All
        </a>

        <a href="?status=pending"
           class="{{ $tabClasses }}
           {{ $current=='pending' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
            Pending
        </a>

        <a href="?status=approved"
           class="{{ $tabClasses }}
           {{ $current=='approved' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
            Approved
        </a>

        <a href="?status=rescheduled"
           class="{{ $tabClasses }}
           {{ $current=='rescheduled' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
            Rescheduled
        </a>

        <a href="?status=completed"
           class="{{ $tabClasses }}
           {{ $current=='completed' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
            Completed
        </a>

        <a href="?status=rejected"
           class="{{ $tabClasses }}
           {{ $current=='rejected' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
            Rejected
        </a>

        <a href="?status=canceled"
           class="{{ $tabClasses }}
           {{ $current=='canceled' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
            Canceled
        </a>


    {{--  Filters select   --}}

        <form method="GET">
            <select name="status"
                    onchange="this.form.submit()"
                    class="border rounded-xl px-3 py-2 text-sm bg-white shadow-sm hover:border-gray-400 focus:ring-1 focus:ring-indigo-300 transition">
                <option value="">All Status</option>
                <option value="pending"     {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved"    {{ request('status')=='approved' ? 'selected' : '' }}>Approved</option>
                <option value="rescheduled" {{ request('status')=='rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                <option value="completed"   {{ request('status')=='completed' ? 'selected' : '' }}>Completed</option>
                <option value="rejected"    {{ request('status')=='rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="canceled"    {{ request('status')=='canceled' ? 'selected' : '' }}>Canceled</option>
            </select>
        </form>

    </div>

    {{--  Cards Grid  --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

        @forelse($bookings as $booking)

        <div class="bg-white border rounded-2xl shadow-sm p-5 hover:shadow-lg transition">

            {{-- Header --}}
            <div class="flex items-start justify-between mb-3">

                <div>
                    <h3 class="font-semibold text-gray-900 leading-tight">
                        {{ $booking->property->title }}
                    </h3>

                    <p class="text-sm text-gray-500 mt-0.5">
                        {{ $booking->property->city ?? '' }}
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
                    {{ $booking->user->name }}
                </p>
                <p class="text-xs text-gray-500">
                    {{ $booking->user->email }}
                </p>
            </div>

            {{-- Schedule --}}
            <div class="mt-3 text-sm text-gray-700">
                <span class="font-medium">
                    {{ $booking->scheduled_at }}
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
                            <button class="px-3 py-1 rounded-full text-sm
                                           bg-red-50 border border-red-200 text-red-700 hover:bg-red-100">
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

        @empty
            <p class="text-gray-500 text-sm">
                No bookings assigned to you.
            </p>
        @endforelse

    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $bookings->links() }}
    </div>

</div>

@endsection
