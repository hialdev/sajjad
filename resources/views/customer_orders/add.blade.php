@extends('layouts.base')
@section('css')
    <link rel="stylesheet" href="{{ env('APP_URL') }}/assets/libs/select2/dist/css/select2.min.css">
@endsection
@section('content')
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Buat Penjualan Instant Pelanggan</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('customer-order.index') }}">Penjualan Instant Pelanggan</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Buat Penjualan Instant Pelanggan</li>
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
            <div class="col-12">
                <div class="card">
                    <div class="px-4 py-3 border-bottom">
                        <h5 class="card-title fw-semibold mb-0">Penjualan Instant Pelanggan</h5>
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
                        <form action="{{ route('customer-order.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Tanggal Pembelian</label>
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
                                <label class="form-label fw-semibold">Penanggung Jawab Penjualan</label>
                                <div class="input-group">
                                    <span class="input-group-text px-6" id="basic-addon1"><i
                                            class="ti ti-user-circle fs-6"></i></span>
                                    <div style="flex-grow:1">
                                        <select name="user_id" id="user_id" class="select2-normal form-select">
                                            <option value="">-- Pilih User --</option>
                                            @foreach ($users as $user)
                                                <option value="{{$user->id}}" {{$user->id == old('user_id') ? 'selected' : ''}}>{{$user->name}} {{auth()->user()->id == $user->id ? '(Saya sendiri)' : ''}}</option>
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
                            
                            
                            <div class="p-3 bg-primary-subtle rounded-3 mb-4">
                                <label class="form-label fw-semibold">Customer / Pelanggan</label>
                                <div class="input-group">
                                    <span class="input-group-text px-6" id="basic-addon1"><i
                                            class="ti ti-truck-delivery fs-6"></i></span>
                                    <div style="flex-grow:1">
                                        <select name="customer_id" id="customer_id" class="select2-normal form-select">
                                            <option value="">-- Pilih Pelanggan / Customer --</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{$customer->id}}" {{$customer->id == old('customer_id') ? 'selected' : ''}}>{{$customer->name.', '.$customer->email.' - '.$customer->phone }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @error('customer_id')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror

                                <div class="d-flex align-items-center gap-2 mt-2">
                                    Tidak menemukan Customer yang sesuai ? 
                                    <button type="button" class="btn btn-sm text-primary bg-primary-subtle"
                                        data-bs-toggle="modal"
                                        data-bs-target="#addCustomerModal"
                                    >Tambah Customer</button>
                                </div>

                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Deskripsi</label>
                                <div class="input-group">
                                    <span class="input-group-text px-6" id="basic-addon1"><i
                                            class="ti ti-align-justified fs-6"></i></span>
                                    <textarea class="form-control ps-2" name="description" id="description" cols="20" rows="5"
                                        placeholder="Deskripsi untuk Penjualan Instant Pelanggan">{{old('description')}}</textarea>
                                </div>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">
                                Simpan dan Lanjut ke Produk
                            </button>
                        </form>
                    </div>
                </div>

                @include('customers.addmodal',['id' => 'addCustomerModal'])

            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ env('APP_URL') }}/assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="{{ env('APP_URL') }}/assets/libs/select2/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            const stepMap = {
                'data': 1,
                'produk': 2,
                'lampiran': 3
            };

            function updateStepFromHash() {
                let hash = window.location.hash.replace('#', '');
                let step = stepMap[hash] || 1; // Default ke step pertama jika tidak ditemukan
                setActiveStep(step);
            }

            function setActiveStep(step) {
                $(".step").removeClass("active");
                $(".step-content").hide();
                $(".step[data-step='" + step + "']").addClass("active");
                $(".step-content[data-step='" + step + "']").fadeIn();
            }

            $(".step").click(function() {
                let step = $(this).data("step");
                let hashKey = Object.keys(stepMap).find(key => stepMap[key] === step);
                if (hashKey) {
                    window.location.hash = hashKey; // Perbarui hash di URL
                }
            });

            // Jalankan saat halaman dimuat
            updateStepFromHash();

            // Jalankan saat hash berubah
            $(window).on('hashchange', function() {
                updateStepFromHash();
            });

            document.querySelectorAll(".editAddressBtn").forEach(button => {
                button.addEventListener("click", function () {
                    // Ambil data dari atribut tombol
                    let id = this.getAttribute("data-id");
                    let name = this.getAttribute("data-name");
                    let city = this.getAttribute("data-city");
                    let postalCode = this.getAttribute("data-postal-code");
                    let address = this.getAttribute("data-address");
                    let url = this.getAttribute("data-url");

                    // Isi data ke dalam modal
                    document.getElementById("editName").value = name;
                    document.getElementById("editPostalCode").value = postalCode;
                    document.getElementById("editAddress").value = address;

                    // **Set nilai Select2 dan trigger change event**
                    $("#editCity").val(city).trigger("change");

                    // Ubah action form agar sesuai dengan alamat yang diedit
                    document.getElementById("editAddressForm").setAttribute("action", url);
                });
            });

        });

    </script>
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

            $('#principal_id').on('change', async function () {
                fetchShowBy({
                    url: "{{ route('ajax.showBy') }}",
                    model: "PrincipalPic", // Model yang akan di-fetch
                    key: "principal_id", // Kolom yang digunakan untuk filter
                    data: $(this).val(), // Ambil nilai dari selector
                    isCollection: true, // Apakah hasil koleksi?
                    affectSelectorId: '#principal_pic_id', // Selector untuk menerima data
                    optionPlaceholder: '-- Pilih PIC Principal --' // Placeholder untuk opsi pertama
                });

                fetchByRelation({
                    url: "{{ route('ajax.showRelation') }}",
                    model: "Principal", // Model yang akan di-fetch
                    relation: "addresses", // Kolom yang digunakan untuk filter
                    key: "id", // Kolom yang digunakan untuk filter
                    data: $(this).val(), // Ambil nilai dari selector
                    affectSelectorId: '#pickup_address_id', // Selector untuk menerima data
                    optionPlaceholder: '-- Pilih Alamat Pengambilan Barang --' // Placeholder untuk opsi pertama
                });
            });

            $('#request_order_id').on('change', async function () {
                fetchByRelation({
                    url: "{{ route('ajax.showRelation') }}",
                    model: "RequestOrder", // Model yang akan di-fetch
                    relation: "client.addresses", // Kolom yang digunakan untuk filter
                    key: "id", // Kolom yang digunakan untuk filter
                    data: $(this).val(), // Ambil nilai dari selector
                    affectSelectorId: '#delivery_address_id', // Selector untuk menerima data
                    optionPlaceholder: '-- Pilih Alamat Pengantaran --' // Placeholder untuk opsi pertama
                });
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
                if (event.target.matches('input[type="file"][name="attachment"]')) {
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

            let reqid = @JSON(request()->get('reqid'));
            if(reqid){
                fetchByRelation({
                    url: "{{ route('ajax.showRelation') }}",
                    model: "RequestOrder", // Model yang akan di-fetch
                    relation: "client.addresses", // Kolom yang digunakan untuk filter
                    key: "id", // Kolom yang digunakan untuk filter
                    data: reqid, // Ambil nilai dari selector
                    affectSelectorId: '#delivery_address_id', // Selector untuk menerima data
                    optionPlaceholder: '-- Pilih Alamat Pengantaran --' // Placeholder untuk opsi pertama
                });
            }
        });
    </script>
@endsection
