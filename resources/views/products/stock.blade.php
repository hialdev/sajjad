@extends('layouts.base')
@section('css')
@endsection
@section('content')
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Sebaran Stock Produk : {{$product->name}}</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('product.index') }}">Produk</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Semua Sebaran Stock Produk : {{$product->name}}</li>
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

    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <img src="{{ $product->image ? asset('/storage/'.$product->image) : '/assets/images/profile/user-1.jpg' }}"
                    class="rounded-2" alt="product Image {{ $product->name }}" style="width: 4em" />
                <div class="ms-3">
                    <div class="d-flex align-items-center gap-1">
                        <div class="badge bg-primary fs-1 mb-1 text-white">{{ $product->code }}</div>
                        <div class="badge bg-secondary fs-1 mb-1 text-white">{{ $product->type->type }}</div>
                    </div>
                    <h6 class="fw-semibold mb-1" style="white-space: normal !important">{{ $product->name }}</h6>
                    <div class="d-flex align-items-center gap-2">
                        <div><i class="ti ti-arrow-up"></i> {{ $product->height }} cm</div>
                        <div><i class="ti ti-arrow-right"></i> {{ $product->width }} cm</div>
                    </div>
                </div>
                <div class="ms-auto">
                  <div class="fs-2 fw-bold mb-1">Total Stock</div>
                  <div
                    class="bg-primary text-white d-flex align-items-center justify-content-center px-2 p-1 rounded-5 me-auto">
                    {{ $product->stock_count}}</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mb-3 d-flex align-items-center gap-4">
        <h1>Sebaran Stock</h1>
        <div class="d-flex align-items-center gap-1">
            <div class="bg-primary-subtle text-primary rounded-2 fs-3 px-2 p-1"><i class="ti ti-building-warehouse me-2"></i> {{ count($product->getAnalyticAndLocations()['warehouse']) }}</div>
            <div class="bg-primary-subtle text-primary rounded-2 fs-3 px-2 p-1"><i class="ti ti-building-store me-2"></i> {{ count($product->getAnalyticAndLocations()['store']) }}</div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-none bg-light">
                <div class="card-body">
                    <div class="fs-5 fw-bold"><i class="ti ti-building-warehouse me-2"></i> Gudang</div>

                    @forelse ($product->getAnalyticAndLocations()['warehouse'] as $wdistribute)
                        @php
                            $wloc = $wdistribute->location;
                        @endphp
                        <div class="bg-white rounded-3 p-3 my-3 position-relative overflow-hidden">
                            <div class="position-absolute top-0 end-0 me-3 p-2 px-3 rounded-bottom-3 bg-primary text-white">
                                <div class="fs-1 text-muted text-white">Terdapat</div>
                                <div class="fs-3 fw-bold">{{ $wdistribute->qty }}</div>
                            </div>

                            <div class="d-flex align-items-center" style="width:15em">
                                <img src="{{ $wloc->image ? asset('/storage/'.$wloc->image) : '/assets/images/profile/user-1.jpg' }}"
                                    class="rounded-2" alt="Client Image {{ $wloc->name }}" style="width: 4em" />
                                <div class="ms-3">
                                    <h6 class="fw-semibold mb-1" style="white-space: normal !important">{{ $wloc->name }}</h6>
                                    <div class="fw-normal text-muted" style="white-space:normal; font-size:13px; ">{{ $wloc->description ?? 'Tidak ada deskripsi' }}</div>    
                                </div>
                            </div>

                            <div class="mb-2">
                                <div class="fw-normal fs-2" style="white-space:normal; font-size:13px; ">{{ $wloc->address }}</div>    
                                <div class="text-primary fs-2">{{ $wloc->city }}. {{$wloc->postal_code}}</div>
                            </div>

                            <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                <i class="ti ti-mail mb-0 fs-3"></i> {{ $wloc->email}}
                            </div>
                            <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                <i class="ti ti-phone mb-0 fs-3"></i> {{ $wloc->phone}}
                            </div>
                            <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                <i class="ti ti-phone-check mb-0 fs-3"></i> {{ $wloc->fax}}
                            </div>
                        </div>
                    @empty
                        <div class="fw-bold text-center p-3 rounded-3 border-2 border-dashed mt-3">Tidak ada stock yang tersebar disini</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-none bg-light">
                <div class="card-body">
                  <div class="fs-5 fw-bold"><i class="ti ti-building-store me-2"></i> Toko</div>

                  @forelse ($product->getAnalyticAndLocations()['store'] as $sdistribute)
                        @php
                            $sloc = $sdistribute->location;
                        @endphp
                        <div class="bg-white rounded-3 p-3 my-3 position-relative overflow-hidden">
                            <div class="position-absolute top-0 end-0 me-3 p-2 px-3 rounded-bottom-3 bg-primary text-white">
                                <div class="fs-1 text-muted text-white">Terdapat</div>
                                <div class="fs-3 fw-bold">{{ $sdistribute->qty }}</div>
                            </div>

                            <div class="d-flex align-items-center" style="width:15em">
                                <img src="{{ $sloc->image ? asset('/storage/'.$sloc->image) : '/assets/images/profile/user-1.jpg' }}"
                                    class="rounded-2" alt="Client Image {{ $sloc->name }}" style="width: 4em" />
                                <div class="ms-3">
                                    <h6 class="fw-semibold mb-1" style="white-space: normal !important">{{ $sloc->name }}</h6>
                                    <div class="fw-normal text-muted" style="white-space:normal; font-size:13px; ">{{ $sloc->description ?? 'Tidak ada deskripsi' }}</div>    
                                </div>
                            </div>

                            <div class="mb-2">
                                <div class="fw-normal fs-2" style="white-space:normal; font-size:13px; ">{{ $sloc->address }}</div>    
                                <div class="text-primary fs-2">{{ $sloc->city }}. {{$sloc->postal_code}}</div>
                            </div>

                            <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                <i class="ti ti-mail mb-0 fs-3"></i> {{ $sloc->email}}
                            </div>
                            <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                <i class="ti ti-phone mb-0 fs-3"></i> {{ $sloc->phone}}
                            </div>
                            <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                <i class="ti ti-phone-check mb-0 fs-3"></i> {{ $sloc->fax}}
                            </div>
                        </div>
                    @empty
                        <div class="fw-bold text-center p-3 rounded-3 border-2 border-dashed mt-3">Tidak ada stock yang tersebar disini</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
@endsection
