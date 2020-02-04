{{ $title }}
<div class="table-links">
    <a href="{{ route('app.single', $slug) }}" target="_blank">Lihat</a>
    <div class="bullet"></div>
    <a href="{{ route('admin.post.edit', $id) }}">Ubah</a>
    <div class="bullet"></div>
    <button type="button" class="btn text-danger p-0 delete-button" data-id="{{ $id }}">Hapus</button>
</div>