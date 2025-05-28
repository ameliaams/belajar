@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Tambah Data Baru</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('data.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="isi" class="form-label">Isi</label>
                            <textarea class="form-control @error('isi') is-invalid @enderror" id="isi" name="isi" rows="5" required>{{ old('isi') }}</textarea>
                            @error('isi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="status" name="status" checked>
                            <label class="form-check-label" for="status">Aktif</label>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('data.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
