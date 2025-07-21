@extends('layouts.base')
@section('css')
<link rel="stylesheet" href="/assets/libs/owl.carousel/dist/assets/owl.carousel.min.css" />
@endsection
@section('content')
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Stock Management</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Kelola Stock</li>
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
        <h1>Kelola Stock</h1>
        <a href="{{ route('stock.move') }}" class="btn btn-primary btn-al-primary"><i class="ti ti-truck-delivery me-1"></i> Distribusikan</a>
        {{-- <a href="{{route('pdf.preview.blade', ['bladePath' => 'Clients.stok'])}}" target="_blank" class="btn btn-danger"><i class="ti ti-file-download me-2"></i>Laporan Stok</a> --}}
    </div>

    <form action="{{ route('request-order.index') }}" method="GET">
        <div class="row align-items-end mb-3 flex-wrap">
            <div class="col-md-4 mb-2">
                <label for="search" class="form-label">Filter Kata</label>
                <input type="text" class="form-control" placeholder="Cari Kode" name="search"
                    value="{{ $filter->q ?? '' }}">
            </div>
            <div class="col-md-3 mb-2">
                <label for="field" class="form-label">Urutkan Berdasarkan</label>
                <select name="field" id="field" class="form-select">
                    @foreach (['total_stock', 'bal', 'jenis_product', 'stock_satuan', 'stock_meteran'] as $atr)
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

    <!-- Sebaran Stock -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                              <h5 class="card-title mb-9 fw-semibold">
                                Stock Meter Utuh dan Satuan
                              </h5>
                              <h4 class="fw-semibold mb-3 text-primary">{{$stockTotal->total}}</h4>
                              <div class="d-flex align-items-center">
                                  <div class="me-4">
                                      <span
                                        class="round-8 text-bg-primary rounded-circle me-2 d-inline-block"
                                      ></span>
                                      <span class="fs-2">Satuan ({{$stockTotal->satuan}})</span>
                                  </div>
                                  <div>
                                      <span
                                        class="round-8 bg-dark rounded-circle me-2 d-inline-block"
                                      ></span>
                                      <span class="fs-2">Meteran ({{$stockTotal->meteran}})</span>
                                  </div>
                              </div>
                          </div>
                          <div class="col-4">
                              <div class="d-flex justify-content-center">
                                  <div id="availableStock"></div>
                              </div>
                          </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                              <h5 class="card-title mb-9 fw-semibold">
                                Stock Meter dan Satuan Terjual
                              </h5>
                              <h4 class="fw-semibold mb-3 text-primary">{{$stockTotal->total}}</h4>
                              <div class="d-flex align-items-center">
                                  <div class="me-4">
                                      <span
                                        class="round-8 text-bg-primary rounded-circle me-2 d-inline-block"
                                      ></span>
                                      <span class="fs-2">Satuan ({{$stockTotal->satuan}})</span>
                                  </div>
                                  <div>
                                      <span
                                        class="round-8 bg-dark rounded-circle me-2 d-inline-block"
                                      ></span>
                                      <span class="fs-2">Meteran ({{$stockTotal->meteran}})</span>
                                  </div>
                              </div>
                          </div>
                          <div class="col-4">
                              <div class="d-flex justify-content-center">
                                  <div id="soldStock"></div>
                              </div>
                          </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">

            <div class="row">
              <div class="col-12 d-flex align-items-center gap-3 mb-3 ">
                <span class="text-primary fs-6">#</span>
                <h3 class="fs-5 mb-0"> Statistik Stock di Setiap Gudang</h3>
              </div>
              @foreach($warehouses as $warehouse)
              <div class="col-md-3">
                  <a href="{{route('warehouse.stock', $warehouse->id)}}" class="card ">
                    <div class="card-body">
                      <div class="d-flex align-items-center" style="width:15em">
                          <img src="{{ $warehouse->image ? asset('/storage/'.$warehouse->image) : '/assets/images/profile/user-1.jpg' }}"
                              class="rounded-2" alt="Client Image {{ $warehouse->name }}" style="width: 4em" />
                          <div class="ms-3">
                              <h6 class="fw-semibold mb-1" style="white-space: normal !important">{{ $warehouse->name }}</h6>
                              <div class="fw-normal fs-2 text-muted" style="white-space:normal; font-size:13px; ">{{ $warehouse->description ?? 'Tidak ada deskripsi' }}</div>    
                          </div>
                      </div>

                      <div class=" border-2 border-light-subtle border-dashed p-2 rounded-2 mt-3">
                        @php
                            $wanalytic = $warehouse->analytics()
                        @endphp
                        <div class="text-dark fs-2 fw-semibold d-flex align-items-center justify-content-center p-1 px-2 rounded-2 bg-primary-subtle mb-1" title="Total Jenis Satuan"><i class="ti ti-package me-2"></i> {{$wanalytic->stock->total_product}} Produk</div>
                        <div class="d-flex align-items-center pb-1 mb-1 justify-content-around">
                            <div class="text-dark fw-bold" title="Total Stock Satuan"><i class="ti ti-package me-2"></i> {{$wanalytic->stock->total_remaining}}</div>

                            <div class="d-flex align-items-center">
                                <div class="px-2 border-x-2 text-success fw-semibold">{{ $wanalytic->stock->total_in }}</div>
                                <div class="px-2 border-x-2 text-danger fw-semibold">{{ $wanalytic->stock->total_out }}</div>
                                <div class="px-2 border-x-2 text-warning fw-semibold">{{ $wanalytic->stock->total_onway }}</div>
                            </div>
                        </div>

                        <div class="text-dark fs-2 fw-semibold d-flex align-items-center justify-content-center p-1 px-2 rounded-2 bg-primary-subtle mb-1" title="Total Jenis Satuan"><i class="ti ti-ruler me-2"></i> {{$wanalytic->meteran->total_product}} Produk Meteran</div>
                        <div class="d-flex align-items-center pb-1 mb-1 justify-content-around">
                            <div class="text-dark fw-bold" title="Total Stock Satuan"><i class="ti ti-package me-2"></i> {{$wanalytic->meteran->total_remaining}}</div>

                            <div class="d-flex align-items-center">
                                <div class="px-2 border-x-2 text-success fw-semibold">{{ $wanalytic->meteran->total_in }}</div>
                                <div class="px-2 border-x-2 text-warning fw-semibold">{{ $wanalytic->meteran->total_onway }}</div>
                            </div>
                        </div>

                        <div class="mb-1 justify-content-around">
                            <div class="text-dark fs-2 fw-semibold d-flex align-items-center justify-content-center p-1 px-2 rounded-2 border mb-1" title="Total Bal"><i class="ti ti-circles fs-4 me-2"></i> {{$wanalytic->total_bal}} Bal</div>
                        </div>

                      </div>
                    
                        <div class="d-flex align-items-center justify-content-center mt-3 text-center fs-2 text-muted fw-semibold">
                            Klik Untuk Lihat Detail
                            <i class="ti ti-arrow-narrow-right fs-4 ms-2"></i>
                        </div>
                    </div>
                  </a>
              </div>
              @endforeach
            </div>

            <div class="row">
              <div class="col-12 d-flex align-items-center gap-3 mb-3 ">
                <span class="text-primary fs-6">#</span>
                <h3 class="fs-5 mb-0"> Statistik Stock di Setiap Toko</h3>
              </div>
              @foreach($stores as $store)
              <div class="col-md-3 mb-4">
                  <a href="{{route('store.stock', $store->id)}}" class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center" style="width:15em">
                            <img src="{{ $store->image ? asset('/storage/'.$store->image) : '/assets/images/profile/user-1.jpg' }}"
                                class="rounded-2" alt="Client Image {{ $store->name }}" style="width: 4em" />
                            <div class="ms-3">
                                <h6 class="fw-semibold mb-1" style="white-space: normal !important">{{ $store->name }}</h6>
                                <div class="fw-normal fs-2 text-muted" style="white-space:normal; font-size:13px; ">{{ $store->description ?? 'Tidak ada deskripsi' }}</div>    
                            </div>
                        </div>

                        <div class=" border-2 border-light-subtle border-dashed p-2 rounded-2 mt-3">
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
                    
                        <div class="d-flex align-items-center justify-content-center mt-3 text-center fs-2 text-muted fw-semibold">
                            Klik Untuk Lihat Detail
                            <i class="ti ti-arrow-narrow-right fs-4 ms-2"></i>
                        </div>
                    </div>
                  </a>
              </div>
              @endforeach
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script src="/assets/libs/owl.carousel/dist/owl.carousel.min.js"></script>

<script>
  $(document).ready(function() {
      // =====================================
      // availableStock
      // =====================================
      let satuanCount = @JSON($stockTotal->satuan);
      let meteranCount = @JSON($stockTotal->meteran);
      var availableStock = {
        color: "#adb5bd",
        series: [satuanCount, meteranCount],
        labels: ["Satuan", "Meteran"],
        chart: {
          width: 180,
          type: "donut",
          fontFamily: "inherit",
          foreColor: "#adb0bb",
        },
        plotOptions: {
          pie: {
            startAngle: 0,
            endAngle: 360,
            donut: {
              size: "75%",
            },
          },
        },
        stroke: {
          show: false,
        },

        dataLabels: {
          enabled: false,
        },

        legend: {
          show: false,
        },
        colors: ["var(--bs-primary)", "var(--bs-dark)"],

        responsive: [
          {
            breakpoint: 991,
            options: {
              chart: {
                width: 120,
              },
            },
          },
        ],
        tooltip: {
          theme: "dark",
          fillSeriesColor: false,
        },
      };

      var chart = new ApexCharts(document.querySelector("#availableStock"), availableStock);
      chart.render();
  })

  $(document).ready(function() {
      // =====================================
      // soldStock
      // =====================================
      let satuanCount = @JSON($stockTotal->satuan);
      let meteranCount = @JSON($stockTotal->meteran);
      var soldStock = {
        color: "#adb5bd",
        series: [satuanCount, meteranCount],
        labels: ["Satuan", "Meteran"],
        chart: {
          width: 180,
          type: "donut",
          fontFamily: "inherit",
          foreColor: "#adb0bb",
        },
        plotOptions: {
          pie: {
            startAngle: 0,
            endAngle: 360,
            donut: {
              size: "75%",
            },
          },
        },
        stroke: {
          show: false,
        },

        dataLabels: {
          enabled: false,
        },

        legend: {
          show: false,
        },
        colors: ["var(--bs-primary)", "var(--bs-dark)"],

        responsive: [
          {
            breakpoint: 991,
            options: {
              chart: {
                width: 120,
              },
            },
          },
        ],
        tooltip: {
          theme: "dark",
          fillSeriesColor: false,
        },
      };

      var chart = new ApexCharts(document.querySelector("#soldStock"), soldStock);
      chart.render();
  })
</script>
@endsection
