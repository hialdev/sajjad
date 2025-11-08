@extends('layouts.base')
@section('css')
    <link rel="stylesheet" href="{{ env('APP_URL') }}/assets/libs/select2/dist/css/select2.min.css">
    <style>
        .stepper {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            position: relative;
        }

        .stepper .step {
            width: 100px;
            text-align: center;
            position: relative;
            cursor: pointer;
        }

        .stepper .step .circle {
            width: 35px;
            height: 35px;
            background-color: #ccc;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            margin: 0 auto;
            transition: 0.3s;
        }

        .stepper .step.active .circle {
            background: rgba(var(--bs-primary-rgb));
        }

        .stepper .step .label {
            margin-top: 8px;
            font-size: 14px;
        }

        .stepper .line {
            position: absolute;
            top: 17px;
            left: 50%;
            width: 100%;
            height: 5px;
            background-color: #ccc;
            z-index: -1;
        }

        .stepper .step.active .line {
            background: rgba(var(--bs-primary-rgb));
        }

        .stepper .step:last-child .line {
            display: none;
        }
    </style>
@endsection
@section('content')

    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Analitik Stok Toko : {{ $store->name }}</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('store.index') }}">Toko</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Kelola Stok Toko : {{ $store->name }}
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="{{ env('APP_URL') }}/assets/images/breadcrumb/ChatBc.png" alt=""
                            class="img-fluid mb-n4" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="">
        <div class="card ">
            <div class="card-body flex-column flex-md-row flex-wrap d-flex justify-between align-items-center">
               <div class="me-auto d-flex align-items-center" style="width:15em">
                  <img src="{{ $store->image ? asset('/storage/'.$store->image) : '/assets/images/profile/user-1.jpg' }}"
                     class="rounded-2" alt="Client Image {{ $store->name }}" style="width: 4em" />
                  <div class="ms-3">
                     <h6 class="fw-semibold mb-1" style="white-space: normal !important">{{ $store->name }}</h6>
                     <div class="fw-normal fs-2 text-muted" style="white-space:normal; font-size:13px; ">{{ $store->description ?? 'Tidak ada deskripsi' }}</div>    
                  </div>
               </div>

               <div class="ms-auto w-100 flex-column flex-md-row d-flex align-items-center gap-4 border-2 border-light-subtle border-dashed p-2 rounded-2 mt-3">
                  @php
                        $sanalytic = $store->analytics()
                  @endphp
                  <div class="text-dark fs-2 fw-semibold d-flex align-items-center justify-content-center p-1 px-2 rounded-2 bg-primary-subtle mb-1" title="Total Jenis Satuan"><i class="ti ti-package me-2"></i> {{$sanalytic->stock->total_product}} Produk</div>
                  <div class="d-flex align-items-center pb-1 mb-1 justify-content-around">
                        <div class="text-dark fw-bold" title="Total Stock Satuan"><i class="ti ti-package me-2"></i> {{$sanalytic->stock->total_remaining}}</div>

                        <div class="d-flex align-items-center">
                           <div class="px-2 border-x-2 text-success fw-semibold">{{ $sanalytic->stock->total_in }}</div>
                           <div class="px-2 border-x-2 text-danger fw-semibold">{{ $sanalytic->stock->total_out }}</div>
                           <div class="px-2 border-x-2 text-warning fw-semibold">{{ $sanalytic->stock->total_onway }}</div>
                        </div>
                  </div>

                  <div class="text-dark fs-2 fw-semibold d-flex align-items-center justify-content-center p-1 px-2 rounded-2 bg-primary-subtle mb-1" title="Total Jenis Satuan"><i class="ti ti-ruler me-2"></i> {{$sanalytic->meteran->total_product}} Produk Meteran</div>
                  <div class="d-flex align-items-center pb-1 mb-1 justify-content-around">
                        <div class="text-dark fw-bold" title="Total Stock Satuan"><i class="ti ti-package me-2"></i> {{$sanalytic->meteran->total_remaining}}</div>

                        <div class="d-flex align-items-center">
                           <div class="px-2 border-x-2 text-success fw-semibold">{{ $sanalytic->meteran->total_in }}</div>
                           <div class="px-2 border-x-2 text-warning fw-semibold">{{ $sanalytic->meteran->total_onway }}</div>
                        </div>
                  </div>

                  <div class="mb-1 justify-content-around">
                        <div class="text-dark fs-2 fw-semibold d-flex align-items-center justify-content-center p-1 px-2 rounded-2 border mb-1" title="Total Bal"><i class="ti ti-circles fs-4 me-2"></i> {{$sanalytic->total_bal}} Bal</div>
                  </div>

               </div>
            </div>
         </div>
        
        <div class="row">
            <div class="col-6 col-md-3 mb-auto d-flex align-items-center gap-2">
                
            </div>
            <div class="col-md-6 order-first order-md-0 d-flex align-items-start gap-2 flex-wrap">
                {{-- <div class="stepper flex-grow-1 overflow-auto">
                    <div class="step active" data-step="1">
                        <div class="circle">1</div>
                        <div class="label fs-2">Produk</div>
                        <div class="line"></div>
                    </div>
                </div> --}}
            </div>
            <div class="col-6 col-md-3 mb-4 d-flex justify-content-end align-items-start gap-2">
                <button onclick="seePDF('pdf.po','{{$store->id}}')" class="btn btn-danger" style="background:rgb(186, 55, 55); border-color:rgb(186, 55, 55)"><i class="ti ti-printer"></i><span class="d-none ms-2 d-sm-inline-block">Cetak</span></button>
                <a href="{{route('stock.move', ['from_id' => $store->id])}}" class="btn btn-primary"><i class="ti ti-truck-delivery me-1"></i> Distribusikan</a>
            </div>

        </div>

        <!-- Form Step -->
        <div id="stepper-form">
            
            <!-- Form Step 1 -->
            <div class="step-content active" data-step="1" style="display: none;">
                <div class="row">
                    <div class="col-12 mb-3 order-first">
                        <div class="d-flex mb-3 align-items-center gap-3">
                            <i class="ti ti-packages fs-8"></i>
                            <h5 class="mb-0">Produk</h5>
                            <div class="d-flex align-items-center justify-content-center bg-primary text-white p-2 rounded-circle"
                                style="aspect-ratio:1/1; width:2.5em; height:2.5em">
                                {{ count($store->products()) }}
                            </div>
                        </div>
                        @forelse ($store->products() as $product)
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $product->image ? asset('/storage/'.$product->image) : '/assets/images/profile/user-1.jpg' }}"
                                            class="rounded-2" alt="product Image {{ $product->name }}" style="width: 4em" />
                                        <div class="ms-3">
                                            <a href="{{route('product.index', ['search' => $product->code])}}" target="_blank" class="d-block">
                                                <div class="d-flex align-items-center gap-1">
                                                  <div class="badge bg-primary fs-1 mb-1 text-white">{{ $product->code }}</div>
                                                  <div class="badge bg-primary-subtle fs-1 mb-1 text-primary text-uppercase">{{ $product->type->type }}</div>
                                                </div>
                                                <h6 class="fw-semibold mb-1" style="white-space: normal !important">{{ $product->name }}</h6>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div><i class="ti ti-arrow-up"></i> {{ $product->height }} cm</div>
                                                    <div><i class="ti ti-arrow-right"></i> {{ $product->width }} cm</div>
                                                </div>
                                                <div class="fw-normal" style="white-space:normal; font-size:13px; ">{{ $product->description ?? 'Tidak ada deskripsi'}}</div>    
                                            </a>
                                        </div>

                                        <div class="ms-auto">
                                          <div class="fs-2 text-dark text-end">Qty</div>
                                          <div class="fs-5 text-primary">{{ $store->getProductRemaining($product->id) }}</div>
                                        </div>
                                    </div>
                                    
                                    @if($product->type->type == 'meteran')
                                       @php
                                       $detailStocks = $product->getAnalyticLocation('store', $store->id)[0]?->details;
                                       @endphp
                                       <div class="p-4 rounded-3 border border-dashed mt-3">
                                          <div class="fw-medium text-dark mb-2">Detail Stok Produk</div>
                                          @forelse ($detailStocks as $detail)
                                             <div class="d-flex align-items-center gap-4">
                                                <div class="d-flex align-items-center justify-content-center p-2 rounded-circle bg-primary-subtle" style="aspect-ratio: 1/1; width: 2em; height: 2em;">{{ $loop->index+1 }}</div>
                                                <div>
                                                   <div class="text-muted">Tersedia</div>
                                                   <div class="text-success fw-semibold">{{ $detail?->length - $detail?->sold_length }} cm / {{ ($detail?->length - $detail?->sold_length)/100 }}m</div>
                                                </div>
                                                <div>
                                                   <div class="text-muted">Terjual</div>
                                                   <div class="text-danger fw-semibold">{{ $detail?->sold_length }} cm / {{ $detail?->sold_length / 100 }}m</div>
                                                </div>
                                                <div class="p-2 rounded-4 bg-secondary-subtle">Updated at {{ \Carbon\Carbon::parse($detail?->updated_at)->diffForHumans() }}</div>
                                             </div>
                                          @empty
                                             <div class="text-muted">Tidak ada detail stok</div>
                                          @endforelse
                                       </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="p-5 rounded-3 bg-light text-center border-2 border-dashed">
                                Belum ada Bal yang ditambahkan untuk Pemesanan {{ $store->code }}
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
        
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            const stepMap = {
                'produk': 1,
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
                button.addEventListener("click", function() {
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
        $(document).ready(function () {
            // Tutup semua content accordion saat halaman dimuat
            $(".btn-accordion-content").hide();

            // Tambahkan event klik untuk setiap .btn-accordion
            $(".btn-accordion").on("click", function () {
                let content = $(this).next(".btn-accordion-content");

                // Tutup accordion lain (opsional, jika hanya satu yang boleh terbuka)
                $(".btn-accordion-content").not(content).slideUp();

                // Toggle slide untuk content yang diklik
                content.slideToggle();
            });
        });
    </script>
@endsection
