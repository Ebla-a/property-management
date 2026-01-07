@extends('dashboard.layout')

@section('title', 'Property Images')

@section('content')
<div class="container">

    <h2>Property Images #{{ $property->id }}</h2>

    @if(session('success'))
        <div style="padding:10px;background:#d1fae5;margin:10px 0;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="padding:10px;background:#fee2e2;margin:10px 0;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Upload Form --}}
    <form action="{{ route('admin.properties.images.store', $property->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div style="margin-bottom:10px;">
            <label>Images</label><br>
            <input type="file" name="images[]" multiple required>
        </div>

        <div style="margin-bottom:10px;">
            <label>Alt (optional)</label><br>
            <input type="text" name="alt" placeholder="alt text" style="width:300px;">
        </div>

        <button type="submit">Upload</button>
    </form>

    <hr style="margin:20px 0;">

    {{-- Link to trashed page --}}
    <div style="margin-bottom:15px;">
        <a href="{{ route('admin.properties.images.trashed', $property->id) }}">View Trashed Images</a>
    </div>

    {{-- Images list --}}
    <h3>Current Images</h3>

    <div style="display:flex;flex-wrap:wrap;gap:15px;">
        @foreach($images as $img)
            <div style="border:1px solid #ddd;padding:10px;width:220px;">
                <div style="margin-bottom:8px;">
                    <img src="{{ asset('storage/' . $img->path) }}" style="width:200px;height:120px;object-fit:cover;">
                </div>

                <div>
                    <strong>ID:</strong> {{ $img->id }} <br>
                    <strong>Main:</strong> {{ $img->is_main ? 'Yes' : 'No' }}
                </div>

                <div style="margin-top:10px;display:flex;gap:8px;flex-wrap:wrap;">
                    {{-- Set main --}}
                    <form method="POST" action="{{ route('admin.properties.images.setMain', [$property->id, $img->id]) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit">Set Main</button>
                    </form>

                    {{-- soft delete only --}}
                    <form method="POST" action="{{ route('admin.properties.images.destroy', [$property->id, $img->id]) }}"
                        onsubmit="return confirm('delete?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit">move to trash</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
