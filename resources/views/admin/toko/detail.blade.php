@extends('admin.layouts.app')

@section('title')
    PVOT-Digital | Admin Dashboard
@endsection

@section('css')
    .fotoToko {
        width: 200px;
        height: 200px;
        margin-bottom: 10px;
    }
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="#">Toko</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('headerTitle')
    Detail
@endsection

@section('content')

    <div class="container-fluid">
        <a href="{{ route('admin.toko') }}" class="btn btn-primary mb-3"><i class="fa fa-angle-left"></i> Back</a>
        <div class="row">
            <div class="col-12">
                <!-- Default box -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Shop Detail</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <img src="{{ asset($data->Shop->fotoToko) }}" alt="" class="fotoToko">

                        <h3>{{ $data->Shop->namaToko }} | <small>Status: <span style="{{ $data->Shop == "Paid" ? "color: green" : null }}">{{ $data->Shop->status }}</span></small></h3>
                        <br>
                        <p>
                            Toko Email: {{ $data->Shop->emailToko }}
                        </p>
                        <p>
                            Toko Phone: {{ $data->Shop->handphoneToko }}
                        </p>
                        <p>
                            Toko Address: {{ $data->Shop->alamatToko }}
                        </p>
                        @if($data->marketplaceSelect == 1)
                            <p>
                                URL Tokopedia : <a target="_blank" href="{{ $data->Shop->url_tokopedia ? $data->Shop->url_tokopedia : '#'}}">{{ $data->Shop->url_tokopedia ? $data->Shop->url_tokopedia : '-'}}</a>
                            </p>
                        @elseif($data->marketplaceSelect == 2)
                            <p>
                                URL Shopee : <a target="_blank" href="{{ $data->Shop->url_shopee ? $data->Shop->url_shopee : '#'}}">{{ $data->Shop->url_shopee ? $data->Shop->url_shopee : '-'}}</a>
                            </p>
                        @else
                            <p>
                                URL Tokopedia : <a target="_blank" href="{{ $data->Shop->url_tokopedia ? $data->Shop->url_tokopedia : '#'}}">{{ $data->Shop->url_tokopedia ? $data->Shop->url_tokopedia : '-'}}</a>
                            </p>
                            <p>
                                URL Shopee : <a target="_blank" href="{{ $data->Shop->url_shopee ? $data->Shop->url_shopee : '#'}}">{{ $data->Shop->url_shopee ? $data->Shop->url_shopee : '-'}}</a>
                            </p>
                        @endif


                        <p>
                            Category : {{ $data->Shop->Category ? $data->Shop->Category->name : '-'}}
                        </p>
                        <p>
                            Supplier : {{ $data->Shop->Supplier ? $data->Shop->Supplier->name : '-'}}
                        </p>
                        <p>
                            Design Toko : {{ $data->Shop->Design ? $data->Shop->Design->designName : '-'}}
                        </p>
                        <p>
                            Description : {{ $data->Shop->description ? $data->Shop->description : '-'}}
                        </p>
                        <p>
                            Created At : {{ \Carbon\Carbon::parse($data->Shop->created_at)->format('d-M-Y H:i') }}
                        </p>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        Updated at {{ \Carbon\Carbon::now()->format('d-m-Y') }}
                    </div>
                    <!-- /.card-footer-->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>

@endsection

@section('js')

@endsection
