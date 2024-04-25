@extends('layouts.main', ['title' => 'Kategori'])

@section('title-content')
    <i class="fas fa-list mr-2"></i> Kategori
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-4 col-lg-6">
            <form method="POST" action="{{ route('kategori.store') }}" class="card card-outline">
                <div class="card-header">
                    <h3 class="card-title">Buat Kategori Baru</h3>
                </div>

                <div class="card-body">
                    @csrf

                    <!-- Validasi error -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="nama_kategori">Nama Kategori</label>
                        <input id="nama_kategori" name="nama_kategori" type="text" class="form-control">
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan Kategori</button>
                    <a href="{{ route('kategori.index') }}" class="btn btn-secondary ml-2">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
