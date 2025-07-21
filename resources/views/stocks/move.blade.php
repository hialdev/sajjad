@extends('layouts.base')
@section('css')
    <link rel="stylesheet" href="{{ env('APP_URL') }}/assets/libs/select2/dist/css/select2.min.css">
@endsection
@section('content')
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Distribusi Barang / Stock</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('stock.distribution') }}">Distribusi</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Distribusi Barang / Stock</li>
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
    <div class="">
        <div class="row">
            <div class="col-md-4 h-100 mb-3">
                <div id="placeholder-image"
                    class="d-flex p-5 text-center align-items-center justify-content-center rounded-5 border-2 border-dashed"
                    style="aspect-ratio:1/1">
                    <div>
                        <div class="fs-4">If Image / File Selected, it will show (Preview)</div>
                    </div>
                </div>
                <img src="" id="preview-image" alt="Distribusi Image Preview" class="d-none rounded-4 shadow w-100"
                    style="">
                <div id="preview-file" class="d-none">
                    <a href="" id="preview-file-link" class="d-flex align-items-center gap-2 p-2 rounded-2 border border-al-primary" target="_blank">
                        <i class="ti ti-file fs-6"></i>
                        <div id="preview-file-text" class="fs-2 line-clamp line-clamp-2">File Name</div>
                    </a>
                    <embed id="preview-file-embed" src="" class="rounded-4 overflow-hidden mt-3" width="100%" height="600px" type="application/pdf">
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="px-4 py-3 border-bottom">
                        <h5 class="card-title fw-semibold"> Distribusi Barang / Stock </h5>
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
                        <form action="{{ route('stock.moved')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Tanggal Distribusi</label>
                                <div class="input-group">
                                    <span class="input-group-text px-6" id="basic-addon1"><i
                                            class="ti ti-calendar-event fs-6"></i></span>
                                    <input type="date" name="date" class="form-control ps-2" value="{{old('date', \Carbon\Carbon::parse(now())->format('Y-m-d'))}}">
                                </div>
                                @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Penanggung Jawab</label>
                                <div class="input-group">
                                    <span class="input-group-text px-6" id="basic-addon1"><i
                                            class="ti ti-building-skyscraper fs-6"></i></span>
                                    <div style="flex-grow:1">
                                        <select name="user_id" id="user_id" class="select2-normal form-select">
                                            <option value="">-- Pilih PIC --</option>
                                            @foreach ($users as $user)
                                                <option value="{{$user->id}}" {{$user->id == old('user_id') ? 'selected' : ''}}>{{$user->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @error('user_id')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Lampirkan Foto Distribusi Barang / Stock</label>
                                <div class="input-group">
                                    <span class="input-group-text px-6" id="basic-addon1"><i
                                            class="ti ti-file fs-6"></i></span>
                                    <input type="file" name="image" class="form-control ps-2">
                                </div>
                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" name="is_handle_logistic" type="checkbox" value="1" id="is_handle_logistic" {{ old('transport_id') ? 'checked' : '' }} />
                                    <label class="form-check-label" for="is_handle_logistic">Perusahaan mengurus Logistik / Pengangkutan</label>
                                </div>
                            </div>
                            <div class="p-3 bg-primary-subtle rounded-3 mb-4 {{ old('transport_id') ? '' : 'd-none' }}" id="logistic_input">
                                <label class="form-label fw-semibold">Pengangkutan / Logistik Distribusi</label>
                                <div class="input-group">
                                    <span class="input-group-text px-6" id="basic-addon1"><i
                                            class="ti ti-truck-delivery fs-6"></i></span>
                                    <div style="flex-grow:1">
                                        <select name="transport_id" id="transport_id" class="select2-normal form-select">
                                            <option value="">-- Pilih Pengangkutan --</option>
                                            @foreach ($transports as $transport)
                                                <option value="{{$transport->id}}" {{$transport->id == old('transport_id') ? 'selected' : ''}}>{{$transport->code.' | Memakai Logistik : '.$transport->logistic->name.', Dengan Total Harga : '.formatRupiah($transport->total_price).', PPN : '.($transport->tax ?? 0).'%' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @error('transport_id')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror

                                <div class="d-flex align-items-center gap-2 my-2">
                                    Tidak menemukan Pengangkutan yang sesuai ? 
                                    <button type="button" class="btn btn-sm text-primary bg-primary-subtle"
                                        data-bs-toggle="modal"
                                        data-bs-target="#addPengangkutanModal"
                                    >Tambah Pengangkutan</button>
                                </div>
                            </div>

                            <div class="p-3 bg-primary-subtle rounded-3 mb-4">
                                <div class="mb-2">
                                    <label class="form-label fw-semibold">Distribusi Barang Dari</label>
                                    <div class="input-group">
                                        <span class="input-group-text px-6" id="basic-addon1"><i
                                                class="ti ti-truck-delivery fs-6"></i></span>
                                        <div style="flex-grow:1">
                                            <select name="from_id" id="from_id" class="select2-normal form-select">
                                                <option value="">-- Pilih Gudang / Toko --</option>
                                                @foreach ($warehouses as $warehouse)
                                                   @if((int) $warehouse->analytics()->stock->total_remaining > 0 || $warehouse->analytics()->meteran->total_remaining > 0)
                                                      <option value="{{$warehouse->id}}" {{$warehouse->id == old('from_id', request()->get('from_id')) ? 'selected' : ''}} style="white-space:normal !important">{{ "[Gudang] [Satuan : {$warehouse->analytics()->stock->total_remaining} qty] [Meteran : {$warehouse->analytics()->meteran->total_remaining} qty]| {$warehouse->name}, {$warehouse->address} - {$warehouse->city}" }}</option>
                                                   @endif
                                                @endforeach
                                                @foreach ($stores as $store)
                                                   @if((int) $store->analytics()->stock->total_remaining > 0 || $store->analytics()->meteran->total_remaining > 0)
                                                      <option value="{{$store->id}}" {{$store->id == old('from_id', request()->get('from_id')) ? 'selected' : ''}} style="white-space:normal !important">{{ "[Toko] [Satuan : {$store->analytics()->stock->total_remaining} qty] [Meteran : {$store->analytics()->meteran->total_remaining} qty]| {$store->name}, {$store->address} - {$store->city}" }}</option>
                                                   @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @error('from_id')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="">
                                    <label class="form-label fw-semibold">Distribusikan Menuju</label>
                                    <div class="input-group">
                                        <span class="input-group-text px-6" id="basic-addon1"><i
                                                class="ti ti-truck-delivery fs-6"></i></span>
                                        <div style="flex-grow:1">
                                            <select name="to_id" id="to_id" class="select2-normal form-select">
                                                <option value="">-- Pilih Gudang / Toko --</option>
                                                @foreach ($warehouses as $warehouse)
                                                    <option value="{{$warehouse->id}}" {{$warehouse->id == old('to_id', request()->get('to_id')) ? 'selected' : ''}}>{{ "[Gudang] {$warehouse->name}, {$warehouse->address} - {$warehouse->city}. {$warehouse->postal_code}" }}</option>
                                                @endforeach
                                                @foreach ($stores as $store)
                                                    <option value="{{$store->id}}" {{$store->id == old('to_id', request()->get('to_id')) ? 'selected' : ''}}>{{ "[Toko] {$store->name}, {$store->address} - {$store->city}. {$store->postal_code}" }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @error('to_id')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Deskripsi</label>
                                <div class="input-group">
                                    <span class="input-group-text px-6" id="basic-addon1"><i
                                            class="ti ti-align-justified fs-6"></i></span>
                                    <textarea class="form-control ps-2" name="description" id="description" cols="20" rows="5"
                                        placeholder="DeskripsiDistribusi Barang / Stock">{{old('description')}}</textarea>
                                </div>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">
                                Simpan dan Tentukan Produk / Bal Distribusi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @include('transports.addmodal',['id' => 'addPengangkutanModal'])

    </div>
@endsection
@section('scripts')
    <script src="{{ env('APP_URL') }}/assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="{{ env('APP_URL') }}/assets/libs/select2/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').each(function() {
                let modal = $(this).closest('.modal'); // Cari modal terdekat
                $(this).select2({
                    dropdownParent: modal // Pasang dropdown di dalam modal
                });
            });
            $('.select2-normal').each(function() {
                let modal = $(this).closest('.modal'); // Cari modal terdekat
                $(this).select2();
            });

        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var redirectHash = "{{ session('redirect_hash') }}";
            if (redirectHash) {
                window.location.hash = redirectHash;
            }

            document.addEventListener('change', function(event) {
                // Cek apakah event berasal dari input file yang memiliki atribut name="image"
                if (event.target.matches('input[type="file"][name="image"]')) {
                    const fileInput = event.target;
                    const formContainer = fileInput.closest('.row'); // Mencari form terkait dalam satu grup

                    if (!formContainer) return;

                    const placeholder = formContainer.querySelector('#placeholder-image');
                    const previewImage = formContainer.querySelector('#preview-image');
                    const previewFile = formContainer.querySelector('#preview-file');
                    const previewFileLink = formContainer.querySelector('#preview-file-link');
                    const previewFileEmbed = formContainer.querySelector('#preview-file-embed');
                    const previewFileName = formContainer.querySelector('#preview-file-text');

                    const file = fileInput.files[0];

                    if (file) {
                        if (file.type.startsWith('image/')) {
                            // Preview image
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                previewImage.src = e.target.result;
                                previewImage.classList.remove('d-none'); // Show image preview
                                previewFile.classList.add('d-none'); // Hide file preview
                                placeholder.classList.add('d-none'); // Hide placeholder
                            };
                            reader.readAsDataURL(file);
                        } else {
                            // Preview file
                            previewFileName.textContent = file.name;
                            previewFileEmbed.src = URL.createObjectURL(file); // Temporary file link
                            previewFileLink.href = URL.createObjectURL(file); // Temporary file link
                            previewFile.classList.remove('d-none'); // Show file preview
                            previewImage.classList.add('d-none'); // Hide image preview
                            placeholder.classList.add('d-none'); // Hide placeholder
                        }
                    } else {
                        // Reset previews
                        previewImage.src = '';
                        previewImage.classList.add('d-none');
                        previewFile.classList.add('d-none');
                        placeholder.classList.remove('d-none'); // Show placeholder
                    }
                }
            });

            $('input[name="is_handle_logistic"]').on('change', function(){
                let check = $(this).is(':checked');
                if(check) {
                    $('#logistic_input').removeClass('d-none');
                }else{
                    $('#logistic_input').addClass('d-none');
                }
            })

            $('input[name="name"]').on('input', function() {
                const name = $(this).val();
                $('input[name="slug"]').val(makeSlug(name));
            });

            $('input[name="price"]').on('input', function() {
                $(this).val(formatRupiah($(this).val()));
            })
        });
    </script>
@endsection
