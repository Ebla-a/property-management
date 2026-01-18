@extends('dashboard.layout')

@section('content')

<div class="lg:ml-50 px-6 py-8" style="direction:ltr">

    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">
            {{ __('messages.booking.reschedule') }}
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            {{ __('messages.booking.reschedule_subtitle') }}
        </p>
    </div>

    {{-- Wrapper Card --}}
    <div class="bg-white border rounded-2xl shadow-sm">

        {{-- Top Section --}}
        <div class="border-b px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-900">
                {{ __('messages.booking.booking_id') }} #{{ $booking->id }}
            </h3>

            <p class="text-sm text-gray-500 mt-1">
                {{ __('messages.booking.review_schedule_hint') }}
            </p>
        </div>

        {{-- Content --}}
        <div class="p-6 space-y-6">

            {{-- Current Schedule --}}
            <div class="bg-gray-50 border rounded-xl px-4 py-3">
                <p class="text-sm text-gray-600">
                    {{ __('messages.booking.current_schedule') }}
                </p>

                <p class="font-medium text-gray-900 mt-1">
                    {{ $booking->scheduled_at }}
                </p>
            </div>

            {{-- Form --}}
            <form action="{{ route('employee.bookings.reschedule', $booking->id) }}"
                  method="POST"
                  class="space-y-5">
                @csrf
                @method('PATCH')

                {{-- New Schedule --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('messages.booking.new_date_time') }}
                    </label>

                    <input type="datetime-local"
                           name="scheduled_at"
                           value="{{ old('scheduled_at') }}"
                           class="w-full border rounded-xl px-3.5 py-2.5 text-sm
                                  focus:ring-2 focus:ring-gray-900/20 focus:border-gray-800">

                    @error('scheduled_at')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2">

                <button
                    class="px-3 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-black">
                    {{ __('messages.user.save_changes') }}
                </button>

                <a href="{{ route('employee.bookings.show', $booking->id) }}"
                class="px-3 py-2 rounded-lg border text-sm hover:bg-gray-50">
                    {{ __('messages.booking.cancel') }}
                </a>

            </div>

            </form>
        </div>
    </div>
</div>

@endsection