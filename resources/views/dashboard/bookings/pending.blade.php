@extends('dashboard.layout')

@section('content')

<div class="lg:ml-50 px-6 py-8 text-left" style="direction:ltr">

    {{-- Page Title --}}
    <h1 class="text-3xl font-bold mb-8 text-gray-900">
        Pending Bookings
    </h1>

    {{-- Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

        @forelse($bookings as $booking)

        <div class="bg-white border rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 p-6">

            {{-- Card Header --}}
            <div class="flex items-start justify-between mb-4">

                <div>
                    <h3 class="font-semibold text-gray-900 text-lg leading-tight">
                        {{ $booking->property->title }}
                    </h3>

                    <p class="text-sm text-gray-500 mt-0.5">
                        {{ $booking->property->city ?? '' }}
                    </p>
                </div>

                {{-- Status Badge --}}
                <span class="px-3 py-1 rounded-lg text-xs border bg-yellow-50 text-yellow-700 border-yellow-300 font-medium">
                    Pending
                </span>

            </div>

            <div class="border-t border-gray-200 my-4"></div>

            {{-- Customer --}}
            <div class="flex items-center gap-3">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5 text-gray-600"
                     viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd"
                          d="M12 2.25a4.5 4.5 0 014.5 4.5v.75a4.5 4.5 0 11-9 0V6.75a4.5 4.5 0 014.5-4.5zm-7.5 17.1a7.5 7.5 0 0115 0v.15A2.25 2.25 0 0117.25 21h-10.5A2.25 2.25 0 014.5 19.5v-.15z"
                          clip-rule="evenodd" />
                </svg>

                <div>
                    <p class="text-sm font-medium text-gray-900">
                        {{ $booking->user->name }}
                    </p>

                    <p class="text-xs text-gray-500">
                        {{ $booking->user->email }}
                    </p>
                </div>
            </div>

            {{-- Schedule --}}
            <div class="mt-3 flex items-center gap-2">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5 text-gray-600"
                     viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd"
                          d="M6.75 2.25a.75.75 0 01.75.75V4.5h9V3a.75.75 0 011.5 0v1.5h.75A2.25 2.25 0 0121 6.75v12A2.25 2.25 0 0118.75 21H5.25A2.25 2.25 0 013 18.75v-12A2.25 2.25 0 015.25 4.5H6V3a.75.75 0 01.75-.75zM3.75 9h16.5v9.75a.75.75 0 01-.75.75H4.5a.75.75 0 01-.75-.75V9z"
                          clip-rule="evenodd" />
                </svg>

                <p class="text-sm text-gray-700">
                    <span class="font-medium">
                        {{ $booking->scheduled_at }}
                    </span>
                </p>

            </div>

            {{-- Actions --}}
            <div class="mt-6 flex flex-wrap gap-2">

                {{-- Approve --}}
                <form method="POST"
                      action="{{ route('employee.bookings.approve', $booking->id) }}">
                    @csrf
                    @method('PATCH')
                    <button class="px-3 py-1.5 text-xs rounded-lg bg-green-600 text-white hover:bg-green-700">
                        Approve
                    </button>
                </form>

                {{-- Reject --}}
                <form method="POST"
                      action="{{ route('employee.bookings.reject', $booking->id) }}">
                    @csrf
                    @method('PATCH')
                    <button class="px-3 py-1.5 text-xs rounded-lg bg-red-600 text-white hover:bg-red-700">
                        Reject
                    </button>
                </form>

                {{-- View --}}
                <a href="{{ route('employee.bookings.show', $booking->id) }}"
                   class="px-3 py-1.5 text-xs rounded-lg border hover:bg-gray-50">
                    View Details
                </a>

            </div>

        </div>

        @empty
        <p class="text-gray-500 text-sm">
            No pending bookings available.
        </p>
        @endforelse

    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $bookings->links() }}
    </div>

</div>

@endsection
