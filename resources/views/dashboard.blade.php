@extends('layouts.master')
@section('content')
<section class="content card" style="padding: 10px 10px 10px 10px ">
    <div class="box">
        <div class="row">
            <div class="col">
                <center>
                    <h3 class="font-weight-bold">DASHBOARD</h3>
                    <hr />
                </center>
                <br>
            </div>
        </div>

        <div class="row">
            <div class="card-body">
                <!-- Small boxes (Stat box) -->
                <div class="filter-container p-0 row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-light">
                            <div class="inner">
                                <h3>{{ $suratmasuk ?? '-'}}</h3>
                                <p>-</p>
                            </div>
                            <div class="icon">
                                <i class="nav-icon fas fa-envelope-open-text"></i>
                            </div>
                            <a href="/suratmasuk/index" class="small-box-footer bg-orange">Lihat Detail <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-light">
                            <div class="inner">
                                <h3>{{ $suratkeluar ?? '-'}}</h3>
                                <p>-</p>
                            </div>
                            <div class="icon">
                                <i class="nav-icon fas fa-envelope"></i>
                            </div>
                            <a href="/suratkeluar/index" class="small-box-footer bg-orange">Lihat Detail <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-light">
                            <div class="inner">
                                <h3>{{ $klasifikasi ?? '-'}}</h3>
                                <p>-</p>
                            </div>
                            <div class="icon">
                                <i class="nav-icon fas fa-layer-group"></i>
                            </div>
                            <a href="/klasifikasi/index" class="small-box-footer bg-orange">Lihat Detail <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    @if (auth()->user()->role == 'admin')
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-light">
                            <div class="inner">
                                <h3>{{ $pengguna ?? '-'}}</h3>
                                <p>-</p>
                            </div>
                            <div class="icon">
                                <i class="nav-icon fas fa-user"></i>
                            </div>
                            <a href="" class="small-box-footer bg-orange">Lihat Detail <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    @endif
                    <!-- ./col -->
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
