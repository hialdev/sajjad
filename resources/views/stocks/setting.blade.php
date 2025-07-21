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
                    <h4 class="fw-semibold mb-8">Kelola Distribusi Stok / Barang : {{ $move->code }}</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('receive.index') }}">Distribusi Stok / Barang</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Kelola Distribusi Stok / Barang : {{ $move->code }}
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
                @if($move->status != 2)
                <button type="button" class="btn p-2 px-3 d-flex btn-{{$move->status < 1 ? 'warning' : 'success'}} align-items-center gap-2"
                            data-bs-toggle="modal" data-bs-target="#processModal-{{$move->id}}"><i
                                class="fs-4 ti ti-{{$move->status < 1 ? 'atom-2' : 'check'}}"></i><span class="d-none d-sm-block">{{$move->status < 1 ? 'Proses' : 'Selesaikan'}}</span></button>
                <!-- Process Modal -->
                <div class="modal fade" id="processModal-{{$move->id}}" tabindex="-1"
                    aria-labelledby="vertical-center-modal" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header d-flex align-items-center">
                                <h4 class="modal-title" id="myLargeModalLabel" style="white-space: normal">
                                    {{$move->status < 1 ? 'Proses' : 'Selesaikan'}} Distribusi {{$move->code}}
                                </h4>
                                <button type="button" class="btn-close mb-auto" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body pt-0">
                                <form id="receiveForm" action="{{ route('stock.process', $move->id) }}" method="POST">
                                    @csrf
                                    <p class="text-muted" style="white-space: normal">
                                        Pastikan Keadaan lapangan sudah sesuai, dan dapat dipertanggung jawabkan dengan baik untuk
                                        <strong>{{$move->status < 1 ? 'Proses' : 'Selesaikan'}} Distribusi {{$move->products->count()}} Barang</strong>
                                    </p>

                                    <div class="d-flex gap-1 align-items-center justify-content-end">
                                        <button id="submitBtn" type="submit" class="btn btn-{{$move->status < 1 ? 'warning' : 'success'}}">
                                            {{$move->status < 1 ? 'Proses' : 'Selesaikan'}} Distribusi Barang
                                        </button>
                                    </div>
                                    <div id="loadingState" class="text-center mt-3" style="display: none;">
                                        <hr>
                                        <div class="spinner-border text-success" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <div class="mt-2 fw-bold text-success">Memproses Distribusi Barang...</div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @php
                    $status = [
                        '0' => ['label' => 'Draft','color' => 'secondary'],
                        '1' => ['label' => 'On Process','color' => 'warning',],
                        '2' => ['label' => 'Finished','color' => 'success',],
                    ];
                @endphp
                <div>
                    <div class="fw-normal fs-1 text-muted" style="">Status Distribusi
                    </div>
                    <h6 class="fw-semibold fs-2 text-{{ $status[$move->status ?? 0]['color'] }} mb-1" style="">{{ $status[$move->status ?? 0]['label'] }}</h6>
                </div>
            </div>
            <div class="col-md-6 order-first order-md-0 d-flex align-items-start gap-2 flex-wrap">
                <div class="stepper flex-grow-1 overflow-auto">
                    <div class="step active" data-step="1">
                        <div class="circle">1</div>
                        <div class="label fs-2">Data Distribusi</div>
                        <div class="line"></div>
                    </div>
                    <div class="step" data-step="2">
                        <div class="circle">2</div>
                        <div class="label fs-2">Produk / Stock</div>
                        <div class="line"></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-4 d-flex justify-content-end align-items-start gap-2">
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteReceive-{{$move->id}}"><i class="ti ti-trash"></i> <span class="ms-1 d-none d-md-inline-block">Hapus</span></button>
                <button onclick="seePDF('pdf.po','{{$move->id}}')" class="btn btn-danger" style="background:rgb(186, 55, 55); border-color:rgb(186, 55, 55)"><i class="ti ti-printer me-2"></i><span class="d-none d-sm-inline-block">Cetak</span></button>
            </div>

            <!-- Delete Modal -->
            <div id="deleteReceive-{{$move->id}}" class="modal fade" tabindex="-1"
                aria-labelledby="danger-header-modalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                    <div class="modal-content p-3 modal-filled bg-danger">
                        <div class="modal-header modal-colored-header text-white">
                            <h4 class="modal-title text-white" id="danger-header-modalLabel">
                                Yakin ingin menghapus Distribusi Barang ({{$move->code}}) ?
                            </h4>
                            <button type="button" class="btn-close btn-close-white mb-auto"
                                data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="width: fit-content; white-space:normal">
                            <h5 class="mt-0 text-white">Distribusi Barang {{$move->code}} di gudang {{$move->from->name}} akan dihapus</h5>
                            <p class="text-white">Segala data yang berkaitan dengan Request Order tersebut juga akan dihapus secara permanen. Penghapusan dapat dilakukan jika tidak ada Transaksi penting yang terkait.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                Close
                            </button>
                            <form action="{{route('stock.destroy', $move->id)}}" method="POST">
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
                                <div>
                                    <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Tanggal</div>
                                    <h6 class="fs-2 fw-semibold text-success mb-1" style="">
                                        {{ \Carbon\Carbon::parse($move->date)->format('d F Y') }}</h6>
                                </div>
                                <div>
                                    <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Detail Distribusi
                                    </div>
                                    <h6 class="fw-semibold text-primary mb-1" style="">{{ $move->code }}</h6>
                                </div>
                                <div class=""><i class="ti ti-user-circle me-2"></i> {{ $move->user->name }}</div>
                                <div>
                                    @php
                                    $statuses = [
                                        '0' => ['label' => 'Draft','color' => 'secondary'],
                                        '1' => ['label' => 'On Proses','color' => 'warning'],
                                        '2' => ['label' => 'Finished','color' => 'success'],
                                    ];
                                    @endphp

                                    <div class="fw-normal fs-1 text-muted" style="">Status
                                    </div>
                                    <h6 class="fw-semibold fs-2 text-{{ $statuses[$move->status]['color'] }} mb-1" style="">{{ $statuses[$move->status]['label'] }}</h6>
                                </div>
                                <hr>
                                <h6>Detail</h6>
                                <div class="mb-1">
                                    <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Barang Dari</div>
                                    <div class=""><i class="ti ti-building-{{$move->from_type}} me-2"></i> {{ $move->from->name }}</div>
                                    <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Distribusi Ke</div>
                                    <div class=""><i class="ti ti-building-{{$move->to_type}} me-2"></i> {{ $move->to->name }}</div>
                                </div>
                                <div style="min-width: 10em">
                                    <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Deksripsi</div>
                                    <p class="mb-1 fs-2" style="white-space:normal !important;">{{ $move->description ?? 'tidak ada deskripsi' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="">
                                    <h6>Distribusi Ke</h6>
                                    <a href="{{route(($move->to_type == 'warehouse' ? 'warehouse' : 'store').'.index', ['search' => $move->to->name])}}" target="_blank" class="border border-primary p-1 px-2 rounded-2 d-inline-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-building-{{$move->to_type == 'warehouse' ? 'warehouse' : 'store'}} mb-0 fs-3"></i> {{ $move->to->name }}
                                    </a>
                                    <div class="fw-normal fs-1 text-muted" style="">Detail</div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-map-2 mb-0 fs-3"></i> {{ $move->to->address.', '.$move->to->city.'. '.$move->to->postal_code }}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-mail mb-0 fs-3"></i> {{ $move->to->email ?? '-' }}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-phone mb-0 fs-3"></i> {{ $move->to->phone ?? '-' }}
                                    </div>
                                </div>
                                <hr />
                                <button type="button"
                                    class="dropdown-item fs-2 text-center d-inline-flex p-2 px-3 align-items-center gap-2 bg-secondary text-white rounded-3"
                                    data-bs-toggle="modal" data-bs-target="#produkModal-{{$move->id}}"><i
                                        class="fs-4 ti ti-package"></i> {{ count($move->products) }} Produk</button>

                                <!-- List Product modal -->
                                <div class="modal fade " id="produkModal-{{$move->id}}" tabindex="-1"
                                    aria-labelledby="vertical-center-modal" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header d-flex align-items-center">
                                                <h4 class="modal-title" id="myLargeModalLabel">
                                                    Produk yang Didistribusikan
                                                </h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body pt-0">
                                                @forelse ($move->products as $dproduct)
                                                    <div class="border border-1 border-dashed border-primary {{$loop->index+1 == count($move->products) ? '' : 'mb-2'}} p-3 rounded-3">
                                                        <div
                                                            class="d-flex align-items-center gap-2 mb-2 {{ $loop->index == 0 ? '' : 'mt-3' }}">
                                                            <img src="{{ $dproduct->product->image ? '/storage/' . $dproduct->product->image : 'https://placehold.co/300?text=' . $dproduct->product->name }}"
                                                                alt="Image Product {{ $dproduct->product->name }} in Cart" class="d-block rounded-2"
                                                                style="width: 5em; height:5em; object-fit:cover">
                                                            <div>
                                                                <div class="mb-1">
                                                                    <div class="d-inline-block p-1 px-2 rounded-2 bg-primary-subtle text-primary fs-2">{{ $dproduct->product->type->type }}</div>
                                                                </div>
                                                                <div class="text-decoration-none text-dark fs-3 fw-semibold">
                                                                    {{ $dproduct->product->name }}</div>
                                                                <div class="text-muted fs-2 mb-2">
                                                                    {{ $dproduct->product->description ?? 'tidak ada deskripsi' }}</div>
                                                            </div>

                                                        </div>
                                                        <div class="d-flex align-items-end gap-2">
                                                            <div>
                                                                <label for="qty" class="text-muted fs-1">Didistribusikan Sebanyak
                                                                    (qty)</label>
                                                                <div class="d-flex align-items-center gap-2">
                                                                    {{ $dproduct->qty }}
                                                                </div>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <label for="price_buy" class="text-muted fs-1">Deskripsi / Keterangan</label>
                                                                <div class="fs-2">{{ $dproduct->description }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="p-3 border text-center border-2 border-dashed rounded-2">
                                                        Produk belum ditentukan
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="">
                                    <h6>Dengan Detail Logistik / Pengangkutan</h6>
                                    @if( $move->tarnsport )
                                    <a href="{{route('logistic.setting', $move->transport->logistic->id)}}" target="_blank" class="border border-primary p-1 px-2 rounded-2 d-inline-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-truck-delivery mb-0 fs-3"></i> {{ $move->transport->logistic->name }}
                                    </a>
                                    <a href="javascript:void(0);" title="Klik untuk melihat detail" class="d-flex text-secondary align-items-center fs-2 mb-1 gap-2"
                                        data-bs-toggle="modal" data-bs-target="#detailDelivery-{{$move->id}}"
                                    >
                                        <i class="ti ti-exchange mb-0 fs-3"></i> Detail Antar Jemput
                                    </a>
                                    <!-- List Product modal -->
                                    <div class="modal fade " id="detailDelivery-{{$move->id}}" tabindex="-1"
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
                                                            <div class="fs-3">{{$move->pickup->address.', '.$move->pickup->city.'. '.$move->pickup->postal_code}}</div>
                                                        </div>
                                                    </div>
                                                    <div class="p-3 rounded-3 border border-dashed border-primary">
                                                        <div class="fs-2 text-muted">Pengantaran Barang</div>
                                                        <div>
                                                            <div class="fs-3">{{$move->delivery->address.', '.$move->delivery->city.'. '.$move->delivery->postal_code}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fw-normal fs-1 text-muted" style="">Contact Person</div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-user-circle mb-0 fs-3"></i> {{ $move->transport->logistic->cp_name }}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-mail mb-0 fs-3"></i> {{ $move->transport->logistic->cp_email ?? '-' }}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-phone mb-0 fs-3"></i> {{ $move->transport->logistic->cp_phone ?? '-' }}
                                    </div>
                                    @else
                                    <div class="fs-2">Tidak ada jasa Pengangkutan</div>
                                    @endif
                                </div>
                                <hr>
                                <h6>Timestamp</h6>
                                <div class="d-flex flex-column align-items-start gap-2">
                                    <div class="badge bg-success-subtle text-success rounded-3 fw-semibold fs-2">
                                        Updated
                                        at
                                        : {{ $move->updated_at }}</div>
                                    <div class="badge bg-primary-subtle text-primary rounded-3 fw-semibold fs-2">
                                        Created
                                        at
                                        : {{ $move->created_at }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row d-none" id="editDataBox">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-4 h-100 mb-3">
                                @if($move->image)
                                <img id="now-image" src="{{ '/storage/'.$move->image }}" alt="Movement Stock Image Preview" class="rounded-4 shadow w-100"
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
                                        <form action="{{ route('stock.update', $move->id)}}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-4">
                                                <label class="form-label fw-semibold">Tanggal Distribusi</label>
                                                <div class="input-group">
                                                    <span class="input-group-text px-6" id="basic-addon1"><i
                                                            class="ti ti-calendar-event fs-6"></i></span>
                                                    <input type="date" name="date" class="form-control ps-2" value="{{old('date', $move->date ?? \Carbon\Carbon::parse(now())->format('Y-m-d'))}}">
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
                                                                <option value="{{$user->id}}" {{$user->id == old('user_id', $move->user_id) ? 'selected' : ''}}>{{$user->name}}</option>
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
                                                    <input class="form-check-input" name="is_handle_logistic" type="checkbox" value="1" id="is_handle_logistic" {{ old('transport_id', $move->transport_id) ? 'checked' : '' }} />
                                                    <label class="form-check-label" for="is_handle_logistic">Perusahaan mengurus Logistik / Pengangkutan</label>
                                                </div>
                                            </div>
                                            <div class="p-3 bg-primary-subtle rounded-3 mb-4 {{ old('transport_id', $move->transport_id) ? '' : 'd-none' }}" id="logistic_input">
                                                <label class="form-label fw-semibold">Pengangkutan / Logistik Distribusi</label>
                                                <div class="input-group">
                                                    <span class="input-group-text px-6" id="basic-addon1"><i
                                                            class="ti ti-truck-delivery fs-6"></i></span>
                                                    <div style="flex-grow:1">
                                                        <select name="transport_id" id="transport_id" class="select2-normal form-select">
                                                            <option value="">-- Pilih Pengangkutan --</option>
                                                            @foreach ($transports as $transport)
                                                                <option value="{{$transport->id}}" {{$transport->id == old('transport_id', $move->transport_id) ? 'selected' : ''}}>{{$transport->code.' | Memakai Logistik : '.$transport->logistic->name.', Dengan Total Harga : '.formatRupiah($transport->total_price).', PPN : '.($transport->tax ?? 0).'%' }}</option>
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
                                                                    <option value="{{$warehouse->id}}" {{$warehouse->id == old('from_id', $move->from_id) ? 'selected' : ''}}>{{ "[Gudang] {$warehouse->name}, {$warehouse->address} - {$warehouse->city}. {$warehouse->postal_code}" }}</option>
                                                                @endforeach
                                                                @foreach ($stores as $store)
                                                                    <option value="{{$store->id}}" {{$store->id == old('from_id', $move->from_id) ? 'selected' : ''}}>{{ "[Toko] {$store->name}, {$store->address} - {$store->city}. {$store->postal_code}" }}</option>
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
                                                                    <option value="{{$warehouse->id}}" {{$warehouse->id == old('to_id', $move->to_id) ? 'selected' : ''}}>{{ "[Gudang] {$warehouse->name}, {$warehouse->address} - {$warehouse->city}. {$warehouse->postal_code}" }}</option>
                                                                @endforeach
                                                                @foreach ($stores as $store)
                                                                    <option value="{{$store->id}}" {{$store->id == old('to_id', $move->to_id) ? 'selected' : ''}}>{{ "[Toko] {$store->name}, {$store->address} - {$store->city}. {$store->postal_code}" }}</option>
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
                                                Perbarui Distribusi
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @include('transports.addmodal',['id' => 'addPengangkutanModal'])

                    </div>
                </div>
            </div>

            @php
                $carts = session()->get('distribution_cart_' . $move->id, []);
                if ($move->products->count() > 0) {
                    $carts = $move->products->map(function($product) {
                        return [
                            'id' => $product->product_id,
                            'qty' => $product->qty,
                            'description' => $product->description,
                        ];
                    })->toArray();
                }
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
                                        <label for="search" class="form-label">Menampilkan Stock dari Tempat Asal Barang ({{$move->from->name}})</label>
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
                            @foreach ($products as $product)
                                @php
                                    if($move->requestOrder)
                                        $product = $product->product;
                                @endphp
                                <div class="col-12 col-sm-6 mb-2">
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

                                                <div class="p-2 my-1 rounded-2 bg-primary-subtle">
                                                    @if($product->type->type == 'meteran')
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div class="text-dark fs-2 fw-bold" title="Total Stock Satuan"><i class="ti ti-ruler me-2"></i> {{$product->analytic->qty_remaining}} qty</div>

                                                            <div class="d-flex align-items-center">
                                                                <div class="px-2 fs-2 border-x-2 text-success fw-semibold">{{ $product->analytic->qty }}</div>
                                                                <div class="px-2 fs-2 border-x-2 text-warning fw-semibold">{{ $product->analytic->qty_onway }}</div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="d-flex align-items-center justify-content-around">
                                                            <div class="text-dark fw-bold fs-2" title="Total Stock Satuan"><i class="ti ti-package me-2"></i> {{$product->analytic->stock_remaining}} qty</div>

                                                            <div class="d-flex align-items-center">
                                                                <div class="px-2 fs-2 border-x-2 text-success fw-semibold">{{ $product->analytic->stock_in }}</div>
                                                                <div class="px-2 fs-2 border-x-2 text-danger fw-semibold">{{ $product->analytic->stock_out }}</div>
                                                                <div class="px-2 fs-2 border-x-2 text-warning fw-semibold">{{ $product->analytic->stock_onway }}</div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <div class="d-flex mt-auto align-items-center gap-2 mb-3">
                                                    <button type="submit"
                                                        class="{{ isset($carts[$product->id]) ? '' : 'd-none' }} fs-3 px-3 w-100 text-center justify-content-center btn-sm btn btn-secondary-subtle rounded-2 d-flex align-items-center gap-2"
                                                        disabled>
                                                        <i class="ti ti-package-off"></i>
                                                        <span class="fs-2 w-100" style="white-space: nowrap">Sudah Ada</span>
                                                    </button>
                                                    <form action="{{ route('stock.addCart', $move->id) }}"
                                                        method="POST"
                                                        class="{{ isset($carts[$product->id]) ? 'd-none' : '' }}"
                                                        style="flex-grow: 1; width:100%">
                                                        @csrf
                                                        <input type="hidden" name="product_id"
                                                            value="{{ $product->id }}">
                                                        <button type="submit"
                                                            class="fs-3 px-3 w-100 text-center justify-content-center btn-sm btn btn-primary rounded-2 d-flex align-items-center gap-2"
                                                            style="width: 100%;"
                                                            {{ $move->status != 0 ? 'disabled' : '' }}
                                                        >
                                                            <i class="ti ti-packge-export"></i>
                                                            <span class="fs-2">
                                                            {{
                                                                $move->status != 0
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
                            <h5 class="mb-0">Produk yang Didistribusikan</h5>
                            <div class="d-flex align-items-center justify-content-center bg-primary text-white p-2 rounded-circle"
                                style="aspect-ratio:1/1; width:2.5em; height:2.5em">
                                {{ count($carts) }}
                            </div>
                        </div>
                        <form action="{{ route('stock.product.store', $move->id) }}" method="POST"
                            class="d-block bg-white">
                            @csrf
                            <div class="border border-2 border-dashed border-dark-subtle rounded-4 p-3">
                                <p class="fs-2"><span class="text-danger">*</span><i>Perubahan tidak disimpan sampai
                                    anda menekan tombol Simpan</i></p>
                                @forelse ($carts as $item)
                                    @php
                                        $cart = \App\Models\Product::find($item['id']);
                                        if (isset($cart->type) && isset($cart->type->type) && $cart->type->type === 'meteran') {
                                            $analytic = \App\Models\StockMeter::analyticProductInLocation($move->from_type, $move->from_id, $cart->id)->first();
                                            $cart->analytic = $analytic ?: (object)[
                                                'qty' => 0,
                                                'length_total' => 0,
                                                'qty_onway' => 0,
                                                'qty_remaining' => 0,
                                            ];
                                        } else {
                                            $analytic = \App\Models\Stock::analyticProductInLocation($move->from_type, $move->from_id, $cart->id)->first();
                                            $cart->analytic = $analytic ?: (object)[
                                                'stock_in' => 0,
                                                'stock_out' => 0,
                                                'stock_onway' => 0,
                                                'stock_remaining' => 0,
                                            ];
                                        }
                                    @endphp
                                    <div id="cart-item-{{ $item['id'] }}">
                                        <div
                                            class="d-flex flex-wrap align-items-center gap-2 mb-2 {{ $loop->index == 0 ? '' : 'mt-3' }}">
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

                                            <div class="p-2 my-1 ms-auto rounded-2 bg-primary-subtle">
                                                @if($cart->type->type == 'meteran')
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="text-dark fs-2 fw-bold" title="Total Stock Satuan"><i class="ti ti-ruler me-2"></i> {{$cart->analytic->qty_remaining}} qty</div>

                                                        <div class="d-flex align-items-center">
                                                            <div class="px-2 fs-2 border-x-2 text-success fw-semibold">{{ $cart->analytic->qty }}</div>
                                                            <div class="px-2 fs-2 border-x-2 text-warning fw-semibold">{{ $cart->analytic->qty_onway }}</div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="d-flex align-items-center justify-content-around">
                                                        <div class="text-dark fw-bold fs-2" title="Total Stock Satuan"><i class="ti ti-package me-2"></i> {{$cart->analytic->stock_remaining}} qty</div>

                                                        <div class="d-flex align-items-center">
                                                            <div class="px-2 fs-2 border-x-2 text-success fw-semibold">{{ $cart->analytic->stock_in }}</div>
                                                            <div class="px-2 fs-2 border-x-2 text-danger fw-semibold">{{ $cart->analytic->stock_out }}</div>
                                                            <div class="px-2 fs-2 border-x-2 text-warning fw-semibold">{{ $cart->analytic->stock_onway }}</div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex align-items-start flex-wrap flex-sm-nowrap gap-2">
                                            @php
                                                $moveProduct = $move->products()->where('product_id', $cart->id)->first();   
                                            @endphp
                                            <div class="flex-grow-1">
                                                <label for="qty" class="form-label mb-0 fs-2">Pindahkan Sebanyak
                                                    (qty)</label>
                                                
                                                <div class="d-flex align-items-center gap-2">
                                                    <input type="hidden" name="product_id[]"
                                                        value="{{ $item['id'] }}">
                                                    <input type="number" name="qty[]" id="qty_{{ $item['id'] }}"
                                                        class="form-control qty-input"
                                                        data-id="{{ $item['id'] }}" value="{{ $moveProduct ? $moveProduct->qty : $item['qty'] }}"
                                                        min="1" {{$move->status != 0 ? 'disabled' : ''}} />
                                                </div>
                                            </div>

                                            <div class="flex-grow-1">
                                                <label for="receive_qty" class="form-label mb-0 fs-2">Catatan / Keterangan</label>
                                                <div class="d-flex align-items-center gap-2">
                                                   <textarea name="desc[]" id="description_{{$cart->id}}" placeholder="Berikan catatan / keterangan apabila diperlukan" cols="30" rows="3" class="form-control form-control-sm" {{ $move->status != 0 ? 'disabled' : '' }}>{{ $moveProduct && $moveProduct->description || $cart['description'] ? $moveProduct->description : ''}}</textarea>
                                                </div>
                                            </div>
                                                
                                            <button class="btn btn-sm btn-danger remove-cart align-self-center"
                                                data-url="{{ route('stock.removeCart', $move->id) }}"
                                                data-product-id="{{ $item['id'] }}"
                                                {{$move->status != 0 ? 'disabled' : ''}}
                                            >
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
                                        <button type="submit" class="btn btn-primary w-100" {{$move->status != 0 ? 'disabled' : ''}}>Simpan Data Barang Distribusi</button>
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
                location.reload();
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
