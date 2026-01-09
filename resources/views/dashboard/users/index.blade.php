{{-- resources/views/dashboard/users/index.blade.php --}}
@extends('dashboard.layout')

@section('content')
<!--
  Users Index Page
  Displays a paginated list of all users with management actions
-->
<div class="p-6" dir="ltr">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Users Management</h1>

        <a href="{{ route('dashboard.admin.employees.create') }}"
           class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
            + Add Employee
        </a>
    </div>

    {{-- Success & Error Messages --}}
    @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-800 border border-green-300">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-3 rounded bg-red-100 text-red-800 border border-red-300">
            {{ session('error') }}
        </div>
    @endif

    {{-- Users Table --}}
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Name</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Email</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Role</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Active</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @foreach($users as $user)
                    <tr>
                        <td class="px-4 py-3">{{ $user->name }}</td>
                        <td class="px-4 py-3">{{ $user->email }}</td>
                        <td class="px-4 py-3 capitalize">
                            {{ $user->getRoleNames()->first() ?? '—' }}
                        </td>
                        <td class="px-4 py-3">
                            @if($user->is_active)
                                <span class="text-green-600 font-medium">Yes</span>
                            @else
                                <span class="text-red-600 font-medium">No</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 space-x-2">
                            <a href="{{ route('dashboard.admin.users.edit-role', $user->id) }}"
                               class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded hover:bg-yellow-200">
                                Change Role
                            </a>

                            <a href="{{ route('dashboard.admin.users.edit-status', $user->id) }}"
                               class="px-3 py-1 bg-blue-100 text-blue-800 rounded hover:bg-blue-200">
                                Account Status
                            </a>

                            @if(auth()->id() === $user->id)
                                <!-- Prevent deleting yourself -->
                                <button disabled class="px-3 py-1 bg-gray-200 text-gray-500 rounded cursor-not-allowed" title="You cannot delete your own account">
                                    Delete
                                </button>
                            @else
                                <form action="{{ route('dashboard.admin.users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection
