@extends('dashboard.layout')

@section('title', 'Trashed Images')

@section('content')
<div class="container">

    <h2>Trashed Images - Property #{{ $property->id }}</h2>

    <div style="margin:10px 0;">
        <a href="{{ route('admin.properties.images.index', $property->id) }}">← Back to Images</a>
    </div>

    @if(session('success'))
        <div style="padding:10px;background:#d1fae5;margin:10px 0;">
            {{ session('success') }}
        </div>
    @endif

    @if($trashedImages->isEmpty())
        <p>No trashed images.</p>
    @else
        <div style="display:flex;flex-wrap:wrap;gap:15px;">
            @foreach($trashedImages as $img)
                <div style="border:1px solid #ddd;padding:10px;width:220px;">
                    <div style="margin-bottom:8px;">
                        {{-- Note: image file still exists (soft delete), so it should display --}}
                        <img src="{{ asset('storage/' . $img->path) }}" style="width:200px;height:120px;object-fit:cover;">
                    </div>

                    <div>
                        <strong>ID:</strong> {{ $img->id }} <br>
                        <strong>Deleted at:</strong> {{ $img->deleted_at }}
                    </div>

                    <div style="margin-top:10px;display:flex;gap:8px;flex-wrap:wrap;">
                        {{-- Restore --}}
                        <form method="POST" action="{{ route('admin.properties.images.restore', [$property->id, $img->id]) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit">Restore</button>
                        </form>

                        {{-- Force delete --}}
                        <form method="POST" action="{{ route('admin.properties.images.forceDestroy', ['property' => $property->id, 'image' => $img->id]) }}"
                            onsubmit="return confirm('Force delete permanently?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Force</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
