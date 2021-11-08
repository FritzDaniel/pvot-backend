@extends('supplier.layouts.app')

@section('title')
    PVOT-Digital | Supplier Dashboard
@endsection

@section('css')

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('headerTitle')
    Dashboard
@endsection

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">
                <!-- small card -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><sup style="font-size: 20px">Rp</sup> {{ number_format(Auth::user()->EWallet->balance) }}</h3>

                        <p>E-Wallet</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-bill"></i>
                    </div>
                    <a href="{{ route('supplier.withdraw') }}" class="small-box-footer">
                        Withdraw <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{$order}}</h3>

                        <p>New Orders</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <a href="{{ route('supplier.orders') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <!-- Default box -->
            </div>
        </div>
    </div>

@endsection

@section('js')

@endsection
