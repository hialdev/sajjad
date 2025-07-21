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
                    <h4 class="fw-semibold mb-8">Kelola Penerimaan Barang : {{ $receive->code }}</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('receive.index') }}">Penerimaan Barang</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Kelola Penerimaan Barang : {{ $receive->code }}
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
        <div class="row">
            <div class="col-6 col-md-3 mb-auto d-flex align-items-center gap-2">
                @if($receive->purchase->status == 2)
                <form action="{{ route('purchase-order.generate', $receive->purchase->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-primary {{$receive->purchase->generate_invoice ? 'd-none' : ''}}"><i class="ti ti-credit-card me-1"></i> Tagih</button>
                </form>
                @endif
                @if($receive->purchase->status != 2 && !$receive->is_stocked)
                <button type="button" class="btn p-2 px-3 d-flex btn-success align-items-center gap-2"
                            data-bs-toggle="modal" data-bs-target="#processModal-{{$receive->purchase->id}}"><i
                                class="fs-4 ti ti-check"></i><span class="d-none d-sm-block">Selesaikan</span></button>
                <!-- Process Modal -->
                <div class="modal fade" id="processModal-{{$receive->purchase->id}}" tabindex="-1"
                    aria-labelledby="vertical-center-modal" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header d-flex align-items-center">
                                <h4 class="modal-title" id="myLargeModalLabel" style="white-space: normal">
                                    Selesaikan Pembelian {{$receive->purchase->code}} dan Penerimaan {{$receive->code}}
                                </h4>
                                <button type="button" class="btn-close mb-auto" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body pt-0">
                                <form id="receiveForm" action="{{ route('receive.process', $receive->id) }}" method="POST">
                                    @csrf
                                    <p class="text-muted" style="white-space: normal">
                                        Pastikan Keadaan lapangan sudah sesuai, dan dapat dipertanggung jawabkan dengan baik untuk
                                        <strong>Selesaikan Pembelian ({{ $receive->purchase->code }}) dan Penerimaan ({{ $receive->code }})!
                                            Barang yang diterima akan ditambahkan kedalam stock</strong>
                                    </p>

                                    <div class="d-flex gap-1 align-items-center justify-content-end">
                                        <button id="submitBtn" type="submit" class="btn btn-success">
                                            Selesaikan Pembelian & Penerimaan
                                        </button>
                                    </div>
                                    <div id="loadingState" class="text-center mt-3" style="display: none;">
                                        <hr>
                                        <div class="spinner-border text-success" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <div class="mt-2 fw-bold text-success">Memproses Penerimaan ke Stock...</div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @php
                    $status = [
                        '0' => ['label' => 'Terbuka','color' => 'secondary'],
                        '1' => ['label' => 'Dikunci','color' => 'danger',],
                    ];
                @endphp
                <div>
                    <div class="fw-normal fs-1 text-muted" style="">Status Penerimaan
                    </div>
                    <h6 class="fw-semibold fs-2 text-{{ $status[$receive->is_lock ?? 0]['color'] }} mb-1" style="">{{ $status[$receive->is_lock ?? 0]['label'] }}</h6>
                </div>
            </div>
            <div class="col-md-6 order-first order-md-0 d-flex align-items-start gap-2 flex-wrap">
                <div class="stepper flex-grow-1 overflow-auto">
                    <div class="step active" data-step="1">
                        <div class="circle">1</div>
                        <div class="label fs-2">Data Penerimaan</div>
                        <div class="line"></div>
                    </div>
                    <div class="step" data-step="2">
                        <div class="circle">2</div>
                        <div class="label fs-2">Produk Diterima</div>
                        <div class="line"></div>
                    </div>
                    <div class="step" data-step="3">
                        <div class="circle">3</div>
                        <div class="label fs-2">Bal</div>
                        <div class="line"></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-4 d-flex justify-content-end align-items-start gap-2">
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteReceive-{{$receive->id}}"><i class="ti ti-trash"></i> <span class="ms-1 d-none d-md-inline-block">Hapus</span></button>
                <button onclick="seePDF('pdf.po','{{$receive->purchase->id}}')" class="btn btn-danger" style="background:rgb(186, 55, 55); border-color:rgb(186, 55, 55)"><i class="ti ti-printer me-2"></i><span class="d-none d-sm-inline-block">Cetak</span></button>
            </div>

            <!-- Delete Modal -->
            <div id="deleteReceive-{{$receive->id}}" class="modal fade" tabindex="-1"
                aria-labelledby="danger-header-modalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                    <div class="modal-content p-3 modal-filled bg-danger">
                        <div class="modal-header modal-colored-header text-white">
                            <h4 class="modal-title text-white" id="danger-header-modalLabel">
                                Yakin ingin menghapus Penerimaan Barang ({{$receive->code}}) ?
                            </h4>
                            <button type="button" class="btn-close btn-close-white mb-auto"
                                data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="width: fit-content; white-space:normal">
                            <h5 class="mt-0 text-white">Penerimaan Barang dari Principal {{$receive->code}} di gudang {{$receive->purchase->warehouse->name}} akan dihapus</h5>
                            <p class="text-white">Segala data yang berkaitan dengan Request Order tersebut juga akan dihapus secara permanen. Penghapusan dapat dilakukan jika tidak ada Transaksi penting yang terkait.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                Close
                            </button>
                            <form action="{{route('purchase-order.destroy', $receive->purchase->id)}}" method="POST">
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
        </div>

        <!-- Form Step 1 -->
        <div id="stepper-form">
            <div class="step-content active" data-step="1">
                <div class="card position-relative" id="showDataBox">
                    <button id="btnEditData" class="btn btn-sm btn-secondary position-absolute top-0 end-0 m-3"><i class="ti ti-edit"></i></button>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <h6>Data Penerimaan</h6>
                                <div>
                                    <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Tanggal</div>
                                    <h6 class="fs-2 fw-semibold text-success mb-1" style="">
                                        {{ \Carbon\Carbon::parse($receive->date)->format('d F Y') }}</h6>
                                </div>
                                <div>
                                    <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Detail Penerimaan
                                    </div>
                                    <h6 class="fw-semibold text-primary mb-1" style="">{{ $receive->code }}</h6>
                                </div>
                                <div>
                                    <div class=""><i class="ti ti-building-warehouse me-2"></i> {{ $receive->purchase->warehouse->name }}</div>
                                    <div class=""><i class="ti ti-user-circle me-2"></i> {{ $receive->user->name }}</div>
                                </div>
                                <div style="min-width: 10em">
                                    <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Deksripsi</div>
                                    <p class="mb-1 fs-2" style="white-space:normal !important;">{{ $receive->description ?? 'tidak ada deskripsi' }}</p>
                                </div>
                                <hr>
                                <h6>Pembelian</h6>
                                <div>
                                    <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Tanggal</div>
                                    <h6 class="fs-2 fw-semibold text-success mb-1" style="">
                                        {{ \Carbon\Carbon::parse($receive->purchase->date)->format('d F Y') }}</h6>
                                </div>
                                <div>
                                    <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kode Pembelian
                                    </div>
                                    <a href="{{route('purchase-order.setting', $receive->purchase->id)}}" class="d-flex align-items-center gap-2 mb-1">
                                        <h6 class="fw-semibold text-primary mb-0" style="">{{ $receive->purchase->code }}</h6>
                                        <i class="ti ti-external-link"></i>
                                    </a>
                                </div>
                                @php
                                    $status = [
                                        '0' => ['label' => 'Pending','color' => 'secondary'],
                                        '1' => ['label' => 'Diproses','color' => 'warning',],
                                        '2' => ['label' => 'Selesai','color' => 'success'],
                                    ];

                                    $statusInvoice = [
                                        '0' => ['label' => 'Belum Ditagih / Stock','color' => 'secondary'],
                                        '1' => ['label' => 'Ditagih','color' => 'success'],
                                    ];
                                @endphp
                                <div>
                                    <div class="fw-normal fs-1 text-muted" style="">Status Permintaan
                                    </div>
                                    <h6 class="fw-semibold fs-2 text-{{ $status[$receive->purchase->status]['color'] }} mb-1" style="">{{ $status[$receive->purchase->status]['label'] }}</h6>
                                </div>
                                <div>
                                    <div class="fw-normal fs-1 text-muted" style="">Status Penagihan Invoice
                                    </div>
                                    <h6 class="fw-semibold fs-2 text-{{ $statusInvoice[$receive->purchase->generate_invoice]['color'] }} mb-1" style="">{{ $statusInvoice[$receive->purchase->generate_invoice]['label'] }}</h6>
                                </div>
                                <div style="min-width: 10em">
                                    <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Deksripsi</div>
                                    <p class="mb-1 fs-2" style="white-space:normal !important;">{{ $receive->purchase->description ?? 'tidak ada deskripsi' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="">
                                    <h6>Memesan Ke Principal</h6>
                                    <a href="{{route('principal.setting', $receive->purchase->principal->id)}}" target="_blank" class="border border-primary p-1 px-2 rounded-2 d-inline-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-building-skyscraper mb-0 fs-3"></i> {{ $receive->purchase->principal->name }}
                                    </a>
                                    <div class="fw-normal fs-1 text-muted" style="">PIC Principal</div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-user-circle mb-0 fs-3"></i> {{ $receive->purchase->pic->name }}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-mail mb-0 fs-3"></i> {{ $receive->purchase->pic->email ?? '-' }}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-phone mb-0 fs-3"></i> {{ $receive->purchase->pic->phone ?? '-' }}
                                    </div>
                                </div>
                                <hr>
                                <div class="">
                                    <h6>Dikirim ke Gudang</h6>
                                    <a href="{{route('warehouse.index', ['search' => $receive->purchase->warehouse->name])}}" target="_blank" class="border border-primary p-1 px-2 rounded-2 d-inline-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-building-warehouse mb-0 fs-3"></i> {{ $receive->purchase->warehouse->name }}
                                    </a>
                                    <div class="fw-normal fs-1 text-muted" style="">Detail</div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-map-2 mb-0 fs-3"></i> {{ $receive->purchase->warehouse->address.', '.$receive->purchase->warehouse->city.'. '.$receive->purchase->warehouse->postal_code }}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-mail mb-0 fs-3"></i> {{ $receive->purchase->warehouse->email ?? '-' }}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-phone mb-0 fs-3"></i> {{ $receive->purchase->warehouse->phone ?? '-' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="">
                                    <h6>Dengan Detail Logistik / Pengangkutan</h6>
                                    @if( $receive->purchase->tarnsport )
                                    <a href="{{route('logistic.setting', $receive->purchase->transport->logistic->id)}}" target="_blank" class="border border-primary p-1 px-2 rounded-2 d-inline-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-truck-delivery mb-0 fs-3"></i> {{ $receive->purchase->transport->logistic->name }}
                                    </a>
                                    <a href="javascript:void(0);" title="Klik untuk melihat detail" class="d-flex text-secondary align-items-center fs-2 mb-1 gap-2"
                                        data-bs-toggle="modal" data-bs-target="#detailDelivery-{{$receive->purchase->id}}"
                                    >
                                        <i class="ti ti-exchange mb-0 fs-3"></i> Detail Antar Jemput
                                    </a>
                                    <!-- List Product modal -->
                                    <div class="modal fade " id="detailDelivery-{{$receive->purchase->id}}" tabindex="-1"
                                        aria-labelledby="vertical-center-modal" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header d-flex align-items-center">
                                                    <h4 class="modal-title" id="myLargeModalLabel">
                                                        Pengantaran dan Penjemputan Barang
                                                    </h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body pt-0">
                                                    <div class="p-3 rounded-3 border border-dashed mb-2 border-secondary">
                                                        <div class="fs-2 text-muted">Penjemputan Barang</div>
                                                        <div>
                                                            <div class="fs-3">{{$receive->purchase->pickup->address.', '.$receive->purchase->pickup->city.'. '.$receive->purchase->pickup->postal_code}}</div>
                                                        </div>
                                                    </div>
                                                    <div class="p-3 rounded-3 border border-dashed border-primary">
                                                        <div class="fs-2 text-muted">Pengantaran Barang</div>
                                                        <div>
                                                            <div class="fs-3">{{$receive->purchase->delivery->address.', '.$receive->purchase->delivery->city.'. '.$receive->purchase->delivery->postal_code}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fw-normal fs-1 text-muted" style="">Contact Person</div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-user-circle mb-0 fs-3"></i> {{ $receive->purchase->transport->logistic->cp_name }}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-mail mb-0 fs-3"></i> {{ $receive->purchase->transport->logistic->cp_email ?? '-' }}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-phone mb-0 fs-3"></i> {{ $receive->purchase->transport->logistic->cp_phone ?? '-' }}
                                    </div>
                                    @else
                                    <div class="fs-2">Diurus Oleh Principal</div>
                                    @endif
                                </div>
                                <hr>
                                <h6>Kalkulasi Pembayaran</h6>
                                @if($receive->purchase->products->count() > 0)
                                <div>
                                    <div class="fw-normal fs-1 text-muted" style="">Total Nilai Awal</div>
                                    <h6 class="fw-semibold fs-2 text-primary mb-1" style="">{{ formatRupiah($receive->purchase->total_price) }}</h6>
                                </div>
                                <div>
                                    <div class="fw-normal fs-1 text-muted" style="">Pajak</div>
                                    <h6 class="fw-semibold fs-2 text-primary mb-1" style="">{{ $receive->purchase->tax ?? '11' }}%</h6>
                                </div>
                                <div>
                                    <div class="fw-normal fs-1 text-muted" style="">Total Dengan Pajak</div>
                                    {{-- @php
                                        dd($receive->purchase->total_price, $receive->purchase->tax, $receive->purchase->tax && (int)$receive->purchase->tax != '0' ? $receive->purchase->tax : 11, ));
                                    @endphp --}}
                                    <h6 class="fw-semibold fs-2 text-primary mb-1">
                                        {{ 
                                            (int) $receive->purchase->total_price_taxed && (int) $receive->purchase->total_price_taxed != 0 
                                                ? formatRupiah($receive->purchase->total_price_taxed) 
                                                : formatRupiah((int) $receive->purchase->total_price + ((int) $receive->purchase->total_price * (($receive->purchase->tax && (int) $receive->purchase->tax != 0 ? $receive->purchase->tax : 11) / 100)))
                                        }}
                                    </h6>
                                </div>
                                @else
                                Lengkapi Data Dahulu
                                @endif

                                <hr>
                                <h6>Timestamp</h6>
                                <div class="d-flex flex-column align-items-start gap-2">
                                    <div class="badge bg-success-subtle text-success rounded-3 fw-semibold fs-2">
                                        Updated
                                        at
                                        : {{ $receive->purchase->updated_at }}</div>
                                    <div class="badge bg-primary-subtle text-primary rounded-3 fw-semibold fs-2">
                                        Created
                                        at
                                        : {{ $receive->purchase->created_at }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row d-none" id="editDataBox">
                    <div class="col-12">
                        <div class="card">
                            <div class="px-4 py-3 border-bottom">
                                <h5 class="card-title fw-semibold mb-0">Penerimaan Barang</h5>
                                <button id="btnCloseData" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-3"><i class="ti ti-x"></i></button>
                            </div>
                            <div class="card-body p-4">
                                
                                <div class="row">
                                    <div class="col-md-4 h-100 mb-3">
                                        @if($receive->image)
                                        <img id="now-image" src="{{ '/storage/'.$receive->image }}" alt="Receive Image Preview" class="rounded-4 shadow w-100"
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
                                        <img src="" id="preview-image" alt="Product Image Preview" class="d-none rounded-4 shadow w-100"
                                            style="">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card">
                                            <div class="px-4 py-3 border-bottom">
                                                <h5 class="card-title fw-semibold mb-0">Update Penerimaan Barang dari Pembelian</h5>
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
                                                <form action="{{ route('receive.update', $receive->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="mb-4">
                                                        <label class="form-label fw-semibold">Bukti Penerimaan</label>
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
                                                        <label class="form-label fw-semibold">Tanggal Penerimaan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                                    class="ti ti-text-caption fs-6"></i></span>
                                                            <input type="date" name="date" value="{{old('date', $receive->date ?? \Carbon\Carbon::now()->format('Y-m-d'))}}" class="form-control ps-2" placeholder="Tanggal Penerimaan">
                                                        </div>
                                                        @error('date')
                                                            <span class="invalid-feedback" role="alert">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    
                                                    <div class="p-3 rounded-3 bg-primary-subtle mb-2">
                                                        <label for="purchase_order_id" class="form-label">Purchase Order / Pembelian</label>
                                                        <div class="input-group mb-2">
                                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                                    class="ti ti-package fs-6"></i></span>
                                                            <div style="flex-grow:1">
                                                                <select name="purchase_order_id" id="purchase_order_id" class="select2-normal form-select">
                                                                    <option value="">-- Pilih Pembelian --</option>
                                                                    @foreach ($purchases as $purchase)
                                                                        <option value="{{$receive->purchase->id}}" {{ $receive->purchase->id == old('purchase_order_id', $receive->purchase->id) ? 'selected' : '' }}>
                                                                            {{ $receive->purchase->code }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <label for="warehouse_id" class="form-label">Gudang Penerima</label>
                                                        <div class="input-group mb-2">
                                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                                    class="ti ti-package fs-6"></i></span>
                                                            <div style="flex-grow:1">
                                                                <select name="warehouse_id" id="warehouse_id" class="select2-normal form-select">
                                                                    <option value="">-- Pilih Gudang --</option>
                                                                    @foreach ($warehouses as $warehouse)
                                                                        <option value="{{$warehouse->id}}" {{ $warehouse->id == old('warehouse_id', $receive->purchase->warehouse->id) ? 'selected' : '' }}>
                                                                            {{ $warehouse->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <label for="user_id" class="form-label">Penanggung Jawab</label>
                                                        <div class="input-group mb-2">
                                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                                    class="ti ti-package fs-6"></i></span>
                                                            <div style="flex-grow:1">
                                                                <select name="user_id" id="user" class="select2-normal form-select">
                                                                    <option value="">-- Pilih Penanggung Jawab --</option>
                                                                    <option value="{{auth()->user()->id}}" {{ auth()->user()->id == old('user_id', $receive->user_id) ? 'selected' : '' }}>
                                                                            {{ auth()->user()->name }} (Saya Sendiri)
                                                                        </option>
                                                                    @foreach ($users as $user)
                                                                        <option value="{{$user->id}}" {{ $user->id == old('user_id', $receive->user_id) ? 'selected' : '' }}>
                                                                            {{ $user->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-2">
                                                            Tidak menemukan Penanggung Jawab ? 
                                                            <a href="{{urlApp('SSO','/user/add')}}" target="_blank" class="btn btn-sm text-primary bg-primary-subtle"
                                                                >Tambah Penanggung Jawab</a>
                                                        </div>
                                                    </div>

                                                    <div class="mb-4">
                                                        <label class="form-label fw-semibold">Keterangan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                                    class="ti ti-align-justified fs-6"></i></span>
                                                            <textarea class="form-control ps-2" name="desc" id="desc" cols="20" rows="5"
                                                                placeholder="Description about this receivement">{{old('desc', $receive->description)}}</textarea>
                                                        </div>
                                                        @error('desc')
                                                            <span class="invalid-feedback" role="alert">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    
                                                    <button type="submit" class="btn btn-primary">
                                                        Perbarui Penerimaan Barang dari Pembelian
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @include('transports.addmodal',['id' => 'addPengangkutanModal'])

                    </div>
                </div>
            </div>

            @php
                $products = $receive->purchase->products;
                $totalPrice = 0;
            @endphp
            <!-- Form Step 2 -->
            <div class="step-content" data-step="2" style="display: none;">
                <div class="row">
                    <div class="col-12 mb-3 order-first">
                        <div class="d-flex mb-3 align-items-center gap-3">
                            <i class="ti ti-package fs-8"></i>
                            <h5 class="mb-0">Produk yang diterima</h5>
                            <div class="d-flex align-items-center justify-content-center bg-primary text-white p-2 rounded-circle"
                                style="aspect-ratio:1/1; width:2.5em; height:2.5em">
                                {{ count($products) }}
                            </div>
                        </div>
                        <form action="{{ route('receive.product.add', $receive->id) }}" method="POST"
                            class="d-block bg-white">
                            @csrf
                            <div class="border border-2 border-dashed border-dark-subtle rounded-4 p-3">
                                <p class="fs-2"><span class="text-danger">*</span><i>Perubahan tidak disimpan sampai
                                        anda menekan tombol Simpan</i></p>
                                @php $totalPrice = 0; @endphp
                                @forelse ($products as $product)
                                    @php
                                        $priceBuy = $product->price_buy ?? 0; // Harga jual (diambil dari cart)
                                        $subtotal = $priceBuy * $product->qty; // Hitung subtotal awal
                                    @endphp
                                    <div id="cart-item-{{ $product->id }}">
                                        <div
                                            class="d-flex align-items-center gap-2 mb-2 {{ $loop->index == 0 ? '' : 'mt-3' }}">
                                            <img src="{{ $product->product->image ? '/storage/' . $product->product->image : 'https://placehold.co/300?text=' . $product->product->name }}"
                                                alt="Image Product {{ $product->product->name }} in Cart" class="d-block rounded-2"
                                                style="width: 5em; height:5em; object-fit:cover">
                                            <div>
                                                <div class="mb-1">
                                                    <div class="d-inline-block p-1 px-2 rounded-2 bg-primary-subtle text-primary fs-2">{{ $product->product->type->type }}</div>
                                                </div>
                                                <div class="text-decoration-none text-dark fs-3 fw-semibold">
                                                    {{ $product->product->name }}</div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div><i class="ti ti-arrow-up"></i> {{ $product->product->height }} cm</div>
                                                    <div><i class="ti ti-arrow-right"></i> {{ $product->product->width }} cm</div>
                                                </div>
                                                <div class="text-muted fs-2 mb-2">
                                                    {{ $product->product->description ?? 'tidak ada deskripsi' }}</div>
                                            </div>
                                            <div
                                                class="flex-grow-1 d-flex flex-column align-items-end gap-2 justify-content-between">
                                                <div class="fs-2 fw-semibold">Sub Total</div>
                                                <div class="fs-3 fw-bold subtotal" id="subtotal_{{ $product->id }}">
                                                    {{ formatRupiah($subtotal) }}</div>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-end flex-wrap flex-sm-nowrap gap-2">
                                            <div class="flex-grow-1">
                                                <label for="qty" class="form-label mb-0 fs-2">Dibeli Sebanyak
                                                    (qty)</label>
                                                
                                                <div class="d-flex align-items-center gap-2">
                                                    @if (!($receive->is_lock || $product->remainingReceive <= 0))
                                                      <input type="hidden" name="product_id[]"
                                                        value="{{ $product->product->id }}">
                                                    @endif
                                                    <input type="number" name="qty[]" id="qty_{{ $product->id }}"
                                                        class="form-control qty-input"
                                                        data-id="{{ $product->id }}" value="{{ $product->qty }}"
                                                        min="1" {{$receive->purchase->status != 0 ? 'disabled' : ''}} />
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <label for="price_buy" class="form-label mb-0 fs-2">Harga Beli @ qty</label>
                                                <input type="text" name="price_buy[]"
                                                    id="price_buy_{{ $product->id }}"
                                                    class="form-control input-rupiah price-sale-input"
                                                    data-id="{{ $product->id }}" value="{{ formatRupiah($priceBuy) }}"
                                                    min="0" {{$receive->purchase->status != 0 ? 'disabled' : ''}} />
                                            </div>

                                        </div>
                                        <div class="d-flex align-items-start gap-2 mt-2">
                                            @php
                                                $receiveProduct = $receive->products()->where('purchase_product_id', $product->id)->first();   
                                            @endphp
                                            <div class="flex-grow-1">
                                                <label for="receive_qty" class="form-label mb-0 fs-2">Diterima Sebanyak
                                                    (qty)</label>
                                                
                                                <div class="d-flex align-items-center gap-2">
                                                    @if (!($receive->is_lock || $product->remainingReceive <= 0))
                                                      <input type="hidden" name="purchase_product_id[]"
                                                        value="{{ $product->id }}">
                                                    @endif
                                                    <input type="number" name="receive_qty[]" id="receive_qty_{{ $product->id }}"
                                                        class="form-control qty-input" value="{{ $receiveProduct ? $receiveProduct->receive_qty : 0 }}"
                                                        placeholder="Sisa {{ $product->remainingReceive }}" max="{{ $product->qty }}"
                                                        {{ $receive->is_lock || $product->remainingReceive <= 0 ? 'disabled' : '' }} />
                                                </div>
                                                <div class="fs-1 text-muted mt-1">Sisa {{ $product->remainingReceive }} qty</div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <label for="receive_qty" class="form-label mb-0 fs-2">Catatan / Keterangan Barang</label>
                                                <div class="d-flex align-items-center gap-2">
                                                   <textarea name="description[]" id="description_{{$product->id}}" placeholder="Berikan catatan / keterangan apabila diperlukan" cols="30" rows="3" class="form-control form-control-sm" {{ $receive->is_lock || $product->remainingReceive <= 0 ? 'disabled' : '' }}>{{$receiveProduct ? $receiveProduct->description : ''}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div
                                        class="text-center fs-3 p-5 border border-2 border-dashed border-primary rounded-4">
                                        Belum ada produk yang dipilih
                                    </div>
                                @endforelse
                                <div class="pt-3 mt-3 border-top border-2">
                                    <div class="mb-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" name="is_lock" type="checkbox" value="1" id="is_lock" checked {{ $receive->is_lock ? 'disabled' : '' }} />
                                            <label class="form-check-label" for="is_lock">{{ $receive->is_lock ? 'Penerimaan Dikunci' : 'Kunci Penerimaan (agar tidak bisa diedit)' }}</label>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 mt-2">
                                        <button type="submit" class="btn btn-primary w-100" {{$receive->is_lock ? 'disabled': ''}}>{{ $receive->is_lock ? 'Telah disimpan' : 'Simpan dan Kunci Penerimaan Barang' }}</button>
                                    </div>
                                    <a href="#bal" class="btn bg-primary-subtle text-primary d-block my-1">Produk diterima ada yang dibungkus menjadi Bal</a>
                                    <div class="fs-1"><i><span class="text-danger me-2">*</span> Jika diterima dalam bentuk bal, jangan lupa menambahkan Data Bal dan kaitkan ke pesanan ini</i></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Form Step 3 --}}
            <div class="step-content" data-step="3" style="display: none;">
                <div class="row">
                    <div class="col-12 mb-3 order-first">
                        <div class="d-flex mb-3 align-items-center gap-3">
                            <i class="ti ti-circles fs-8"></i>
                            <h5 class="mb-0">Diterima dalam Bentuk Bal</h5>
                            <div class="d-flex align-items-center justify-content-center bg-primary text-white p-2 rounded-circle"
                                style="aspect-ratio:1/1; width:2.5em; height:2.5em">
                                {{ count($receive->purchase->bals ?? []) }}
                            </div>

                            <a href="{{route('bal.add', ['purchase_order' => $receive->purchase->id,'desc' => 'Bal dibuat dari Penerimaan '.$receive->code, 'nowin_id' => $receive->warehouse_id, 'receive' => $receive->id])}}" target="_blank" class="btn btn-primary ms-auto"><i class="ti ti-plus me-2"></i>Tambah</a>
                        </div>
                        @forelse ($receive->bals as $bal)
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $bal->image ? asset('/storage/'.$bal->image) : '/assets/images/profile/user-1.jpg' }}"
                                            class="rounded-2" alt="product Image {{ $bal->name }}" style="width: 4em" />
                                        <div class="ms-3">
                                            <a href="{{route('bal.index', ['search' => $bal->code])}}" target="_blank" class="d-block">
                                                <div class="badge bg-primary fs-1 mb-1 text-white">{{ $bal->code }}</div>
                                                <h6 class="fw-semibold mb-1" style="white-space: normal !important">{{ $bal->name }}</h6>
                                                <div class="fw-normal" style="white-space:normal; font-size:13px; ">{{ $bal->description ?? 'Tidak ada deskripsi'}}</div>    
                                            </a>
                                        </div>

                                        <div class="ms-auto">
                                            @if($bal->is_unpack && $bal->unpack)
                                                <button type="button"
                                                    class="dropdown-item fs-2 text-center d-inline-flex p-2 px-3 align-items-center gap-2 bg-secondary text-white rounded-3"
                                                    data-bs-toggle="modal" data-bs-target="#unpackModal-{{$bal->id}}"><i
                                                        class="fs-4 ti ti-circles"></i> Detail Pembongkaran</button>

                                                <!-- List Product modal -->
                                                <div class="modal fade " id="unpackModal-{{$bal->id}}" tabindex="-1"
                                                    aria-labelledby="vertical-center-modal" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                                        <div class="modal-content">
                                                            <div class="modal-header d-flex align-items-center">
                                                                <h4 class="modal-title" id="myLargeModalLabel">
                                                                    Detail Pembongkaran Bal
                                                                </h4>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body pt-0">
                                                                <img src="{{'/storage/'.$bal->unpack->image}}" target="_blank" class="mb-2 d-block rounded-3 w-full w-100" />
                                                                <div>
                                                                    <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Dibongkar Pada</div>
                                                                    <h6 class="fs-2 fw-semibold text-success mb-1" style="">
                                                                        {{ \Carbon\Carbon::parse($bal->unpack->updated_at)->format('d F Y H:i:s') }}</h6>
                                                                </div>
                                                                <div>
                                                                    <div class="fw-normal fs-1 text-muted" style="">Pembongkar
                                                                    </div>
                                                                    <div class=""><i class="ti ti-user-circle me-2"></i> {{$bal->unpack->user->name}}</div>
                                                                </div>
                                                                <div>
                                                                    <div class="fw-normal fs-1 text-muted" style="">Deskripsi
                                                                    </div>
                                                                    <div class="fs-3">{{$bal->unpack->description}}</div>
                                                                </div>
                                                                <div class="mt-3 d-flex gap-2 justify-content-center align-items-center">
                                                                    <a href="{{route('bal.unpack.edit', ['id' => $bal->id, 'unpack_id' => $bal->unpack->id])}}" class="btn w-100 w-full btn-secondary"><i class="ti ti-edit me-1"></i> Edit</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <a href="{{route('bal.unpack.add', $bal->id)}}" class="btn btn-primary-subtle bg-primary-subtle btn-sm d-block">Bongkar</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-5 rounded-3 bg-light text-center border-2 border-dashed">
                                Belum ada Bal yang ditambahkan untuk Pemesanan {{ $receive->purchase->code }}
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        
    </div>
@endsection
@section('scripts')
    <script src="{{ env('APP_URL') }}/assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="{{ env('APP_URL') }}/assets/libs/select2/dist/js/select2.min.js"></script>
    <script>
        document.getElementById('receiveForm').addEventListener('submit', function () {
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('loadingState').style.display = 'block';
        });
    </script>
    <script>
        $(document).ready(function() {
            const stepMap = {
                'data': 1,
                'produk': 2,
                'bal': 3,
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
        $(document).ready(function() {
            $('.select2').each(function() {
                let modal = $(this).closest('.modal'); // Cari modal terdekat
                $(this).select2({
                    dropdownParent: modal // Pasang dropdown di dalam modal
                });
            });
            $('.select2-normal').each(function() {
                $(this).select2();
            });

            $('.remove-cart').on('click', function(e) {
                e.preventDefault();

                var url = $(this).data('url');
                var productId = $(this).data('product-id');
                var reqorderId = $(this).data('reqorder-id');

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        product_id: productId
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            $('#refetchBtn').on('click', function(e) {
                e.preventDefault();
                let id = "{{ $receive->purchase->id }}"
                $.ajax({
                    url: "{{ route('purchase-order.refetch', $receive->purchase->id) }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            $('#btnEditData').on('click', function(){
                $('#showDataBox').addClass('d-none');
                $('#editDataBox').removeClass('d-none');
            });

            $('#btnCloseData').on('click', function(){
                $('#showDataBox').removeClass('d-none');
                $('#editDataBox').addClass('d-none');
            });
        });

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
                    const nowImage = formContainer.querySelector('#now-image');
                    const previewImage = formContainer.querySelector('#preview-image');
                    const previewFileLink = formContainer.querySelector('#preview-file-link');
                    const previewFileEmbed = formContainer.querySelector('#preview-file-embed');
                    const previewFileName = formContainer.querySelector('#preview-file-text');

                    const file = fileInput.files[0];

                    if (file) {
                        if (file.type.startsWith('image/')) {
                            // Preview image
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                previewImage.src = e.target.result;
                                previewImage.classList.remove('d-none'); // Show image preview
                                nowImage.classList.add('d-none'); // Hide now image
                            };
                            reader.readAsDataURL(file);
                        } else {
                            // Preview file
                            previewFileName.textContent = file.name;
                            previewFileEmbed.src = URL.createObjectURL(file); // Temporary file link
                            previewFile.classList.remove('d-none'); // Show file preview
                            previewImage.classList.add('d-none'); // Hide image preview
                        }
                    } else {
                        // Reset previews
                        previewImage.src = '';
                        previewImage.classList.add('d-none');
                        nowImage.classList.remove('d-none'); // Show placeholder
                    }
                }
            });

            $('input[name="name"]').on('input', function() {
                const name = $(this).val();
                $('input[name="slug"]').val(makeSlug(name));
            });

            $('input[name="price"]').on('input', function() {
                $(this).val(formatRupiah($(this).val()));
            })

            // -----------------------
            // Product
            // -----------------------
            function cleanRupiah(value) {
                return parseFloat(value.replace(/[^\d]/g, '') || 0);
            }

            function updateSubtotalAndTotal() {
                let total = 0;

                document.querySelectorAll('.qty-input').forEach(input => {
                    let id = input.dataset.id;
                    let qty = parseInt(input.value) || 1;
                    let priceBuyInput = document.getElementById(`price_buy_${id}`);

                    if (!priceBuyInput) return; // Cegah error jika elemen tidak ditemukan

                    let priceBuy = cleanRupiah(priceBuyInput.value);
                    let subtotal = priceBuy * qty;

                    document.getElementById(`subtotal_${id}`).textContent = formatRupiah(subtotal);
                    total += subtotal;
                });

                document.getElementById('totalPrice').textContent = formatRupiah(total);
                document.getElementById('totalPriceInput').value = total;

                updateTotalWithTax(); // Pastikan pajak diperbarui setiap subtotal berubah
            }

            function updateTotalWithTax() {
                let total = parseFloat(document.getElementById('totalPriceInput').value) || 0;
                let taxInput = document.querySelector('#tax');
                
                if (!taxInput) return; // Cegah error jika input pajak tidak ada

                let taxValue = parseFloat(taxInput.value) || 0;
                let grandTotal = Math.round(total + (total * taxValue / 100));

                document.getElementById('totalPriceTaxed').textContent = formatRupiah(grandTotal);
                document.getElementById('totalPriceTaxedInput').value = grandTotal;
            }

            // Event listener untuk qty dan price_buy
            document.querySelectorAll('.qty-input, .price-sale-input').forEach(input => {
                input.addEventListener('input', updateSubtotalAndTotal);
            });

            $('#tax').on('input',updateTotalWithTax)

            // Event listener untuk memastikan format harga tetap dalam format rupiah setelah diedit
            document.querySelectorAll('.price-sale-input').forEach(input => {
                input.addEventListener('blur', function() {
                    let value = cleanRupiah(this.value);
                    this.value = formatRupiah(value);
                });
            });

            // Jalankan update awal saat halaman dimuat
            updateSubtotalAndTotal();
        });

        $('input[name="is_handle_logistic"]').on('change', function(){
            let check = $(this).is(':checked');
            if(check) {
                $('#logistic_input').removeClass('d-none');
            }else{
                $('#logistic_input').addClass('d-none');
            }
        })

        $('input[name="is_for_client"]').on('change', function(){
            let check = $(this).is(':checked');
            if(check) {
                $('#client_input').removeClass('d-none');
            }else{
                $('#client_input').addClass('d-none');
            }
        })
    </script>
@endsection
