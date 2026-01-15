@extends('dashboard.layout')

@section('content')

<div class="lg:ml-50 px-6 py-8" style="direction:ltr">

    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">
            Booking Details
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            Full booking information & management actions
        </p>
    </div>


    {{-- Card --}}
    <div class="p-6 bg-white border rounded-xl shadow-sm">

        {{-- Top Row : Booking ID + Status --}}
        <div class="flex items-center justify-between mb-6">

            <h3 class="text-lg font-semibold">
                Booking #{{ $booking->id }}
            </h3>

            <span class="px-3 py-1 rounded-lg text-xs border
                @if($booking->status == 'pending')
                    bg-yellow-50 text-yellow-700 border-yellow-300
                @elseif($booking->status == 'approved')
                    bg-green-50 text-green-700 border-green-300
                @elseif($booking->status == 'rejected')
                    bg-red-50 text-red-700 border-red-300
                @elseif($booking->status == 'cancelled')
                    bg-gray-100 text-gray-700 border-gray-300
                @elseif($booking->status == 'completed')
                    bg-blue-50 text-blue-700 border-blue-300
                @endif">
                {{ ucfirst($booking->status) }}
            </span>

        </div>


        {{-- Grid Layout --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- Customer --}}
            <div class="p-4 border rounded-xl">
                <h4 class="font-medium text-gray-800 mb-2">Customer</h4>

                <div class="flex items-start gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-6 h-6 text-black"
                        viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M12 2.25a4.5 4.5 0 014.5 4.5v.75a4.5 4.5 0 11-9 0V6.75a4.5 4.5 0 014.5-4.5zm-7.5 17.1a7.5 7.5 0 0115 0v.15A2.25 2.25 0 0117.25 21h-10.5A2.25 2.25 0 014.5 19.5v-.15z"
                            clip-rule="evenodd" />
                    </svg>

                    <div>
                        <p class="text-sm font-medium text-gray-700">
                            {{ $booking->customer->name ?? 'N/A' }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $booking->customer->email ?? '' }}
                        </p>
                    </div>
                </div>
            </div>


            {{-- Property --}}
            <div class="p-4 border rounded-xl">
                <h4 class="font-medium text-gray-800 mb-2">Property</h4>

                <p class="text-sm font-semibold">
                    {{ $booking->property->title ?? 'Unknown Property' }}
                </p>

                <p class="text-xs text-gray-500 mt-1">
                    {{ $booking->property->city ?? '' }} —
                    {{ $booking->property->address ?? '' }}
                </p>
            </div>


            {{-- Schedule --}}
            <div class="p-4 border rounded-xl">
                <h4 class="font-medium text-gray-800 mb-2">Scheduled Visit</h4>

                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-6 h-6 text-black"
                        viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M6.75 2.25a.75.75 0 01.75.75V4.5h9V3a.75.75 0 011.5 0v1.5h.75A2.25 2.25 0 0121 6.75v12A2.25 2.25 0 0118.75 21H5.25A2.25 2.25 0 013 18.75v-12A2.25 2.25 0 015.25 4.5H6V3a.75.75 0 01.75-.75zM3.75 9h16.5v9.75a.75.75 0 01-.75.75H4.5a.75.75 0 01-.75-.75V9z"
                            clip-rule="evenodd" />
                    </svg>

                    <p class="text-sm text-gray-700">
                        {{ $booking->scheduled_at }}
                    </p>
                </div>
            </div>


            {{-- Employee --}}
            @if($booking->employee)
            <div class="p-4 border rounded-xl">
                <h4 class="font-medium text-gray-800 mb-2">Assigned Employee</h4>

                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-6 h-6 text-black"
                        viewBox="0 0 24 24" fill="currentColor">
                        <path d="M9 2.25h6A2.25 2.25 0 0117.25 4.5V6H6.75V4.5A2.25 2.25 0 019 2.25z" />
                        <path fill-rule="evenodd"
                            d="M3.75 7.5A2.25 2.25 0 016 5.25h12A2.25 2.25 0 0120.25 7.5v9A2.25 2.25 0 0118 18.75H6A2.25 2.25 0 013.75 16.5v-9z"
                            clip-rule="evenodd" />
                    </svg>

                    <p class="text-sm text-gray-500">
                        {{ $booking->employee->name }}
                    </p>
                </div>
            </div>
            @endif

        </div>

        {{-- Rejection Reason --}}
        @if($booking->status == 'rejected')
        <div class="mt-4 p-4 border border-red-200 bg-red-50 rounded-xl">
            <h4 class="font-medium text-red-800 mb-1">Rejection Reason</h4>
            <p class="text-sm text-red-700">
                {{ $booking->rejection_reason ?? 'No reason was recorded for this rejection.' }}
            </p>
        </div>
        @endif

        {{-- Notes --}}
        @if($booking->notes)
        <div class="mt-4 p-4 border rounded-xl">
            <h4 class="font-medium text-gray-800 mb-1">Notes</h4>
            <p class="text-sm text-gray-600">
                {{ $booking->notes }}
            </p>
        </div>
        @endif

        {{-- Review Section --}}
        <div class="mt-4 p-4 border rounded-xl">
            <h4 class="font-medium text-gray-800 mb-2">Customer Review</h4>
            @if ($booking->status !== 'completed')
            <span class="text-sm text-gray-400">
                Review will be available after completion
            </span>
            @elseif (! $booking->review)
            <span class="text-sm text-gray-500 font-medium">
                Awaiting customer review
            </span>
            @else
            <div class="flex flex-col gap-1">
                <div class="flex items-center">
                    @for ($i = 1; $i <= 5; $i++)
                        <span class="text-lg">{{ $i <= $booking->review->rating ? '⭐' : '☆' }}</span>
                        @endfor
                </div>
                <span class="text-sm text-gray-700 italic">
                    "{{ $booking->review->comment }}"
                </span>
                <span class="text-xs text-gray-500">
                    by {{ $booking->review->user->name }}
                </span>
            </div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="mt-6 flex flex-wrap items-center gap-2">
            <a href="{{ route('employee.bookings.my') }}"
                class="px-3 py-1.5 text-xs rounded-lg border hover:bg-gray-100 transition">
                Back to list
            </a>

            @php
            $isOwner = $booking->employee_id === auth()->id();
            $status = $booking->status;
            @endphp

            @if($isOwner)
            @if($status === 'pending')
            <form method="POST" action="{{ route('employee.bookings.approve', $booking->id) }}">
                @csrf @method('PATCH')
                <button class="px-3 py-1 rounded-full text-sm bg-green-50 border border-green-200 text-green-700 hover:bg-green-100">
                    Approve
                </button>
            </form>

            <button type="button" onclick="openRejectModal({{ $booking->id }})"
                class="px-3 py-1 rounded-full text-sm bg-red-50 border border-red-200 text-red-700 hover:bg-red-100">
                Reject
            </button>

            <form method="POST" action="{{ route('employee.bookings.cancel', $booking->id) }}">
                @csrf @method('PATCH')
                <button class="px-3 py-1 rounded-full text-sm bg-gray-100 border text-gray-700 hover:bg-gray-200">
                    Cancel
                </button>
            </form>
            @elseif($status === 'approved')
            <a href="{{ route('employee.reschedule.form', $booking->id) }}"
                class="px-3 py-1 rounded-full text-sm bg-blue-50 border border-blue-200 text-blue-700 hover:bg-blue-100">
                Reschedule
            </a>
            <form method="POST" action="{{ route('employee.bookings.complete', $booking->id) }}">
                @csrf @method('PATCH')
                <button class="px-3 py-1 rounded-full text-sm bg-emerald-50 border border-emerald-200 text-emerald-700 hover:bg-emerald-100">
                    Complete
                </button>
            </form>
            @endif
            @endif
        </div>
    </div>
</div>



@endsection