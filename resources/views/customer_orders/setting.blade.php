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
                    <h4 class="fw-semibold mb-8">Kelola Penjualan Instan : {{ $cod->code }}</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none"
                                    href="{{ route('customer-order.index') }}">Penjualan Instan</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Kelola Penjualan Instan : {{ $cod->code }}
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
            <div class="col-6 col-md-3  mb-auto d-flex align-items-center gap-2">
                @php
                    $status = [
                        '0' => ['label' => 'Belum Dibayar', 'color' => 'secondary'],
                        '1' => ['label' => 'Terbayar & Selesai', 'color' => 'success'],
                    ];
                @endphp

                <div>
                    <div class="fw-normal fs-1 text-muted" style="">Status</div>
                    <h6 class="fw-semibold fs-2 text-{{ $status[$cod->is_finished]['color'] }} mb-1" style="">
                        {{ $status[$cod->is_finished]['label'] }}</h6>
                </div>
            </div>
            <div class="col-md-6 order-first order-md-0 d-flex align-items-start gap-2 flex-wrap">
                <div class="stepper flex-grow-1 overflow-auto">
                    <div class="step active" data-step="1">
                        <div class="circle">1</div>
                        <div class="label fs-2">Data <span class="d-none d-sm-block">Penjualan Instan</span></div>
                        <div class="line"></div>
                    </div>
                    <div class="step" data-step="2">
                        <div class="circle">2</div>
                        <div class="label fs-2">Produk <span class="d-none d-sm-block">dipesan</span></div>
                        <div class="line"></div>
                    </div>
                    <div class="step" data-step="3">
                        <div class="circle">3</div>
                        <div class="label fs-2">Pembayaran</div>
                        <div class="line"></div>
                    </div>
                    <div class="step" data-step="4">
                        <div class="circle">4</div>
                        <div class="label fs-2">Partner</div>
                        <div class="line"></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-4 d-flex justify-content-end align-items-start gap-2">
                <button class="btn btn-danger" data-bs-toggle="modal"
                    data-bs-target="#deleteRequestOrder-{{ $cod->id }}"><i class="ti ti-trash"></i> <span
                        class="ms-1 d-none d-md-inline-block">Hapus</span></button>
                <a href="{{ route('customer-order.invoice', ['id' => $cod->id]) }}" target="_blank" class="btn btn-danger"
                    style="background:rgb(186, 55, 55); border-color:rgb(186, 55, 55)"><i class="ti ti-printer"></i></a>
            </div>

            <!-- Delete Modal -->
            <div id="deleteRequestOrder-{{ $cod->id }}" class="modal fade" tabindex="-1"
                aria-labelledby="danger-header-modalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                    <div class="modal-content p-3 modal-filled bg-danger">
                        <div class="modal-header modal-colored-header text-white">
                            <h4 class="modal-title text-white" id="danger-header-modalLabel">
                                Yakin ingin menghapus Penjualan Instan (Request Order) {{ $cod->code }} ?
                            </h4>
                            <button type="button" class="btn-close btn-close-white mb-auto" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="width: fit-content; white-space:normal">
                            <h5 class="mt-0 text-white">Penjualan Instan (Request Order) {{ $cod->code }} akan dihapus
                            </h5>
                            <p class="text-white">Segala data yang berkaitan dengan Request Order tersebut juga akan dihapus
                                secara permanen. Penghapusan dapat dilakukan jika tidak ada Transaksi penting yang terkait.
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                Close
                            </button>
                            <form action="{{ route('customer-order.destroy', $cod->id) }}" method="POST">
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

                <div id="showDataBox">
                    <div class="card position-relative">
                        <button id="btnEditData" class="btn btn-sm btn-secondary position-absolute top-0 end-0 m-3"><i
                                class="ti ti-edit"></i></button>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <h6>Penjualan Instan</h6>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Tanggal</div>
                                        <h6 class="fs-2 fw-semibold text-success mb-1" style="">
                                            {{ \Carbon\Carbon::parse($cod->date)->format('d F Y') }}</h6>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kode Penjualan
                                            Instan
                                        </div>
                                        <h6 class="fw-semibold text-primary mb-1" style="">{{ $cod->code }}</h6>
                                    </div>
                                    @php
                                        $status = [
                                            '0' => ['label' => 'Belum Dibayar', 'color' => 'secondary'],
                                            '1' => ['label' => 'Terbayar & Selesai', 'color' => 'success'],
                                        ];
                                    @endphp
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="">Status Penjualan Instan
                                        </div>
                                        <h6 class="fw-semibold fs-2 text-{{ $status[$cod->is_finished]['color'] }} mb-1"
                                            style="">{{ $status[$cod->is_finished]['label'] }}</h6>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="">Deskripsi</div>
                                        <div class="fw-normal fs-2" style="white-space:normal; font-size:13px; ">
                                            {{ $cod->description ?? 'Tidak ada' }}</div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <h6>Customer yang Meminta</h6>
                                    <a href="{{ route('customer.index', ['search' => $cod->customer->name]) }}"
                                        target="_blank"
                                        class="border border-primary p-1 px-2 rounded-2 d-inline-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-user-circle mb-0 fs-3"></i> {{ $cod->customer->name }}
                                    </a>
                                    <div class="fw-normal fs-1 text-muted" style="">Kontak</div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-mail mb-0 fs-3"></i> {{ $cod->customer->email ?? '-' }}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-phone mb-0 fs-3"></i> {{ $cod->customer->phone ?? '-' }}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-map-2 mb-0 fs-3"></i>
                                        {{ $cod->customer->address . ', ' . $cod->customer->city . '. ' . $cod->customer->postal_code }}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <h6>Kalkulasi</h6>
                                    <div>
                                        @if ($cod->products->count() > 0)
                                            <div>
                                                <div class="fw-normal fs-1 text-muted" style="">Total Nilai Awal
                                                </div>
                                                <h6 class="fw-semibold fs-2 text-primary mb-1" style="">
                                                    {{ formatRupiah($cod->total_price) }}</h6>
                                            </div>
                                            <div>
                                                <div class="fw-normal fs-1 text-muted" style="">Pajak</div>
                                                <h6 class="fw-semibold fs-2 text-primary mb-1" style="">
                                                    {{ $cod->tax ?? '11' }}%</h6>
                                            </div>
                                            <div>
                                                <div class="fw-normal fs-1 text-muted" style="">Total Dengan Pajak
                                                </div>
                                                {{-- @php
                                             dd($cod->total_price, $cod->tax, $cod->tax && (int)$cod->tax != '0' ? $cod->tax : 11, ));
                                          @endphp --}}
                                                <h6 class="fw-semibold fs-2 text-primary mb-1">
                                                    {{ (int) $cod->total_price_taxed && (int) $cod->total_price_taxed != 0
                                                        ? formatRupiah($cod->total_price_taxed)
                                                        : formatRupiah(
                                                            (int) $cod->total_price +
                                                                (int) $cod->total_price * (($cod->tax && (int) $cod->tax != 0 ? $cod->tax : 11) / 100),
                                                        ) }}
                                                </h6>
                                            </div>
                                        @else
                                            Lengkapi Data Dahulu
                                        @endif
                                    </div>
                                    <hr>
                                    <div>
                                       <button type="button"
                                        class="dropdown-item fs-2 text-center d-inline-flex p-2 px-3 align-items-center gap-2 bg-secondary text-white rounded-3"
                                        data-bs-toggle="modal" data-bs-target="#produkModal-{{ $cod->id }}"><i
                                            class="fs-4 ti ti-package"></i> {{ count($cod->products) }} Produk</button>

                                          <!-- List Product modal -->
                                          <div class="modal fade " id="produkModal-{{ $cod->id }}" tabindex="-1"
                                             aria-labelledby="vertical-center-modal" aria-hidden="true">
                                             <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                                <div class="modal-content">
                                                      <div class="modal-header d-flex align-items-center">
                                                         <h4 class="modal-title" id="myLargeModalLabel">
                                                            Produk yang Dipesan
                                                         </h4>
                                                         <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                      </div>
                                                      <div class="modal-body pt-0">

                                                         @forelse ($cod->products as $codProduct)
                                                            @php
                                                                  $priceSale = $codProduct->price_sale ?? 0; // Harga jual (diambil dari cart)
                                                                  $subtotal = $priceSale * $codProduct->qty; // Hitung subtotal awal
                                                            @endphp
                                                            <div
                                                                  class="border border-1 border-dashed border-primary {{ $loop->index + 1 == count($cod->products) ? '' : 'mb-2' }} p-3 rounded-3">
                                                                  <div
                                                                     class="d-flex align-items-center gap-2 mb-2 {{ $loop->index == 0 ? '' : 'mt-3' }}">
                                                                     <img src="{{ $codProduct?->product?->image ? '/storage/' . $codProduct?->product?->image : 'https://placehold.co/300?text=' . $codProduct?->product?->name }}"
                                                                        alt="Image Product {{ $codProduct?->product?->name }} in Cart"
                                                                        class="d-block rounded-2"
                                                                        style="width: 5em; height:5em; object-fit:cover">
                                                                     <div>
                                                                        <div
                                                                              class="text-decoration-none text-dark fs-3 fw-semibold">
                                                                              {{ $codProduct?->product?->name }}</div>
                                                                        <div class="text-muted fs-2 mb-2">
                                                                              {{ $codProduct?->product?->description ?? 'tidak ada deskripsi' }}
                                                                        </div>
                                                                     </div>
                                                                     <div
                                                                        class="flex-grow-1 d-flex flex-column align-items-end gap-2 justify-content-between">
                                                                        <div class="fs-2 fw-semibold">Sub Total</div>
                                                                        <div class="fs-3 fw-bold subtotal">
                                                                              {{ formatRupiah($subtotal) }}</div>
                                                                     </div>
                                                                  </div>
                                                                  <div class="d-flex align-items-end gap-2">
                                                                     <div>
                                                                        <label for="qty"
                                                                              class="text-muted fs-1">Memproses Sebanyak
                                                                              ({{ $codProduct?->product?->unit->code }})
                                                                        </label>
                                                                        <div class="d-flex align-items-center gap-2">
                                                                              {{ $codProduct->qty }}
                                                                        </div>
                                                                     </div>
                                                                     <div class="flex-grow-1">
                                                                        <label for="price_buy" class="text-muted fs-1">Dengan
                                                                              Harga Beli</label>
                                                                        <div>{{ formatRupiah($codProduct->price_buy) }}</div>
                                                                     </div>
                                                                  </div>
                                                            </div>
                                                         @empty
                                                            <div>Empty</div>
                                                         @endforelse
                                                         <div class="pt-3 mt-3 border-top border-2">
                                                            <div
                                                                  class="d-flex align-items-center gap-2 justify-content-between">
                                                                  <div class="fs-3 fw-semibold">Total</div>
                                                                  @if ($cod->products->count() > 0)
                                                                     <div class="fs-4 fw-bold">
                                                                        {{ formatRupiah($cod->total_price) }}</div>
                                                                  @else
                                                                     <div class="fs-4 fw-bold">Lengkapi Data Dahulu <a
                                                                              href="{{ route('customer-order.setting', $cod->id) . '#produk' }}"
                                                                              class="btn btn-sm btn-warning ms-2">Lengkapi</a>
                                                                     </div>
                                                                  @endif
                                                            </div>
                                                         </div>
                                                      </div>
                                                </div>
                                             </div>
                                          </div>
                                    </div>
                                    <hr>
                                    <h6>Timestamp</h6>
                                    <div class="d-flex flex-column align-items-start gap-2">
                                        <div class="badge bg-success-subtle text-success rounded-3 fw-semibold fs-2">
                                            Updated
                                            at
                                            : {{ $cod->updated_at }}</div>
                                        <div class="badge bg-primary-subtle text-primary rounded-3 fw-semibold fs-2">
                                            Created
                                            at
                                            : {{ $cod->created_at }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row d-none" id="editDataBox">
                    <div class="col-12">
                        <div class="card">
                            <div class="px-4 py-3 border-bottom">
                                <h5 class="card-title fw-semibold mb-0">Penjualan Instan</h5>
                                <button id="btnCloseData"
                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 m-3"><i
                                        class="ti ti-x"></i></button>
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
                                <form action="{{ route('customer-order.update', $cod->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold fs-2">Tanggal Pembelian</label>
                                        <div class="input-group">
                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                    class="ti ti-calendar-event fs-6"></i></span>
                                            <input type="date" name="date" class="form-control ps-2"
                                                value="{{ old('date', $cod->date ?? \Carbon\Carbon::parse(now())->format('Y-m-d')) }}">
                                        </div>
                                        @error('date')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold fs-2">Penanggung Jawab Penjualan</label>
                                        <div class="input-group">
                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                    class="ti ti-user-circle fs-6"></i></span>
                                            <div style="flex-grow:1">
                                                <select name="user_id" id="user_id" class="select2-normal form-select">
                                                    <option value="">-- Pilih User --</option>
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}"
                                                            {{ $user->id == old('user_id', $cod->user_id) ? 'selected' : '' }}>
                                                            {{ $user->name }}
                                                            {{ auth()->user()->id == $user->id ? '(Saya sendiri)' : '' }}
                                                        </option>
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
                                        <label class="form-label fw-semibold fs-2">Customer / Pelanggan</label>
                                        <div class="input-group">
                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                    class="ti ti-truck-delivery fs-6"></i></span>
                                            <div style="flex-grow:1">
                                                <select name="customer_id" id="customer_id"
                                                    class="select2-normal form-select">
                                                    <option value="">-- Pilih Pelanggan / Customer --</option>
                                                    @foreach ($customers as $customer)
                                                        <option value="{{ $customer->id }}"
                                                            {{ $customer->id == old('customer_id', $cod->customer_id) ? 'selected' : '' }}>
                                                            {{ $customer->name . ', ' . $customer->email . ' - ' . $customer->phone }}
                                                        </option>
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
                                                data-bs-toggle="modal" data-bs-target="#addCustomerModal">Tambah
                                                Customer</button>
                                        </div>
                                       

                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold fs-2">Deskripsi</label>
                                        <div class="input-group">
                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                    class="ti ti-align-justified fs-6"></i></span>
                                            <textarea class="form-control ps-2" name="description" id="description" cols="20" rows="5"
                                                placeholder="Deskripsi untuk Penjualan Instan Pelanggan">{{ old('description', $cod->description) }}</textarea>
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
                                 @include('customers.addmodal',['id' => 'addCustomerModal'])
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $carts = session()->get('cod_cart_' . $cod->id, []);
                $totalPrice = 0;
            @endphp
            <!-- Form Step 2 -->
            <div class="step-content" data-step="2" style="display: none;">
                <div class="row">
                    <div class="col-md-7">
                        <div>
                            <form action="{{ url()->current() }}" method="GET" class="w-100">
                                <input type="hidden" name="hashProduct" value="1">
                                <div class="row align-items-end mb-3 flex-wrap">
                                    <div class="col-md-9 mb-2 flex-grow-1">
                                        <label for="search" class="form-label">Filter Produk</label>
                                        <input type="text" class="form-control" placeholder="Cari Produk"
                                            name="search" value="{{ $filter->q ?? '' }}">
                                    </div>
                                    <div class="col-md-3 mb-2">
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
                        <div class="row">
                            @foreach ($products as $product)
                                <div class="col-12 col-sm-6 col-md-4 mb-2">
                                    <div class="card rounded-4 h-100 overflow-hidden">
                                        <div class="card-body p-0 h-100 d-flex flex-column">
                                            <img src="{{ $product->image ? '/storage/' . $product->image : 'https://placehold.co/160x90?text=' . $product->name }}"
                                                alt="Image {{ $product->name }}"
                                                class="d-block w-100 mb-2 bg-primary-subtle"
                                                style="aspect-ratio:16/9; object-fit:contain;">
                                            <div class="p-1 h-100 d-flex flex-column justify-content-between px-3">
                                                <div class="mb-1">
                                                    <div
                                                        class="d-inline-block p-1 px-2 rounded-2 bg-primary-subtle text-primary fs-2">
                                                        {{ $product->type->type }}</div>
                                                </div>
                                                <div class="text-decoration-none text-dark fs-3 fw-semibold">
                                                    {{ $product->name }}</div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div><i class="ti ti-arrow-up"></i> {{ $product->height }} cm</div>
                                                    <div><i class="ti ti-arrow-right"></i> {{ $product->width }} cm</div>
                                                </div>
                                                <div class="text-muted fs-2 mb-2">
                                                    {{ $product->description ?? 'tidak ada deskripsi' }}</div>

                                                <div class="fs-2 fw-bold mt-1 text-muted"><i class="ti ti-clock"></i>
                                                    Terisisa {{ $product->stockCountIn('store') }} qty disemua Toko</div>
                                                <div class="d-flex mt-auto align-items-center gap-2 mb-3">
                                                    <button type="submit"
                                                        class="{{ isset($carts[$product->id]) && $product->type->type == 'satuan' ? '' : 'd-none' }} fs-3 px-3 w-100 text-center justify-content-center btn-sm btn btn-secondary-subtle rounded-2 d-flex align-items-center gap-2"
                                                        disabled>
                                                        <i class="ti ti-package-off"></i>
                                                        <span class="fs-2" style="white-space: nowrap">Sudah Ada</span>
                                                    </button>
                                                    <form action="{{ route('customer-order.addCart', $cod->id) }}"
                                                        method="POST"
                                                        class="{{ isset($carts[$product->id]) && $product->type->type == 'satuan' ? 'd-none' : '' }}"
                                                        style="flex-grow: 1">
                                                        @csrf
                                                        <input type="hidden" name="product_id"
                                                            value="{{ $product->id }}">
                                                        <button type="submit"
                                                            class="fs-3 px-3 w-100 text-center justify-content-center btn-sm btn btn-primary rounded-2 d-flex align-items-center gap-2">
                                                            <i class="ti ti-packge-export"></i>
                                                            <span class="fs-2">Pilih</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @include('partials.paginate', ['datas' => $products])
                        </div>
                    </div>
                    <div class="col-md-5 mb-3 order-first">

                        <div class="d-flex mb-3 align-items-center gap-3">
                            <i class="ti ti-package fs-8"></i>
                            <h5 class="mb-0">Produk yang dipesan</h5>
                            <div class="d-flex align-items-center justify-content-center bg-primary text-white p-2 rounded-circle"
                                style="aspect-ratio:1/1; width:2.5em; height:2.5em">
                                {{ count($carts) }}
                            </div>
                        </div>

                        <form action="{{ route('customer-order.product.store', $cod->id) }}" method="POST"
                            class="d-block bg-white">
                            @csrf
                            <div class="border border-2 border-dashed border-dark-subtle rounded-4 p-3">
                                <p class="fs-2"><span class="text-danger">*</span><i>Perubahan tidak disimpan sampai
                                        anda menekan tombol Simpan</i></p>
                                @php $totalPrice = 0; @endphp
                                @forelse ($carts as $item)
                                    @php
                                       if (preg_match('/_(.*)$/', $item['id'], $matches)) {
                                          $item['id'] = $matches[1];
                                       } else {
                                          $item['id'] = $item['id'];
                                       }

                                        $cart = \App\Models\Product::find($item['id']);
                                        $codproduct = null;
                                        if($cart->type->type == 'meteran'){
                                          $codproduct = \App\Models\CustomerOrderProduct::where(
                                             'customer_order_id',
                                             $cod->id,
                                          )
                                             ->where([
                                                   'product_id' => $item['id'],
                                                   'qty' => $item['qty'],
                                                   'length' => $item['length'],
                                                   'price_sale' => $item['price_sale'],
                                                   'price_buy' => $item['price_buy'],
                                             ])
                                             ->first();
                                        }else{
                                          $codproduct = \App\Models\CustomerOrderProduct::where(
                                             'customer_order_id',
                                             $cod->id,
                                          )
                                             ->where([
                                                   'product_id' => $item['id'],
                                                   'qty' => $item['qty'],
                                                   'price_sale' => $item['price_sale'],
                                                   'price_buy' => $item['price_buy'],
                                             ])
                                             ->first();
                                        }
                                        $priceSale = $item['price_sale'] ?? 0; // Harga jual (diambil dari cart)
                                        $subtotal = $priceSale * $item['qty']; // Hitung subtotal awal
                                        $totalPrice += $subtotal;
                                    @endphp
                                    <div id="cart-item-{{ $item['id'] }}">
                                        <div
                                            class="d-flex flex-wrap align-items-center gap-2 mb-2 {{ $loop->index == 0 ? '' : 'mt-3' }}">
                                            <img src="{{ $cart->image ? '/storage/' . $cart->image : 'https://placehold.co/300?text=' . $cart->name }}"
                                                alt="Image Product {{ $cart->name }} in Cart" class="d-block rounded-2"
                                                style="width: 5em; height:5em; object-fit:cover">
                                            <div>
                                                <div class="mb-1">
                                                    <div
                                                        class="d-inline-block p-1 px-2 rounded-2 bg-primary-subtle text-primary fs-2">
                                                        {{ $cart->type->type }}</div>
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
                                            <div
                                                class="flex-grow-1 d-flex flex-column align-items-end gap-2 justify-content-between">
                                                <div class="fs-2 fw-semibold">Sub Total</div>
                                                <div class="fs-3 fw-bold subtotal" id="subtotal_{{ $item['id'] }}">
                                                    {{ formatRupiah($subtotal) }}</div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-start gap-2">
                                            <div>
                                                <label for="qty"
                                                    class="form-label {{ $cart->unit ? '' : 'text-danger' }} fs-2">
                                                    {{ $cart->unit ? 'Dibeli Sebanyak (' . $cart?->unit?->code . ')' : 'Satuan produk belum diatur' }}</label>
                                                <div class="d-flex align-items-center gap-2">
                                                    <input type="hidden" name="product_id[]"
                                                        value="{{ $item['id'] }}">
                                                    <input type="number" name="qty[]" id="qty_{{ $item['id'] }}"
                                                        class="form-control form-control-sm qty-input"
                                                        data-id="{{ $item['id'] }}"
                                                      value="{{ old('qty['.$loop->index.']', $codproduct->qty ?? $item['qty']) }}" min="1"
                                                        {{ $cod->is_finished != 0 ? 'disabled' : '' }} />
                                                </div>
                                                <div class="fs-1 mt-1 text-muted stock-info" id="stock_info_{{ $item['id'] }}">Stock tersisa {{ $cart->stockCountIn() }}
                                                    qty</div>
                                            </div>
                                            @if ($cart->type->type == 'meteran')
                                                <div>
                                                    <label for="qty"
                                                        class="form-label {{ $cart->unit ? '' : 'text-danger' }} fs-2">
                                                        {{ $cart->unit ? 'Panjang (cm) per Qty' : 'Satuan produk belum diatur' }}</label>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <input type="number" name="length[]"
                                                            id="length_{{ $item['id'] }}"
                                                            data-index="{{ $loop->index }}"
                                                            class="form-control form-control-sm length-input"
                                                            data-id="{{ $item['id'] }}"
                                                            value="{{ old('length['.$loop->index.']', $codproduct?->length ?? ($item['length'] ?? 0)) }}"
                                                            min="1" max="{{ $cart->width }}"
                                                            {{ $cod->is_finished != 0 ? 'disabled' : '' }} />
                                                    </div>
                                                    <div class="fs-1 mt-1 text-muted">Maksimal Panjang Lebar
                                                        {{ $cart->width }}cm</div>
                                                </div>
                                            @else
                                                <input type="hidden" name="length[]" value="">
                                            @endif
                                            <div class="flex-grow-1">
                                                <label for="price_sale" class="form-label fs-2">Dengan Harga Jual</label>
                                                <input type="text" name="price_sale[]"
                                                    id="price_sale_{{ $item['id'] }}"
                                                    class="form-control form-control-sm input-rupiah price-sale-input"
                                                    data-id="{{ $item['id'] }}"
                                                   value="{{ formatRupiah(old('price_sale['.$loop->index.']', $priceSale)) }}" min="0"
                                                    {{ $cod->is_finished != 0 ? 'disabled' : '' }} />
                                                <div class="fs-1 mt-1 text-muted">Penetapan Harga Jual
                                                    {{ formatRupiah($cart->price_per_unit) }}</div>
                                            </div>
                                            <button class="btn btn-sm btn-danger remove-cart align-self-center"
                                                data-url="{{ route('customer-order.removeCart', $cod->id) }}"
                                                data-product-id="{{ $item['id'] }}"
                                                data-reqorder-id="{{ $cod->id }}"
                                                {{ $cod->is_finished != 0 ? 'disabled' : '' }}>
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>

                                        <div class="p-3 mt-2 bg-primary-subtle rounded-3 mb-4">
                                          <div class="">
                                             <div class="form-check form-switch">
                                                @php
                                                   $isChecked = ($cart->stockCountIn() == 0 || old('is_from_partner['.$loop->index.']')) && !$cod->is_finished;
                                                @endphp
                                                @if(!$isChecked)
                                                   <input type="hidden" id="zeroPartner-{{$loop->index}}" name="is_from_partner[]" value="0" />
                                                @endif
                                                <input class="form-check-input check-from-partner" name="is_from_partner[]" data-item-id="{{$loop->index}}" type="checkbox" value="1" id="checkFromPartner{{$loop->index}}"
                                                      data-product-id="{{ $item['id'] }}" 
                                                      data-index="{{ $loop->index }}"
                                                      data-is-meteran="{{ $cart->type->type == 'meteran' ? '1' : '0' }}"
                                                      {{ $isChecked ? 'checked' : '' }}
                                                />
                                                <label class="form-check-label fs-2" for="checkFromPartner{{$loop->index}}">Barang diambil dari Partner</label>
                                             </div>
                                          </div>
                                          
                                          <div class="mt-2 {{$cart->stockCountIn() > 0 || $codproduct?->store_id != null ? '' : 'd-none'}}" id="store_input_{{$loop->index}}">
                                             <label class="form-label fw-semibold fs-2">Mengambil Dari Toko</label>
                                             <div class="input-group">
                                                   <span class="input-group-text px-6" id="basic-addon1"><i
                                                         class="ti ti-building-community fs-6"></i></span>
                                                   <div style="flex-grow:1">
                                                      <select name="store_id[]" id="store_id_{{$loop->index}}" class="select2-normal store-select form-select"
                                                            data-product-id="{{ $item['id'] }}" 
                                                            data-index="{{ $loop->index }}"
                                                            data-is-meteran="{{ $cart->type->type == 'meteran' ? '1' : '0' }}"
                                                      >
                                                         <option value="">-- Pilih Store --</option>
                                                         @foreach ($stores as $store)
                                                               @if((int) $store->getProductRemaining($item['id']) > 0)
                                                                  <option value="{{$store->id}}" {{$store->id == old('store_id['.$loop->index.']', $codproduct?->store_id) ? 'selected' : ''}} style="white-space:normal !important">{{ "{$store->name}, {$store->address} - {$store->city}" }}</option>
                                                               @endif
                                                         @endforeach
                                                      </select>
                                                   </div>
                                             </div>
                                             @error('store_id')
                                                   <span class="invalid-feedback" role="alert">
                                                      {{ $message }}
                                                   </span>
                                             @enderror
                                          </div>

                                          <div class="{{$cart->stockCountIn() > 0 || $codproduct?->store_id != null ? 'd-none' : ''}} mt-2" id="partner_input_{{$loop->index}}">
                                             <label class="form-label fw-semibold fs-2">Mengambil Dari Partner</label>
                                             <div class="input-group">
                                                   <span class="input-group-text px-6" id="basic-addon1"><i
                                                         class="ti ti-building-community fs-6"></i></span>
                                                   <div style="flex-grow:1">
                                                      <select name="partner_id[]" id="partner_id_{{$loop->index}}" class="select2-normal form-select">
                                                         <option value="">-- Pilih Partner --</option>
                                                         @foreach ($partners as $partner)
                                                               <option value="{{$partner->id}}" {{$partner->id == old('partner_id['.$loop->index.']', $codproduct?->partner_id) ? 'selected' : ''}}>{{$partner->name.', '.$partner->address}}</option>
                                                         @endforeach
                                                      </select>
                                                   </div>
                                             </div>
                                             @error('partner_id')
                                                   <span class="invalid-feedback" role="alert">
                                                      {{ $message }}
                                                   </span>
                                             @enderror

                                             <div class="mt-2">
                                                <label for="price_buy" class="form-label fs-2">Dengan Harga</label>
                                                <input type="text" name="price_buy[]"
                                                    id="price_buy_{{ $item['id'] }}"
                                                    class="form-control bg-white form-control-sm input-rupiah price-sale-input"
                                                    data-id="{{ $item['id'] }}"
                                                    value="{{ formatRupiah($priceSale) }}" min="0"
                                                    {{ $cod->is_finished != 0 ? 'disabled' : '' }} />
                                                <div class="fs-1 mt-1 text-muted">Harga harus lebih rendah dari harga jual</div>
                                            </div>
                                          </div>

                                          <div class="">
                                             <label class="form-label fs-2 fw-semibold">Catatan / Keterangan</label>
                                             <div class="input-group">
                                                <span class="input-group-text px-6" id="basic-addon1"><i
                                                         class="ti ti-align-justified fs-6"></i></span>
                                                <textarea class="form-control bg-white ps-2" name="desc[]" id="desc_{{$loop->index}}" cols="20" rows="2"
                                                      placeholder="Catatan...">{{ old('desc['.$loop->index.']', $codproduct?->description) }}</textarea>
                                             </div>
                                             @error('desc')
                                                <span class="invalid-feedback" role="alert">
                                                      {{ $message }}
                                                </span>
                                             @enderror
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
                                    <div class="d-flex align-items-center mb-2 gap-2 justify-content-between">
                                        <div class="fs-3 fw-semibold">Total</div>
                                        <div class="fs-3 fs-sm-4 fw-bold" id="totalPrice">
                                            {{ formatRupiah($totalPrice) }}</div>
                                        <input type="hidden" name="total_price" value="" id="totalPriceInput">
                                    </div>
                                    <div class="d-flex align-items-center mb-2 gap-2 justify-content-between">
                                        <div class="fs-3 fw-semibold" style="white-space: nowrap">Pajak / VAT</div>
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="number" name="tax"
                                                value="{{ old('tax', $cod->tax) ?? setting('site.ppn') }}"
                                                id="tax" placeholder="0" style="width: 5em;"
                                                class="form-control text-center form-control-sm">
                                            %
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 justify-content-between">
                                        <div class="fs-3 fw-semibold">Total Termasuk Pajak</div>
                                        <div class="fs-3 fs-sm-4 fw-bold" id="totalPriceTaxed">-</div>
                                        <input type="hidden" name="total_price_taxed" value=""
                                            id="totalPriceTaxedInput">
                                    </div>
                                    <div class="d-flex align-items-center gap-2 mt-2">
                                        <button type="button" id="refetchBtn"
                                            class="btn btn-secondary {{ count($carts) == 0 ? 'd-none' : '' }}"
                                            title="Muat Ulang Pemrosesan Produk"
                                            {{ $cod->is_finished != 0 ? 'disabled' : '' }}><i
                                                class="ti ti-refresh"></i></button>
                                        <button type="submit" class="btn btn-primary w-100"
                                            {{ $cod->is_finished != 0 ? 'disabled' : '' }}>Simpan dan Lanjut ke
                                            Pembayaran</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="step-content" data-step="3" style="display: none;">
                <div class="d-flex mb-3 align-items-center gap-3">
                    <i class="ti ti-credit-card fs-9"></i>
                    <h5 class="mb-0">Pembayaran</h5>
                </div>

                <form action="{{route('customer-order.confirmPayment', $cod->id)}}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="row">
                     <div class="col-12 col-md-6">
                        <div class="card rounded-5">
                           <div class="card-body">
                              <div class="mb-4">
                                 @if(!$cod->payment_receipt)
                                 <div id="placeholder-image"
                                    class="d-flex p-5 text-center align-items-center justify-content-center rounded-5 border-2 border-dashed"
                                    style="aspect-ratio:1/1">
                                    <div>
                                       <div class="fs-4">If Image Selected, it will show (Preview)</div>
                                    </div>
                                 </div>
                                 @endif
                                 <img src="{{ $cod->payment_receipt ? '/storage/'.$cod->payment_receipt : '' }}" id="preview-image" alt="Product Image Preview" class="{{ $cod->payment_receipt ? '' : 'd-none' }} rounded-4 shadow w-100"
                                    style="">
                              </div>

                              <div class="mb-4">
                                 <label class="form-label fw-semibold">Bukti Transfer / Nota Pembayaran</label>
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
                           </div>
                        </div>
                     </div>

                     <div class="col-12 col-md-6">
                        <div class="card">
                           <div class="card-header fw-bold">
                              Metode Pembayaran
                           </div>
                           <div class="card-body">
                              @foreach($payments as $payment)
                                 <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="payment_id" id="payment_{{ $payment->id }}" value="{{ $payment->id }}" {{ $cod->payment_id == $payment->id ? 'checked' : '' }} {{ $cod->is_finished != 0 ? 'disabled' : '' }}>
                                    <label class="form-check-label d-flex gap-3 align-items-start" for="payment_{{ $payment->id }}" style="cursor: pointer !important">
                                       @if($payment->type === 'bank')
                                          <img src="{{ $payment->image ? '/storage/'.$payment->image : '/assets/images/logos/bank.png' }}" alt="{{ $payment->bank_name }}" style="width:48px;height:48px;object-fit:contain;">
                                          <div>
                                             <div class="fw-semibold">{{ $payment->bank_name }}</div>
                                             <div class="text-muted fs-2 fw-bold">{{ $payment->account_number }}</div>
                                             <div class="text-muted fs-2">a.n. {{ $payment->account_name }}</div>
                                          </div>
                                       @elseif($payment->type === 'qris')
                                          <div class="w-100">
                                             <div class="d-flex align-items-center gap-2 w-100">
                                                <img src="{{ $payment->image ? '/storage/'.$payment->image : '/assets/images/logos/bank.png' }}" alt="QRIS" style="width:64px;height:64px;object-fit:contain;">
                                                <div class="flex-1 w-100">
                                                   <div>
                                                      <div class="fw-semibold">QRIS : {{ $payment->bank_name }}</div>
                                                      <div class="text-muted fs-2">a.n {{ $payment->account_name }}</div>
                                                   </div>
                                                </div>
                                                <button type="button" data-id="{{$loop->index}}" class="btn btn-secondary btn-sm btn-show-qris ms-auto" style="white-space: nowrap;">Lihat QR</button>
                                             </div>
                                             <img src="{{ $payment->image ? '/storage/'.$payment->image : '/assets/images/logos/bank.png' }}" class="w-100 d-none" id="showQRIS-{{$loop->index}}" alt="QRIS" style="object-fit:contain;">
                                          </div>
                                       @endif
                                    </label>
                                 </div>
                              @endforeach
                           </div>
                        </div>
                     </div>
                  </div>

                  <div class="pb-4 position-sticky bottom-0">
                     <button
                        type="button" 
                        class="btn btn-primary w-100"
                        data-bs-toggle="modal" data-bs-target="#confirmPaymentModal"
                        {{ $cod->is_finished != 0 ? 'disabled' : '' }}
                     >
                        Konfirmasi Pembayaran
                     </button>

                  </div>
                  <!-- Payment Confirmation Modal -->
                  <div id="confirmPaymentModal" class="modal fade" tabindex="-1"
                     aria-labelledby="danger-header-modalLabel" aria-hidden="true">
                     <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                        <div class="modal-content p-3 modal-filled bg-warning">
                           <div class="modal-header modal-colored-header text-white">
                                 <h4 class="modal-title text-white" id="danger-header-modalLabel">
                                    Konfirmasi Pembayaran ?
                                 </h4>
                                 <button type="button" class="btn-close btn-close-white"
                                    data-bs-dismiss="modal" aria-label="Close"></button>
                           </div>
                           <div class="modal-body" style="width: fit-content; white-space:normal">
                                 <h5 class="mt-0 text-white">Klik untuk mengonfirmasi pembayaran</h5>
                                 <p class="text-white">Pastikan telah mengecek dan memastikan pembayaran diterima dengan baik! Karena transaksi tidak bisa diedit.</p>
                           </div>
                           <div class="modal-footer">
                                 <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                    Close
                                 </button>
                                 <button type="submit" class="btn btn-dark">Konfirmasi Dibayar</button>
                           </div>
                        </div>
                     </div>
                  </div>
                </form>
            </div>

            <div class="step-content" data-step="4" style="display: none;">
                <div class="d-flex mb-3 align-items-center gap-3">
                    <i class="ti ti-building-community fs-9"></i>
                    <h5 class="mb-0">Partners</h5>
                    <div class="d-flex align-items-center justify-content-center bg-primary text-white p-2 rounded-circle"
                        style="aspect-ratio:1/1; width:2.5em; height:2.5em">
                        {{ $cod->partnerDebts->count() }}
                    </div>
                </div>

                @forelse ($cod->partnerDebts as $pdebt)
                    <div class="card">
                        <div class="card-body">
                            <div class="btn-accordion p-3 border border-2 rounded-3 border-dashed">
                                <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between"
                                    style="cursor: pointer">
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Partner
                                        </div>
                                        <h6 class="fw-semibold text-primary mb-1" style="">{{ $pdebt->partner?->name }}
                                        </h6>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Total Hutang
                                        </div>
                                        <h6 class="fw-semibold text-dark mb-1" style="">{{ formatRupiah($pdebt->total_debt)}}
                                        </h6>
                                    </div>
                                    @php
                                        $status = [
                                            '0' => ['label' => 'Belum Dibayar', 'color' => 'warning'],
                                            '1' => ['label' => 'Dibayar', 'color' => 'success'],
                                        ];
                                    @endphp
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="">Status Pembayaran
                                        </div>
                                        <h6 class="fw-semibold fs-2 text-{{ $status[$pdebt->is_paid]['color'] }} mb-1"
                                            style="">{{ $status[$pdebt->is_paid]['label'] }}</h6>
                                    </div>
                                    <div
                                        class="flex-grow-1 text-end mb-2 d-flex align-items-center justify-content-end gap-2">
                                        <div class="fs-1 text-muted">Klik untuk melihat detail</div>
                                    </div>
                                </div>
                                 @if($pdebt->is_paid == 0)
                                    <button class="w-100 btn btn-primary mt-2" data-bs-toggle="modal"
                                       data-bs-target="#payPartner-{{ $pdebt->id }}"
                                    >
                                       Bayar ke Partner
                                    </button>

                                    @include('customer_orders.partner_debts.addmodal', ['id' => "payPartner-".$pdebt->id, 'debtId' => $pdebt->id, 'codId' => $pdebt->customerOrderProduct->customerOrder->id])
                                 @endif
                            </div>
                            <div class="btn-accordion-content row mt-3">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div>
                                        <div class="d-flex align-items-center" style="width:15em">
                                            <img src="{{ $pdebt->partner?->image ? asset('/storage/' . $pdebt->partner?->image) : '/assets/images/profile/user-1.jpg' }}"
                                                class="rounded-2" alt="partner Image {{ $pdebt->partner?->name }}"
                                                style="width: 4em" />
                                            <div class="ms-3">
                                                <h6 class="fw-semibold mb-1" style="white-space: normal !important">
                                                    {{ $pdebt->partner?->name }}</h6>
                                                <div class="fw-normal text-muted"
                                                    style="white-space:normal; font-size:13px; ">
                                                    {{ $pdebt->partner?->description ?? 'Tidak ada deskripsi' }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="fw-normal fs-2" style="white-space:normal; font-size:13px; ">
                                            {{ $pdebt->partner?->address }}</div>
                                        <div class="text-primary fs-2">{{ $pdebt->partner?->city }}.
                                            {{ $pdebt->partner?->postal_code }}</div>
                                    </div>
                                    @if($pdebt->is_paid && $pdebt->image)
                                    <div class="mt-2">
                                       <a href="{{'/storage/'.$pdebt->image}}" target="_blank" class="btn btn-secondary"><i class="ti ti-file me-2"></i> Bukti Pembayaran</a>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div>
                                        <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                            <i class="ti ti-building-bank mb-0 fs-3"></i> {{ $pdebt->partner?->npwp }}
                                        </div>
                                        <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                            <i class="ti ti-mail mb-0 fs-3"></i> {{ $pdebt->partner?->email }}
                                        </div>
                                        <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                            <i class="ti ti-phone mb-0 fs-3"></i> {{ $pdebt->partner?->phone }}
                                        </div>
                                        <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                            <i class="ti ti-phone-check mb-0 fs-3"></i> {{ $pdebt->partner?->fax }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mt-3">
                                    @php
                                       $codProduct = $pdebt->customerOrderProduct;
                                       $priceSale = $codProduct->price_sale ?? 0;
                                       $subtotal = $priceSale * $codProduct->qty; // Hitung subtotal awal
                                    @endphp
                                    <div class="border border-1 border-dashed border-primary mb-2 p-3 rounded-3">
                                       <div
                                             class="d-flex align-items-center gap-2 mb-2 {{ $loop->index == 0 ? '' : 'mt-3' }}">
                                             <img src="{{ $codProduct?->product?->image ? '/storage/' . $codProduct?->product?->image : 'https://placehold.co/300?text=' . $codProduct?->product?->name }}"
                                                alt="Image Product {{ $codProduct?->product?->name }} in Cart" class="d-block rounded-2"
                                                style="width: 5em; height:5em; object-fit:cover">
                                             <div>
                                                <div class="text-decoration-none text-dark fs-3 fw-semibold">
                                                   {{ $codProduct?->product?->name }}</div>
                                                <div class="text-muted fs-2 mb-2">
                                                   {{ $codProduct?->product?->description ?? 'tidak ada deskripsi' }}</div>
                                             </div>
                                             <div
                                                class="flex-grow-1 d-flex flex-column align-items-end gap-2 justify-content-between">
                                                <div class="fs-2 fw-semibold">Sub Total</div>
                                                <div class="fs-3 fw-bold subtotal">
                                                   {{ formatRupiah($subtotal) }}</div>
                                             </div>
                                       </div>
                                       <div class="d-flex align-items-end gap-2">
                                             <div>
                                                <label for="qty" class="text-muted fs-1">Memproses Sebanyak
                                                   ({{ $codProduct?->product?->unit->code }})</label>
                                                <div class="d-flex align-items-center gap-2">
                                                   {{ $codProduct->qty }}
                                                </div>
                                             </div>
                                             <div class="flex-grow-1">
                                                <label for="price_buy" class="text-muted fs-1">Dengan Harga Beli</label>
                                                <div>{{ formatRupiah($codProduct->price_buy) }}</div>
                                             </div>
                                       </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center border border-dash">
                        Tidak ditemukan keterlibatan Partner dalam Penjualan Instan ke Pelanggan ini.
                    </div>
                @endforelse

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
                'pembayaran': 3,
                'partner': 4,
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
                let modal = $(this).closest('.modal'); // Cari modal terdekat
                $(this).select2();
            });

            $('#client_id').on('change', async function() {
                fetchShowBy({
                    url: "{{ route('ajax.showBy') }}",
                    model: "ClientPic", // Model yang akan di-fetch
                    key: "client_id", // Kolom yang digunakan untuk filter
                    data: $(this).val(), // Ambil nilai dari selector
                    isCollection: true, // Apakah hasil koleksi?
                    affectSelectorId: '#client_pic_id', // Selector untuk menerima data
                    optionPlaceholder: '-- Pilih PIC Client --' // Placeholder untuk opsi pertama
                });
            });
            let reqorder = @json($cod);
            if (reqorder.id) {
                $('#client_id').val(reqorder.client_id).trigger('change');
                setTimeout(() => {
                    $('#client_pic_id').val(reqorder.client_pic_id).trigger(
                        'change'); // Pilih PIC sesuai dengan reqorder
                }, 500);
            }

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
                let id = "{{ $cod->id }}"
                $.ajax({
                    url: "{{ route('customer-order.refetch', $cod->id) }}",
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

            $('#btnEditData').on('click', function() {
                $('#showDataBox').addClass('d-none');
                $('#editDataBox').removeClass('d-none');
            });

            $('#btnCloseData').on('click', function() {
                $('#showDataBox').removeClass('d-none');
                $('#editDataBox').addClass('d-none');
            });

            $(function(){
                  // Toggle partner input visibility based on checkbox
                  $('.check-from-partner').on('change', function(){
                     let idx = $(this).data('item-id');
                     if($(this).is(':checked')) {
                        $('#partner_input_'+idx).removeClass('d-none');
                        $('#store_input_'+idx).addClass('d-none');
                        // Remove hidden input if exists
                        $('#zeroPartner-'+idx).remove();
                     } else {
                        $('#partner_input_'+idx).addClass('d-none');
                        $('#store_input_'+idx).removeClass('d-none');
                        // Add hidden input if not exists
                        if($('#zeroPartner-'+idx).length === 0) {
                              $('<input type="hidden" id="zeroPartner-'+idx+'" name="is_from_partner[]" value="0" />').insertBefore($(this));
                        }
                     }
                  });
            });
        });
        
        $(document).ready(function() {
            // Tutup semua content accordion saat halaman dimuat
            $(".btn-accordion-content").hide();

            // Tambahkan event klik untuk setiap .btn-accordion
            $(".btn-accordion").on("click", function() {
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
                    const previewImage = formContainer.querySelector('#preview-image');

                    const file = fileInput.files[0];

                    if (file) {
                        if (file.type.startsWith('image/')) {
                            // Preview image
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                previewImage.src = e.target.result;
                                previewImage.classList.remove('d-none'); // Show image preview
                                placeholder.classList.add('d-none'); // Hide placeholder
                            };
                            reader.readAsDataURL(file);
                        } else {
                            // Preview file
                            previewImage.classList.add('d-none'); // Hide image preview
                            placeholder.classList.add('d-none'); // Hide placeholder
                        }
                    } else {
                        // Reset previews
                        previewImage.src = '';
                        previewImage.classList.add('d-none');
                        placeholder.classList.remove('d-none'); // Show placeholder
                    }
                }
            });

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
                    let priceSaleInput = document.getElementById(`price_sale_${id}`);
                    let priceSale = cleanRupiah(priceSaleInput
                        .value); // Bersihkan format "Rp 2.312" menjadi 2312
                    let subtotal = priceSale * qty;

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

            $('#tax').on('input', updateTotalWithTax)

            document.querySelectorAll('.qty-input, .price-sale-input').forEach(input => {
                input.addEventListener('input', updateSubtotalAndTotal);
            });

            // Event listener untuk memastikan price_sale tetap dalam format Rupiah saat diinput
            document.querySelectorAll('.price-sale-input').forEach(input => {
                input.addEventListener('blur', function() {
                    let value = cleanRupiah(this.value); // Bersihkan nilai
                    this.value = formatRupiah(value); // Format kembali ke Rp setelah edit
                });
            });

            // Jalankan update awal saat halaman dimuat
            updateSubtotalAndTotal();
        });

        $('.btn-show-qris').on('click', function() {
            let index = $(this).data('id');
            let qrisImage = $('#showQRIS-' + index);
            if (qrisImage.hasClass('d-none')) {
                qrisImage.removeClass('d-none');
                $(this).text('Sembunyikan QR');
            } else {
                qrisImage.addClass('d-none');
                $(this).text('Lihat QR');
            }
        });

        document.addEventListener("DOMContentLoaded", function () {
            function fetchStock(index) {
               const $store = $(`#store_id_${index}`);
               const storeId = $store.val();
               const productId = $store.data('product-id');
               const isMeteran = $store.data('is-meteran') == '1';
               const isFromPartner = $(`#checkFromPartner${index}`).is(':checked');
               const length = isMeteran ? $(`#length_${productId}`).val() : null;
               const $stockInfo = $(`#stock_info_${productId}`);

               if (isFromPartner || !storeId) {
                  $stockInfo.html('');
                  return;
               }

               $.ajax({
                  url: "{{ route('product.checklength') }}",
                  method: "GET",
                  data: {
                     product_id: productId,
                     store_id: storeId,
                     length: length
                  },
                  success: function (res) {
                     if (res.success) {
                        if (isMeteran) {
                           $stockInfo.html(`<span class="text-success">Tersedia ${res.qty} roll</span>`);
                        } else {
                           $stockInfo.html(`<span class="text-success">Tersedia ${res.qty} qty</span>`);
                        }
                     } else {
                        $stockInfo.html(`<span class="text-danger">${res.message}</span>`);
                     }
                  },
                  error: function (xhr) {
                     const msg = xhr.responseJSON?.message ?? 'Terjadi kesalahan saat mengambil stok.';
                     $stockInfo.html(`<span class="text-danger">${msg}</span>`);
                  }
               });
            }

            $('.store-select, .check-from-partner').on('change', function () {
               const index = $(this).data('index');
               fetchStock(index);
            });

            $('.length-input').on('input change', function () {
               const index = $(this).data('index');
               fetchStock(index);
            });
         });
    </script>
@endsection
