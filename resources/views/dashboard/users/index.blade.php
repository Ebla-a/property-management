@extends('dashboard.layout')

@section('content')
<div class="p-6 bg-white rounded-lg shadow">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-gray-500">User Management</h1>

        <a href="{{ route('dashboard.users.create') }}"
           class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
            Add New User
        </a>
    </div>

    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-gray-100 text-gray-500 text-left">
                <th class="p-3">Name</th>
                <th class="p-3">Email</th>
                <th class="p-3">Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach($users as $user)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-3 text-gray-700">{{ $user->name }}</td>
                <td class="p-3 text-gray-700">{{ $user->email }}</td>

                <td class="p-3 flex gap-4">
                    <a href="{{ route('dashboard.users.edit', $user->id) }}"
                       class="text-indigo-600 hover:underline">
                        Edit
                    </a>

                    <a href="{{ route('dashboard.users.role', $user->id) }}"
                       class="text-gray-500 hover:underline">
                        Role
                    </a>

                    <form method="POST" action="{{ route('dashboard.users.destroy', $user->id) }}">
                        @csrf @method('DELETE')
                        <button class="text-red-600 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
