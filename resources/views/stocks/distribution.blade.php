@extends('layouts.base')
@section('css')
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
                                <a class="text-muted text-decoration-none" href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Semua Pembelian</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="/assets/images/breadcrumb/ChatBc.png" alt="" class="img-fluid mb-n4" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3 d-flex align-items-center gap-2 justify-content-between">
        <h1>Distribusi Barang / Stock</h1>
        <div style="aspect-ratio:1/1; width:3em; height:3em"
            class="bg-primary text-white d-flex align-items-center justify-content-center rounded-5 me-auto">
            {{ count($distributions) }}</div>
        <a href="{{ route('stock.move') }}" class="btn btn-primary btn-al-primary">Tambah</a>
        {{-- <a href="{{route('pdf.preview.blade', ['bladePath' => 'Clients.stok'])}}" target="_blank" class="btn btn-danger"><i class="ti ti-file-download me-2"></i>Laporan Stok</a> --}}
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('stock.index') }}" method="GET">
                <div class="row align-items-end mb-3 flex-wrap">
                    <div class="col-md-4 mb-2">
                        <label for="search" class="form-label">Filter Kata</label>
                        <input type="text" class="form-control" placeholder="Cari Kode / Deskripsi" name="search"
                            value="{{ $filter->q ?? '' }}">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="field" class="form-label">Urutkan Berdasarkan</label>
                        <select name="field" id="field" class="form-select">
                            @foreach (getModelAttributes('PurchaseReceive', []) as $atr)
                                <option value="{{ $atr }}" {{ $filter->field == $atr ? 'selected' : '' }}>
                                    {{ toPascalCase($atr) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="order" class="form-label">Dengan urutan</label>
                        <select name="order" id="order" class="form-select">
                            <option value="newest" {{ $filter->order == 'desc' ? 'selected' : '' }}>Terbaru / Terbesar
                            </option>
                            <option value="oldest" {{ $filter->order == 'asc' ? 'selected' : '' }}>Terlama / Terkecil
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <div class="d-flex align-items-center gap-1">
                            <button type="submit" class="btn btn-primary w-100" style="white-space: nowrap">Apply</button>
                            <a href="{{ url()->current() }}" class="btn btn-secondary" style="white-space: nowrap">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="M22 12c0 5.523-4.477 10-10 10S2 17.523 2 12S6.477 2 12 2v2a8 8 0 1 0 4.5 1.385V8h-2V2h6v2H18a9.99 9.99 0 0 1 4 8" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table border text-nowrap mb-0 align-middle">
                    <thead class="text-dark fs-4">
                        <tr>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Penerimaan</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Detail</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Barang Distribusi</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Timestamp</h6>
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($distributions as $distribution)
                            <tr>
                                <td>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Tanggal</div>
                                        <h6 class="fs-2 fw-semibold text-success mb-1" style="">
                                            {{ \Carbon\Carbon::parse($distribution->date)->format('d F Y') }}</h6>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Detail Penerimaan
                                        </div>
                                        <h6 class="fw-semibold text-primary mb-1" style="">{{ $distribution->code }}</h6>
                                    </div>
                                    <div class=""><i class="ti ti-user-circle me-2"></i> {{ $distribution->user->name }}</div>
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
                                        <h6 class="fw-semibold fs-2 text-{{ $statuses[$distribution->status]['color'] }} mb-1" style="">{{ $statuses[$distribution->status]['label'] }}</h6>
                                    </div>
                                </td>
                                <td>
                                    <div class="mb-1">
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Barang Dari</div>
                                        <div class=""><i class="ti ti-building-{{$distribution->from_type}} me-2"></i> {{ $distribution->from->name }}</div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Distribusi Ke</div>
                                        <div class=""><i class="ti ti-building-{{$distribution->to_type}} me-2"></i> {{ $distribution->to->name }}</div>
                                    </div>
                                    <div style="min-width: 10em">
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Deksripsi</div>
                                        <p class="mb-1 fs-2" style="white-space:normal !important;">{{ $distribution->description ?? 'tidak ada deskripsi' }}</p>
                                    </div>
                                </td>
                                <td>
                                    <button type="button"
                                        class="dropdown-item fs-2 text-center d-inline-flex p-2 px-3 align-items-center gap-2 bg-secondary text-white rounded-3"
                                        data-bs-toggle="modal" data-bs-target="#produkModal-{{$distribution->id}}"><i
                                            class="fs-4 ti ti-package"></i> {{ count($distribution->products) }} Produk</button>

                                    <!-- List Product modal -->
                                    <div class="modal fade " id="produkModal-{{$distribution->id}}" tabindex="-1"
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
                                                    @forelse ($distribution->products as $dproduct)
                                                        <div class="border border-1 border-dashed border-primary {{$loop->index+1 == count($distribution->products) ? '' : 'mb-2'}} p-3 rounded-3">
                                                            <div
                                                                class="d-flex align-items-center gap-2 mb-2 {{ $loop->index == 0 ? '' : 'mt-3' }}">
                                                                <img src="{{ $dproduct->product->image ? '/storage/' . $dproduct->product->image : 'https://placehold.co/300?text=' . $dproduct->product->name }}"
                                                                    alt="Image Product {{ $dproduct->product->name }} in Cart" class="d-block rounded-2"
                                                                    style="width: 5em; height:5em; object-fit:cover">
                                                                <div>
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
                                                                    <div class="fs-2">{{ !$dproduct->description || $dproduct->description == '' ? 'tidak ada keterangan' : $dproduct->description }}</div>
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
                                </td>
                                <td>
                                    <div class="d-flex flex-column align-items-start gap-2">
                                        <div class="badge bg-success-subtle text-success rounded-3 fw-semibold fs-2">
                                            Updated
                                            at
                                            : {{ $distribution->updated_at }}</div>
                                        <div class="badge bg-primary-subtle text-primary rounded-3 fw-semibold fs-2">
                                            Created
                                            at
                                            : {{ $distribution->created_at }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="dropdown dropstart">
                                        <a href="#" class="text-muted" id="dropdownMenuButton"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots fs-5"></i>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <li>
                                                <button type="button" class="dropdown-item d-flex align-items-center gap-3 text-danger"
                                                    onclick="seePDF('pdf.po','{{$distribution->id}}')"><i
                                                        class="fs-4 ti ti-printer"></i>Cetak Penerimaan</button>
                                            </li>
                                            <li>
                                                <a href="{{ route('stock.setting', $distribution->id) . '#data' }}"
                                                    class="dropdown-item text-primary d-flex align-items-center gap-3"><i
                                                        class="fs-4 ti ti-settings"></i>Kelola</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('stock.setting', $distribution->id) . '#produk' }}"
                                                    class="dropdown-item text-secondary d-flex align-items-center gap-3"><i
                                                        class="fs-4 ti ti-package"></i>Kelola Produk</a>
                                            </li>
                                            <li>
                                                <button type="button"
                                                    class="dropdown-item d-flex align-items-center gap-3"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal-{{ $distribution->id }}"><i
                                                        class="fs-4 ti ti-trash"></i>Delete</button>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div id="deleteModal-{{ $distribution->id }}" class="modal fade" tabindex="-1"
                                        aria-labelledby="danger-header-modalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                                            <div class="modal-content p-3 modal-filled bg-danger">
                                                <div class="modal-header modal-colored-header text-white">
                                                    <h4 class="modal-title text-white" id="danger-header-modalLabel">
                                                        Yakin ingin menghapus Pembelian {{ $distribution->code }} ?
                                                    </h4>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body" style="width: fit-content; white-space:normal">
                                                    <h5 class="mt-0 text-white">Distribusi Barang / Stock {{ $distribution->code }}
                                                        akan dihapus</h5>
                                                    <p class="text-white">Segala data yang berkaitan dengan Distribusi Barang / Stock
                                                        tersebut juga akan dihapus secara permanen.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                        Close
                                                    </button>
                                                    <form action="{{ route('stock.destroy', $distribution->id) }}"
                                                        method="POST">
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
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
@endsection
