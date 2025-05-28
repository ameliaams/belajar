@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-between mb-4">
        <div class="col-md-6">
            <h2>Daftar Data</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('data.create') }}" class="btn btn-primary">Tambah Data</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Isi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $index => $item)
                        <tr class="clickable-row" data-id="{{ $item->id }}" style="cursor: pointer;">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ Str::limit($item->isi, 50) }}</td>
                            <td>
                                @if(is_null($item->status))
                                    <span class="badge bg-success">AKTIF</span>
                                @else
                                    <span class="badge bg-danger">NON-AKTIF</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('data.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="dataModal" tabindex="-1" aria-labelledby="dataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dataModalLabel">Edit Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="isi" class="form-label">Isi</label>
                        <textarea class="form-control" id="isi" name="isi" rows="3"></textarea>
                    </div>
                    <div class="mb-3 form-check form-switch">
                        <input type="checkbox" class="form-check-input" id="status" name="status" value="1">
                        <label class="form-check-label" for="status">Status Aktif</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
                <hr>
                <h5 class="mt-4">Histori Perubahan</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Versi</th>
                                <th>Tanggal</th>
                                <th>Isi</th>
                            </tr>
                        </thead>
                        <tbody id="historiTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var modal = new bootstrap.Modal(document.getElementById('dataModal'));
    var form = $('#editForm');
    var currentId;

    $('.clickable-row').click(function() {
        currentId = $(this).data('id');

        // Ambil data via AJAX
        $.get('/data/' + currentId, function(data) {
            $('#dataModalLabel').text('Edit Data - Versi ' + data.versi);
            $('#isi').val(data.isi);
            $('#status').prop('checked', data.status === null);
            console.log(typeof data.status, data.status);

            // Isi tabel histori
            var historiHtml = '';
            data.histori.forEach(function(item) {
                historiHtml += '<tr>' +
                    '<td>' + item.versi + '</td>' +
                    '<td>' + item.tanggal + '</td>' +
                    '<td>' + item.isi + '</td>' +
                '</tr>';
            });
            $('#historiTableBody').html(historiHtml);

            modal.show();
        });
    });

    form.submit(function(e) {
    e.preventDefault();

    $.ajax({
        url: '/data/' + currentId,
        type: 'POST',
        data: {
            _method: 'PUT',
            _token: $('input[name="_token"]').val(),
            isi: $('#isi').val(),
            status: $('#status').is(':checked') ? 1 : 0
        },
        success: function(response) {
            if(response.success) {
                location.reload();
            } else if(response.info) {
                alert(response.info);
                modal.hide();
            }
        },
        error: function(xhr) {
            alert('Terjadi kesalahan: ' + xhr.responseJSON.message);
        }
    });
});
});
</script>
@endpush
