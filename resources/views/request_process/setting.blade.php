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
                    <h4 class="fw-semibold mb-8">Kelola Pemrosesan Permintaan Client : {{ $process->code }}</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('request-process.index') }}">Pemrosesan Permintaan Client</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Kelola Pemrosesan Permintaan Client : {{ $process->code }}
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
                
                @if($process->status != 2)
                <button type="button" class="btn p-2 px-3 d-flex {{$process->status == 0 ? 'btn-warning' : 'btn-success'}} align-items-center gap-2"
                            data-bs-toggle="modal" data-bs-target="#processModal-{{$process->id}}"><i
                                class="fs-4 ti {{$process->status == 0 ? 'ti-loader-3' : 'ti-check'}}"></i><span class="d-none d-sm-block">{{$process->status == 0 ? 'Proses' : 'Selesaikan'}}</span></button>
                <!-- Process Modal -->
                <div class="modal fade" id="processModal-{{$process->id}}" tabindex="-1"
                    aria-labelledby="vertical-center-modal" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header d-flex align-items-center">
                                <h4 class="modal-title" id="myLargeModalLabel" style="white-space: normal">
                                    {{$process->status == 0 ? 'Proses Permintaan Client' : 'Selesaikan Permintaan Client'}} {{$process->code}}
                                </h4>
                                <button type="button" class="btn-close mb-auto" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body pt-0">
                                <form action="{{ route('request-process.process', $process->id) }}" method="POST">
                                    @csrf
                                    <p class="text-muted" style="white-space: normal">Pastikan Keadaan lapangan sudah sesuai, dan dapat dipertanggung jawabkan dengan baik untuk <strong>{{$process->status == 0 ? 'Proses Permintaan Client' : 'Selesaikan Permintaan Client'}} dengan kode {{$process->code}}</strong></p>
                                    <div class="d-flex gap-1 align-items-center justify-content-end">
                                        <button type="submit"
                                            class="btn {{$process->status == 0 ? 'btn-warning' : 'btn-success'}}">{{$process->status == 0 ? 'Proses Permintaan Client' : 'Selesaikan Permintaan Client'}}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @php
                    $status = [
                        '0' => ['label' => 'Pending','color' => 'secondary'],
                        '1' => ['label' => 'Diproses','color' => 'warning',],
                        '2' => ['label' => 'Selesai','color' => 'success'],
                    ];
                @endphp
                <div>
                    <div class="fw-normal fs-1 text-muted" style="">Status Proses Permintaan
                    </div>
                    <h6 class="fw-semibold fs-2 text-{{ $status[$process->status]['color'] }} mb-1" style="">{{ $status[$process->status]['label'] }}</h6>
                </div>
            </div>
            <div class="col-md-6 order-first order-md-0 d-flex align-items-start gap-2 flex-wrap">
                <div class="stepper flex-grow-1 overflow-auto">
                    <div class="step active" data-step="1">
                        <div class="circle">1</div>
                        <div class="label fs-2">Data Pemrosesan</div>
                        <div class="line"></div>
                    </div>
                    <div class="step" data-step="2">
                        <div class="circle">2</div>
                        <div class="label fs-2">Produk diproses</div>
                        <div class="line"></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-4 d-flex justify-content-end align-items-start gap-2">
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteRequestOrder-{{$process->id}}"><i class="ti ti-trash"></i> <span class="ms-1 d-none d-md-inline-block">Hapus</span></button>
                <button onclick="seePDF('pdf.po','{{$process->id}}')" class="btn btn-danger" style="background:rgb(186, 55, 55); border-color:rgb(186, 55, 55)"><i class="ti ti-printer me-2"></i><span class="d-none d-sm-inline-block">Cetak</span> PO</button>
            </div>

            <!-- Delete Modal -->
            <div id="deleteRequestOrder-{{$process->id}}" class="modal fade" tabindex="-1"
                aria-labelledby="danger-header-modalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                    <div class="modal-content p-3 modal-filled bg-danger">
                        <div class="modal-header modal-colored-header text-white">
                            <h4 class="modal-title text-white" id="danger-header-modalLabel">
                                Yakin ingin menghapus Pemrosesan Permintaan Client (Purchase Order) {{$process->code}} ?
                            </h4>
                            <button type="button" class="btn-close btn-close-white mb-auto"
                                data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="width: fit-content; white-space:normal">
                            <h5 class="mt-0 text-white">Pemrosesan Permintaan Client (Purchase Order) {{$process->code}} akan dihapus</h5>
                            <p class="text-white">Segala data yang berkaitan dengan Request Order tersebut juga akan dihapus secara permanen. Penghapusan dapat dilakukan jika tidak ada Transaksi penting yang terkait.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                Close
                            </button>
                            <form action="{{route('request-process.destroy', $process->id)}}" method="POST">
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
                                <h6>Pemrosesan Permintaan Client</h6>
                                <div>
                                    <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Tanggal</div>
                                    <h6 class="fs-2 fw-semibold text-success mb-1" style="">
                                        {{ \Carbon\Carbon::parse($process->date)->format('d F Y') }}</h6>
                                </div>
                                <div>
                                    <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kode Permintaan Client
                                    </div>
                                    <h6 class="fw-semibold text-primary mb-1" style="">{{ $process->code }}</h6>
                                </div>
                                @php
                                    $status = [
                                        '0' => ['label' => 'Pending','color' => 'secondary'],
                                        '1' => ['label' => 'Diproses','color' => 'warning',],
                                        '2' => ['label' => 'Selesai','color' => 'success'],
                                    ];
                                @endphp
                                <div>
                                    <div class="fw-normal fs-1 text-muted" style="">Status Permintaan
                                    </div>
                                    <h6 class="fw-semibold fs-2 text-{{ $status[$process->status]['color'] }} mb-1" style="">{{ $status[$process->status]['label'] }}</h6>
                                </div>
                                <div style="min-width: 10em">
                                    <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Deksripsi</div>
                                    <p class="mb-1 fs-2" style="white-space:normal !important;">{{ $process->description ?? 'tidak ada deskripsi' }}</p>
                                </div>
                                <hr>

                                @if( $process->requestOrder )
                                <h6>Permintaan Client (Request Order)</h6>
                                <div>
                                    <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Tanggal</div>
                                    <h6 class="fs-2 fw-semibold text-success mb-1" style="">
                                        {{ \Carbon\Carbon::parse($process->requestOrder->date)->format('d F Y') }}</h6>
                                </div>
                                <div>
                                    <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kode Permintaan
                                    </div>
                                    <a href="{{ route('request-order.setting', $process->requestOrder->id) }}" target="_blank"><h6 class="fw-semibold text-primary mb-1" style="">{{ $process->requestOrder->code }} <i class="ti ti-external-link"></i></h6></a>
                                </div>
                                <div>
                                    <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Referensi
                                        Permintaan (PO)</div>
                                    <a href="{{ $process->requestOrder->attachment ? '/storage/'.$process->requestOrder->attachment : '#' }}" class="badge fs-2 mb-1 bg-primary-subtle text-primary border-primary">
                                        <i class="ti ti-file"></i>
                                        {{ $process->requestOrder->attachment ? $process->requestOrder->no_refrence : 'Tidak ada Lampiran'}}
                                    </a>
                                </div>
                                @else
                                <div class="fs-2">Tidak berdasarkan Permintaan Client</div>
                                @endif
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="">
                                    <h6>Dengan Detail Logistik / Pengangkutan</h6>
                                    @if( $process->tarnsport )
                                    <a href="{{route('logistic.setting', $process->transport->logistic->id)}}" target="_blank" class="border border-primary p-1 px-2 rounded-2 d-inline-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-truck-delivery mb-0 fs-3"></i> {{ $process->transport->logistic->name }}
                                    </a>
                                    <a href="javascript:void(0);" title="Klik untuk melihat detail" class="d-flex text-secondary align-items-center fs-2 mb-1 gap-2"
                                        data-bs-toggle="modal" data-bs-target="#detailDelivery-{{$process->id}}"
                                    >
                                        <i class="ti ti-exchange mb-0 fs-3"></i> Detail Antar Jemput
                                    </a>
                                    <!-- List Product modal -->
                                    <div class="modal fade " id="detailDelivery-{{$process->id}}" tabindex="-1"
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
                                                            <div class="fs-3">{{$process->pickup->address.', '.$process->pickup->city.'. '.$process->pickup->postal_code}}</div>
                                                        </div>
                                                    </div>
                                                    <div class="p-3 rounded-3 border border-dashed border-primary">
                                                        <div class="fs-2 text-muted">Pengantaran Barang</div>
                                                        <div>
                                                            <div class="fs-3">{{$process->delivery->address.', '.$process->delivery->city.'. '.$process->delivery->postal_code}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fw-normal fs-1 text-muted" style="">Contact Person</div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-user-circle mb-0 fs-3"></i> {{ $process->transport->logistic->cp_name }}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-mail mb-0 fs-3"></i> {{ $process->transport->logistic->cp_email ?? '-' }}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-phone mb-0 fs-3"></i> {{ $process->transport->logistic->cp_phone ?? '-' }}
                                    </div>
                                    @else
                                    <div class="fs-2">Diurus Oleh Principal</div>
                                    @endif
                                </div>
                                <hr>
                                <h6>Kalkulasi Pembayaran</h6>
                                @if($process->products->count() > 0)
                                <div>
                                    <div class="fw-normal fs-1 text-muted" style="">Total Nilai Awal</div>
                                    <h6 class="fw-semibold fs-2 text-primary mb-1" style="">{{ formatRupiah($process->total_price) }}</h6>
                                </div>
                                <div>
                                    <div class="fw-normal fs-1 text-muted" style="">Pajak</div>
                                    <h6 class="fw-semibold fs-2 text-primary mb-1" style="">{{ $process->tax ?? '11' }}%</h6>
                                </div>
                                <div>
                                    <div class="fw-normal fs-1 text-muted" style="">Total Dengan Pajak</div>
                                    {{-- @php
                                        dd($process->total_price, $process->tax, $process->tax && (int)$process->tax != '0' ? $process->tax : 11, ));
                                    @endphp --}}
                                    <h6 class="fw-semibold fs-2 text-primary mb-1">
                                        {{ 
                                            (int) $process->total_price_taxed && (int) $process->total_price_taxed != 0 
                                                ? formatRupiah($process->total_price_taxed) 
                                                : formatRupiah((int) $process->total_price + ((int) $process->total_price * (($process->tax && (int) $process->tax != 0 ? $process->tax : 11) / 100)))
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
                                        : {{ $process->updated_at }}</div>
                                    <div class="badge bg-primary-subtle text-primary rounded-3 fw-semibold fs-2">
                                        Created
                                        at
                                        : {{ $process->created_at }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row d-none" id="editDataBox">
                    <div class="col-12">
                        <div class="card">
                            <div class="px-4 py-3 border-bottom">
                                <h5 class="card-title fw-semibold mb-0">Pemrosesan Permintaan Client</h5>
                                <button id="btnCloseData" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-3"><i class="ti ti-x"></i></button>
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
                                <form action="{{ route('request-process.update', $process->id)}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Tanggal Permintaan Client</label>
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
                                        <label class="form-label fw-semibold">Proses Permintaan Client</label>
                                        <div class="input-group">
                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                    class="ti ti-checklist fs-6"></i></span>
                                            <div style="flex-grow:1">
                                                <select name="request_order_id" id="request_order_id" class="select2-normal form-select">
                                                    <option value="">-- Pilih Permintaan Client --</option>
                                                    @foreach ($reqorders as $reqorder)
                                                        <option value="{{$reqorder->id}}" {{$reqorder->id == old('request_order_id', request()->get('reqorder_id')) ? 'selected' : ''}}>{{$reqorder->code.' - '.\Carbon\Carbon::parse($reqorder->date)->format('d M Y')}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @error('request_order_id')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Penanggung Jawab Proses</label>
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
                                    
                                    <div class="mb-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" name="is_handle_logistic" type="checkbox" value="1" id="is_handle_logistic" />
                                            <label class="form-check-label" for="is_handle_logistic">Perusahaan mengurus Logistik / Pengangkutan</label>
                                        </div>
                                    </div>
                                    <div class="p-3 bg-primary-subtle rounded-3 mb-4 d-none" id="logistic_input">
                                        <label class="form-label fw-semibold">Logistik Diurus Sesuai Data Angkut</label>
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

                                        <div class="d-flex align-items-center gap-2 mt-2">
                                            Tidak menemukan Pengangkutan yang sesuai ? 
                                            <button type="button" class="btn btn-sm text-primary bg-primary-subtle"
                                                data-bs-toggle="modal"
                                                data-bs-target="#addPengangkutanModal"
                                            >Tambah Pengangkutan</button>
                                        </div>

                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Deskripsi</label>
                                        <div class="input-group">
                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                    class="ti ti-align-justified fs-6"></i></span>
                                            <textarea class="form-control ps-2" name="description" id="description" cols="20" rows="5"
                                                placeholder="Deskripsi untuk Pemrosesan Permintaan Client">{{old('description')}}</textarea>
                                        </div>
                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        Simpan dan Lanjut ke Produk Diproses
                                    </button>
                                </form>
                            </div>
                        </div>

                        @include('transports.addmodal',['id' => 'addPengangkutanModal'])

                    </div>
                </div>
            </div>

            @php
                $carts = session()->get('cart_' . $process->id, []);
                $totalPrice = 0;
            @endphp
            <!-- Form Step 2 -->
            <div class="step-content" data-step="2" style="display: none;">
                <div class="row">
                    <div class="col-md-5">
                        <div>
                            <form action="{{ url()->current() }}" method="GET" class="w-100">
                                <input type="hidden" name="hashProduct" value="1">
                                <div class="row align-items-end mb-3 flex-wrap">
                                    <div class="col-md-8 mb-2 flex-grow-1">
                                        <label for="search" class="form-label">Menampilkan Produk {{ $process->requestOrder ? 'yang Diminta' : ''}}</label>
                                        <input type="text" class="form-control" placeholder="Cari Produk"
                                            name="search" value="{{ $filter->q ?? '' }}">
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="d-flex align-items-center gap-1">
                                            <button type="submit" class="btn btn-primary w-100"
                                                style="white-space: nowrap">Apply</button>
                                            <a href="{{ url()->current() }}" class="btn btn-secondary"
                                                style="white-space: nowrap">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em"
                                                    viewBox="0 0 24 24">
                                                    <path fill="currentColor"
                                                        d="M22 12c0 5.523-4.477 10-10 10S2 17.523 2 12S6.477 2 12 2v2a8 8 0 1 0 4.5 1.385V8h-2V2h6v2H18a9.99 9.99 0 0 1 4 8" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @php
                        @endphp
                        <div class="row">
                            @foreach ($products as $item)
                                @php
                                    if($process->requestOrder)
                                        $product = $item->product;
                                @endphp
                                <div class="col-6 mb-2">
                                    <div class="card rounded-4 h-100 overflow-hidden">
                                        <div class="card-body p-0 h-100 d-flex flex-column">
                                            <img src="{{ $product->image ? '/storage/' . $product->image : 'https://placehold.co/160x90?text=' . $product->name }}"
                                                alt="Image {{ $product->name }}"
                                                class="d-block w-100 mb-2 bg-primary-subtle"
                                                style="aspect-ratio:16/9; object-fit:contain;">
                                            <div class="p-1 h-100 d-flex flex-column justify-content-between px-3">
                                                <div class="mb-1">
                                                    <div class="d-inline-block p-1 px-2 rounded-2 bg-primary-subtle text-primary fs-2">{{ $product->type->type }}</div>
                                                </div>
                                                <div class="text-decoration-none text-dark fs-3 fw-semibold">
                                                    {{ $product->name }}</div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div><i class="ti ti-arrow-up"></i> {{ $product->height }} cm</div>
                                                    <div><i class="ti ti-arrow-right"></i> {{ $product->width }} cm</div>
                                                </div>
                                                <div class="text-muted fs-2 mb-2">
                                                    {{ $product->description ?? 'tidak ada deskripsi' }}</div>
                                                
                                                <div class="mt-1 fs-2">
                                                    <i class="ti ti-checklist"></i> {{$item->qty}} qty {{ $product->type->type == 'meteran' ? 'x '.$item->length.' cm': ''}}
                                                </div>
                                                <div class="d-flex mt-auto align-items-center gap-2 mb-3">
                                                    <button type="submit"
                                                        class="{{ isset($carts[$product->id]) ? '' : 'd-none' }} fs-3 px-3 w-100 text-center justify-content-center btn-sm btn btn-secondary-subtle rounded-2 d-flex align-items-center gap-2"
                                                        disabled>
                                                        <i class="ti ti-package-off"></i>
                                                        <span class="fs-2 w-100" style="white-space: nowrap">Sudah Ada</span>
                                                    </button>
                                                    <form action="{{ route('request-process.addCart', $process->id) }}"
                                                        method="POST"
                                                        class="{{ isset($carts[$product->id]) ? 'd-none' : '' }}"
                                                        style="flex-grow: 1; width:100%">
                                                        @csrf
                                                        <input type="hidden" name="product_id"
                                                            value="{{ $product->id }}">
                                                        <button type="submit"
                                                            class="fs-3 px-3 w-100 text-center justify-content-center btn-sm btn btn-primary rounded-2 d-flex align-items-center gap-2"
                                                            style="width: 100%;"
                                                            {{ $process->status != 0 ? 'disabled' : '' }}
                                                            >
                                                            <i class="ti ti-packge-export"></i>
                                                            <span class="fs-2">
                                                            {{
                                                                $process->status != 0 
                                                                ? 'Sudah Diproses' : 'Pilih'
                                                            }}
                                                            </span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @include('partials.paginate',['datas' => $products])
                        </div>
                    </div>
                    <div class="col-lg-7 mb-3 order-first">
                        <div class="d-flex mb-3 align-items-center gap-3">
                            <i class="ti ti-package fs-8"></i>
                            <h5 class="mb-0">Produk yang diproses</h5>
                            <div class="d-flex align-items-center justify-content-center bg-primary text-white p-2 rounded-circle"
                                style="aspect-ratio:1/1; width:2.5em; height:2.5em">
                                {{ count($carts) }}
                            </div>
                        </div>
                        <form action="{{ route('request-process.product.store', $process->id) }}" method="POST"
                            class="d-block bg-white">
                            @csrf
                            <div class="border border-2 border-dashed border-dark-subtle rounded-4 p-3">
                                <p class="fs-2"><span class="text-danger">*</span><i>Perubahan tidak disimpan sampai
                                        anda menekan tombol Simpan</i></p>
                                
                                @forelse ($carts as $item)
                                    @php
                                        $cart = \App\Models\Product::find($item['id']);
                                        $reqproduct = \App\Models\RequestOrderProduct::where('request_order_id', $process->requestOrder->id)->where('product_id', $item['id'])->first();
                                        $procproduct = \App\Models\RequestProcessProduct::where('request_process_id', $process->id)->where('product_id', $item['id'])->first();
                                    @endphp
                                    <div id="cart-item-{{ $item['id'] }}">
                                        <div
                                            class="d-flex align-items-center gap-2 mb-2 {{ $loop->index == 0 ? '' : 'mt-3' }}">
                                            <img src="{{ $cart->image ? '/storage/' . $cart->image : 'https://placehold.co/300?text=' . $cart->name }}"
                                                alt="Image Product {{ $cart->name }} in Cart" class="d-block rounded-2"
                                                style="width: 5em; height:5em; object-fit:cover">
                                            <div>
                                                <div class="mb-1">
                                                    <div class="d-inline-block p-1 px-2 rounded-2 bg-primary-subtle text-primary fs-1">{{ $cart->type->type }}</div>
                                                </div>
                                                <div class="text-decoration-none text-dark fs-3 fw-semibold">
                                                    {{ $cart->name }}</div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div><i class="ti ti-arrow-up"></i> {{ $cart->height }} cm</div>
                                                    <div><i class="ti ti-arrow-right"></i> {{ $cart->width }} cm</div>
                                                </div>
                                                <div class="text-muted fs-2 mb-2">
                                                    {{ $cart->description ?? 'tidak ada deskripsi' }}</div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-end flex-wrap flex-sm-nowrap gap-2">
                                            <div class="flex-grow-1">
                                                <label for="qty" class="form-label mb-0 fs-2">Dipesan Sebanyak
                                                    (qty)</label>
                                                <div class="d-flex align-items-center gap-2">
                                                    <input type="hidden" name="product_id[]"
                                                        value="{{ $item['id'] }}">
                                                    <input type="number"
                                                        class="form-control qty-input"
                                                        data-id="{{ $item['id'] }}" value="{{ $procproduct ? $procproduct->qty+$procproduct->remaining_qty : $reqproduct->qty }}"
                                                        min="1" disabled />
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <label for="price_buy" class="form-label mb-0 fs-2">Proses Sebanyak ({{ $cart->type->type == 'meteran' ? $reqproduct->length.' x ' : '' }}qty)</label>
                                                <div class="fs-2 mb-1 fw-semibold text-muted">{{ $procproduct->remaining_qty ?? $reqproduct->remaining_qty }} qty belum diproses</div>
                                                <input type="number" name="process_qty[]" id="process_qty_{{ $item['id'] }}"
                                                        class="form-control process_qty-input"
                                                        data-id="{{ $item['id'] }}" max="{{ $procproduct->remaining_qty ?? $reqproduct->remaining_qty }}" value="{{ $procproduct->qty ?? 1 }}"
                                                        min="1" {{$process->status != 0 ? 'disabled' : ''}} />
                                            </div>
                                            <div class="flex-grow-1">
                                                <label class="form-label mb-0 fs-2 fw-semibold">Ambil Barang Dari</label>
                                                <div style="max-width: 20em">
                                                    <select name="from_id[]" id="from_id-{{$item['id']}}" class="select2-normal form-select" style="white-space:normal !important">
                                                        <option value="">-- Pilih Gudang / Toko --</option>
                                                        @foreach ($warehouses as $warehouse)
                                                            @if((int) $warehouse->getProductRemaining($item['id'] > 0))
                                                                <option value="{{$warehouse->id}}" {{$warehouse->id == old('from_id', $procproduct?->from_id) ? 'selected' : ''}} style="white-space:normal !important">{{ "[Gudang] | Remaining : ".$warehouse->getProductRemaining($item['id']) ." qty | {$warehouse->name}, {$warehouse->address} - {$warehouse->city}. {$warehouse->postal_code}" }}</option>
                                                            @endif
                                                        @endforeach
                                                        @foreach ($stores as $store)
                                                            @if((int) $store->getProductRemaining($item['id'] > 0))
                                                                <option value="{{$store->id}}" {{$store->id == old('from_id', $procproduct?->from_id) ? 'selected' : ''}} style="white-space:normal !important">{{ "[Toko] | Remaining : ".$store->getProductRemaining($item['id']) ." qty | {$store->name}, {$store->address} - {$store->city}. {$store->postal_code}" }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('from_id')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>

                                            <button class="btn btn-sm btn-danger remove-cart"
                                                data-url="{{ route('request-process.removeCart', $process->id) }}"
                                                data-product-id="{{ $item['id'] }}"
                                                data-reqorder-id="{{ $process->id }}" {{$process->status != 0 ? 'disabled' : ''}}>
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <div
                                        class="text-center fs-3 p-5 border border-2 border-dashed border-primary rounded-4">
                                        Belum ada produk yang dipilih
                                    </div>
                                @endforelse
                                <div class="pt-3 mt-3 border-top border-2">
                                    <div class="d-flex align-items-center gap-2 mt-2">
                                        <button type="button" id="refetchBtn" class="btn btn-secondary {{ count($carts) == 0 ? 'd-none' : ''}}"
                                            title="Muat Ulang Pemrosesan Produk"><i class="ti ti-refresh"></i></button>
                                        <button type="submit" class="btn btn-primary w-100" {{$process->status != 0 ? 'disabled' : ''}}>Simpan dan Lanjut <span class="d-none d-md-inline-block">ke
                                            Lampiran</span></button>
                                    </div>
                                </div>
                            </div>
                        </form>
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
        $(document).ready(function() {
            const stepMap = {
                'data': 1,
                'produk': 2,
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
                let id = "{{ $process->id }}"
                $.ajax({
                    url: "{{ route('request-process.refetch', $process->id) }}",
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

            let purchase = @json($process);
            console.log(purchase);
            if (purchase.id) {
                $('#principal_id').val(purchase.principal_id).trigger('change');
                $('#request_order_id').val(purchase.request_order_id).trigger('change');
                setTimeout(() => {
                    $('#principal_pic_id').val(purchase.principal_pic_id).trigger('change'); // Pilih PIC sesuai dengan purchase
                }, 500);
                setTimeout(() => {
                    $('#pickup_address_id').val(purchase.pickup_address_id).trigger('change'); // Pilih PIC sesuai dengan purchase
                }, 1000);
                setTimeout(() => {
                    $('#delivery_address_id').val(purchase.delivery_address_id).trigger('change'); // Pilih PIC sesuai dengan purchase
                }, 1500);
            }

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
                            reader.onload = function(e) {
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
