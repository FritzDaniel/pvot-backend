@extends('supplier.layouts.app')

@section('title')
    PVOT-Digital | Supplier Dashboard
@endsection

@section('css')

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="#">Orders</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('headerTitle')
    Detail
@endsection

@section('content')

    @php($total = 0)

    <div class="container-fluid">
        <a href="{{ route('supplier.orders') }}" class="btn btn-primary mb-3"><i class="fa fa-angle-left"></i> Back</a>
        <div class="row">
            <div class="col-12">
                <!-- Default box -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Transaction Detail</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <h4>{{ $data->external_id }}</h4>
                        <h5>Status : <span style="color: green">{{ $data->status }}</span></h5>
                        <br>
                        <p>Dropshipper :</p>
                        <p>Name : {{ $data->User->name }}</p>
                        <p>Shop : {{ $data->Shop->namaToko }}</p>
                        <p>Address : {{ $data->User->alamat }}</p>
                        <p>Phone : <a href="https://wa.me/{{ $data->User->phone }}">{{ $data->User->phone }}</a></p>
                        <br>
                        @if($data->Receipt)
                            <p>Receipt Number : {{ $data->Receipt->receiptNumber }}</p>
                            <p>Receipt File : <a target="_blank" href="{{ asset($data->Receipt->receiptImage) }}">Download</a></p>
                            <br>
                        @endif
                        <p>Order :</p>
                        <table class="table table-hover text-nowrap">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product</th>
                                <th>Product Price</th>
                                <th>Quantity</th>
                                <th>Variant</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data->Transaction as $itm)
                                <span style="display: none">{{ $total += ($itm->Product->productPrice * $itm->qty) }}</span>
                                <tr>
                                    <td>{{ $itm->product_id }}</td>
                                    <td>{{ $itm->Product->productName }}</td>
                                    <td>Rp. {{ number_format($itm->Product->productPrice) }}</td>
                                    <td>{{ $itm->qty }} Barang</td>
                                    <td>{{ $itm->variants ? $itm->variants : '-' }}</td>
                                    <td>Rp. {{ number_format($itm->Product->productPrice * $itm->qty) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <br>
                        <p>
                            Order Sub Total : <strong>Rp. {{ number_format($total) }}</strong>
                        </p>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        Updated at {{ \Carbon\Carbon::now()->format('d-M-Y H:i') }}
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
