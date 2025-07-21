@extends('layouts.base')
@section('css')
<link rel="stylesheet" href="{{env('APP_URL')}}/assets/libs/select2/dist/css/select2.min.css">
@endsection
@section('content')
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Bongkar Bal : {{$bal->name.' ('.$bal->code.')'}}</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('bal.index') }}">Bal</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Bongkar Bal : {{$bal->name.' ('.$bal->code.')'}}</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="{{env('APP_URL')}}/assets/images/breadcrumb/ChatBc.png" alt="" class="img-fluid mb-n4" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 h-100 mb-3">
            @if($unpack->image)
            <img id="now-image" src="{{ '/storage/'.$unpack->image }}" alt="Product Image Preview" class="rounded-4 shadow w-100"
                style="">
            @else
            <div id="placeholder-image"
                class="d-flex p-5 text-center align-items-center justify-content-center rounded-5 border-2 border-dashed"
                style="aspect-ratio:1/1">
                <div>
                    <div class="fs-4">If Image Selected, it will show (Preview)</div>
                </div>
            </div>
            @endif
            <img src="" id="preview-image" alt="Unpack Proof Image Preview" class="d-none rounded-4 shadow w-100"
                style="">
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="px-4 py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h5 class="card-title fw-semibold mb-0">Bongkar Bal</h5>
                    <button type="button" class="btn bg-danger-subtle text-danger"
                        data-bs-toggle="modal" data-bs-target="#deleteModal-{{$bal->id}}"><i
                            class="fs-4 ti ti-trash"></i></button>
                </div>
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('bal.unpack.update', ['id' => $bal->id, 'unpack_id' => $bal->unpack->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Bukti Foto</label>
                            <div class="input-group">
                                <span class="input-group-text px-6" id="basic-addon1"><i
                                        class="ti ti-photo fs-6"></i></span>
                                <input type="file" name="image" class="form-control ps-2">
                            </div>
                            @error('image')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="user_id" class="form-label">PIC Pembongkaran</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text px-6" id="basic-addon1"><i
                                        class="ti ti-user-circle fs-6"></i></span>
                                <div style="flex-grow:1">
                                    <select name="user_id" id="user_id" class="select2-normal form-select">
                                        <option value="">-- Pilih Pembongkar --</option>
                                        @foreach ($users as $user)
                                            <option value="{{$user->id}}" {{ $user->id == old('user_id', $unpack->user_id) ? 'selected' : '' }}>
                                                {{ $user->name .($user->id == auth()->user()->id ? ' (Saya Sendiri)' : '')}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <div class="input-group">
                                <span class="input-group-text px-6" id="basic-addon1"><i
                                        class="ti ti-align-justified fs-6"></i></span>
                                <textarea class="form-control ps-2" name="description" id="description" cols="20" rows="5"
                                    placeholder="Description about this Product">{{old('description', $unpack->description)}}</textarea>
                            </div>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Perbarui Pembongkaran Bal</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal-{{$bal->id}}" class="modal fade" tabindex="-1"
        aria-labelledby="danger-header-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content p-3 modal-filled bg-danger">
                <div class="modal-header modal-colored-header text-white">
                    <h4 class="modal-title text-white" id="danger-header-modalLabel">
                        Yakin ingin menghapus Pembongkaran Bal ?
                    </h4>
                    <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="width: fit-content; white-space:normal">
                    <h5 class="mt-0 text-white">Pembongkaran Bal {{$bal->name}} akan dihapus</h5>
                    <p class="text-white">Segala data yang berkaitan dengan Pembongkaran Bal tersebut juga akan dihapus secara permanen.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Close
                    </button>
                    <form action="{{route('bal.unpack.destroy', ['id' => $bal->id, 'unpack_id' => $bal->unpack->id])}}" method="POST">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-dark">Ya, Hapus</button>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection
@section('scripts')
    <script src="{{env('APP_URL')}}/assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="{{env('APP_URL')}}/assets/libs/select2/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').each(function () {
                let modal = $(this).closest('.modal'); // Cari modal terdekat
                $(this).select2({
                    dropdownParent: modal // Pasang dropdown di dalam modal
                });
            });
            $('.select2-normal').each(function () {
                let modal = $(this).closest('.modal'); // Cari modal terdekat
                $(this).select2();
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.querySelector('input[type="file"][name="image"]');
            const placeholder = document.getElementById('placeholder-image');
            const nowImage = document.getElementById('now-image');
            const previewImage = document.getElementById('preview-image');

            fileInput.addEventListener('change', function(event) {
                const file = event.target.files[0];

                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        previewImage.src = e.target.result; // Set preview image source
                        nowImage.classList.add('d-none');
                        previewImage.classList.remove('d-none'); // Show the preview image
                        placeholder.classList.add('d-none'); // Hide the placeholder
                    };

                    reader.readAsDataURL(file);
                } else {
                    // Reset if no file or file is not an image
                    previewImage.src = '';
                    previewImage.classList.add('d-none');
                    nowImage.classList.remove('d-none');
                    placeholder.classList.remove('d-none');
                }
            });

            $('input[name="name"]').on('input', function() {
                const name = $(this).val();
                $('input[name="slug"]').val(makeSlug(name));
            });

            $('input[name="is_for_purchase"]').on('change', function(){
                let check = $(this).is(':checked');
                if(check) {
                    $('#purchase').removeClass('d-none');
                }else{
                    $('#purchase').addClass('d-none');
                }
            })
        });
    </script>
@endsection
