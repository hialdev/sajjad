@extends('layouts.base')
@section('css')
<link rel="stylesheet" href="{{env('APP_URL')}}/assets/libs/select2/dist/css/select2.min.css">
@endsection
@section('content')
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Tambah Payment Method</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('payment.index') }}">Produk</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Tambah</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="../assets/images/breadcrumb/ChatBc.png" alt="" class="img-fluid mb-n4" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 h-100 mb-3">
            <div id="placeholder-image"
                class="d-flex p-5 text-center align-items-center justify-content-center rounded-5 border-2 border-dashed"
                style="aspect-ratio:1/1">
                <div>
                    <div class="fs-4">If Image Selected, it will show (Preview)</div>
                </div>
            </div>
            <img src="" id="preview-image" alt="Product Image Preview" class="d-none rounded-4 shadow w-100"
                style="">
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="px-4 py-3 border-bottom">
                    <h5 class="card-title fw-semibold mb-0">Tambah Payment Method</h5>
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
                    <form action="{{ route('payment.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Image QRIS / Bank</label>
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
                            <label class="form-label fw-semibold">Nama Bank</label>
                            <div class="input-group">
                                <span class="input-group-text px-6" id="basic-addon1"><i
                                        class="ti ti-text-caption fs-6"></i></span>
                                <input type="text" name="bank_name" value="{{old('bank_name')}}" class="form-control ps-2" placeholder="Nama Bank / QRIS">
                            </div>
                            @error('bank_name')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Atas Nama</label>
                            <div class="input-group">
                                <span class="input-group-text px-6" id="basic-addon1"><i
                                        class="ti ti-text-caption fs-6"></i></span>
                                <input type="text" name="account_name" value="{{old('account_name')}}" class="form-control ps-2" placeholder="Atas Nama">
                            </div>
                            @error('account_name')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold">No. Rekening</label>
                            <div class="input-group">
                                <span class="input-group-text px-6" id="basic-addon1"><i
                                        class="ti ti-text-caption fs-6"></i></span>
                                <input type="text" name="account_number" value="{{old('account_number')}}" class="form-control ps-2" placeholder="Nomor Rekening">
                            </div>
                            @error('account_number')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                           <label for="text" class="form-label">Type</label>
                           <div class="input-group mb-2">
                              <span class="input-group-text px-6" id="basic-addon1"><i
                                       class="ti ti-package fs-6"></i></span>
                              <div style="flex-grow:1">
                                 <select name="type" id="type_added" class="select2-normal form-select">
                                       <option value="">-- Pilih Tipe Rekening --</option>
                                       <option value="bank">Bank Transfer</option>
                                       <option value="qris">QRIS</option>
                                 </select>
                              </div>
                           </div>
                        </div>

                        <div class="mb-4">
                           <div class="form-check form-switch">
                              <input class="form-check-input" name="is_active" type="checkbox" value="1" id="is_active" checked />
                              <label class="form-check-label" for="is_active">Aktifkan Metode</label>
                           </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <div class="input-group">
                                <span class="input-group-text px-6" id="basic-addon1"><i
                                        class="ti ti-align-justified fs-6"></i></span>
                                <textarea class="form-control ps-2" name="description" id="description" cols="20" rows="5"
                                    placeholder="Description about this Product">{{old('description', request()->get('desc'))}}</textarea>
                            </div>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">
                            Tambah Payment Method
                        </button>
                    </form>
                </div>
            </div>
        </div>
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
        $(document).ready(function () {
            // Ketika purchase order diubah
            
            $('#receive').on('change', function () {
                const id = $(this).val();
                if (!id) return;

                $.get(`/receive/${id}/products`, function (data) {
                    let html = '';

                    data.forEach((prod) => {
                        html += `
                            <div class="d-flex mb-1 flex-1 w-100 align-items-center gap-2 flex-wrap">
                                <input type="hidden" name="product_id[]" value="${prod.product_id}">
                                <img src="${ prod.image }"
                                    class="rounded-2" alt="product Image ${prod.name}" style="width: 4em" />
                                <div class="">
                                    <div class="fs-1 badge shadow-sm mb-1 bg-primary-subtle text-primary">${prod.type}</div>
                                    <h6 class="fw-semibold mb-1" style="white-space: normal !important">${prod.name}</h6>
                                    <div class="d-flex align-items-center gap-2">
                                        <div><i class="ti ti-arrow-up"></i> ${prod.height} cm</div>
                                        <div><i class="ti ti-arrow-right"></i> ${prod.width} cm</div>
                                    </div>
                                </div>
                                <div class="ms-auto">
                                    <div class="fs-2 mb-1">Qty. (available ${prod.qty})</div>
                                    <input type="number" class="form-control " style="max-width:10em" name="qty[]" value="0" max="${prod.qty}" placeholder="${prod.qty}">
                                </div>
                            </div>`;
                    });

                    $('#product-container').html(html);
                });
            });

            // Ambil nilai purchase_order dari query string
            let purchaseReceive = @json(request()->get('receive'));

            if (purchaseReceive) {
                $('#receive').val(purchaseReceive).trigger('change');
            }

            // Hapus baris produk
            $(document).on('click', '.remove-row', function () {
                $(this).closest('.product-row').remove();
            });

            // Tambah produk manual
            let productCounter = 0;
            $('#add-product').on('click', function () {
                productCounter++; // Tambah counter setiap klik

                let manual = `
                    <div class="d-flex flex-wrap align-items-end gap-2 mb-3 product-row">
                        <div style="width:100%; max-width:30em">
                            <select name="product_id[]" id="select_product_${productCounter}" class="form-select">
                                <option value="">-- Pilih Produk --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->name }} - Jual {{ formatRupiah($product->price_per_unit) }} / {{ $product->unit->code }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <div class="fs-1 mb-1 text-dark">Sebanyak</div>
                            <input type="number" class="form-control bg-white" name="qty[]" value="0" placeholder="Sebanyak" style="max-width:10em">
                        </div>
                        <button type="button" class="btn btn-sm btn-danger remove-row"><i class="ti ti-trash"></i></button>
                    </div>`;
                $('#product-container').append(manual);
            
                // Inisialisasi select2 setelah ditambahkan
                $(`#select_product_${productCounter}`).select2({
                    dropdownParent: $('#product-container') // opsional jika select2 di modal
                });
            });

        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.querySelector('input[type="file"][name="image"]');
            const placeholder = document.getElementById('placeholder-image');
            const previewImage = document.getElementById('preview-image');

            fileInput.addEventListener('change', function(event) {
                const file = event.target.files[0];

                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        previewImage.src = e.target.result; // Set preview image source
                        previewImage.classList.remove('d-none'); // Show the preview image
                        placeholder.classList.add('d-none'); // Hide the placeholder
                    };

                    reader.readAsDataURL(file);
                } else {
                    // Reset if no file or file is not an image
                    previewImage.src = '';
                    previewImage.classList.add('d-none');
                    placeholder.classList.remove('d-none');
                }
            });

            $('input[name="name"]').on('input', function() {
                const name = $(this).val();
                $('input[name="slug"]').val(makeSlug(name));
            });

            $('input[name="is_from_receive"]').on('change', function(){
                let check = $(this).is(':checked');
                if(check) {
                    $('#switchbox').removeClass('d-none');
                }else{
                    $('#switchbox').addClass('d-none');
                }
            })
        });
    </script>
@endsection
