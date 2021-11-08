@extends('supplier.layouts.app')

@section('title')
    PVOT-Digital | Supplier Dashboard
@endsection

@section('css')

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="#">Orders</a></li>
    <li class="breadcrumb-item active">Status</li>
@endsection

@section('headerTitle')
    Status
@endsection

@section('content')

    <div class="container-fluid">
        <a href="{{ route('supplier.orders') }}" class="btn btn-primary mb-3"><i class="fa fa-angle-left"></i> Back</a>
        <div class="row">
            <div class="col-12">
                <!-- Default box -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Change Status</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('supplier.orders.update',$data->external_id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="">Status</label>
                                <select name="status" id="" class="form-control">
                                    <option value="Processed">Processed</option>
                                    <option value="Sent">Sent</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary" value="Save">
                            </div>
                        </form>
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
