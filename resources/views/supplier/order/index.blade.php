@extends('supplier.layouts.app')

@section('title')
    PVOT-Digital | Supplier Dashboard
@endsection

@section('css')

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
    <li class="breadcrumb-item active">Orders</li>
@endsection

@section('headerTitle')
    Orders
@endsection

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @if (session('message'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-check"></i> Success!</h4>
                        {{ session('message') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-times"></i> Error!</h4>
                        {{ session('error') }}
                    </div>
                @endif
                <!-- Default box -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Order List</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="dataTables_wrapper dt-bootstrap4">
                            <table id="datatable" class="table table-bordered table-hover dataTable dtr-inline">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Transaction ID</th>
                                    <th>Dropshipper</th>
                                    <th>Shop Name</th>
                                    <th>Status</th>
                                    <th>Transaction Date</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($data->isEmpty())
                                    <tr>
                                        <td>No Data</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @else
                                    @foreach($data as $key => $dt)
                                        <tr>
                                            <td>{{ $key+1 }}.</td>
                                            <td>{{ $dt->external_id }}</td>
                                            <td>{{ $dt->User->name }}</td>
                                            <td>{{ $dt->Shop->namaToko }}</td>
                                            <td>
                                                @if($dt->status == "Paid")
                                                    <span class="badge bg-success">Paid</span>

                                                @elseif($dt->status == "Processed")
                                                    <span class="badge bg-warning">Processed</span>
                                                @elseif($dt->status == "Sent")
                                                    <span class="badge bg-primary">Sent</span>
                                                @else
                                                    <span class="badge bg-primary">Success</span>
                                                @endif

                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($dt->created_at)->format('d-M-Y H:i') }}</td>
                                            <td>
                                                @if($dt->status !== "Complete")
                                                    <a href="{{ route('supplier.orders.status',$dt->external_id )}}" class="btn btn-primary"><i class="fa fa-arrow-circle-right"></i> Change Status</a>
                                                @endif
                                                <a href="{{ route('supplier.orders.detail',$dt->external_id) }}" class="btn btn-info"><i class="fa fa-eye"></i> Detail</a>
                                                @if($dt->status == "Sent")
                                                    @if($dt->getDaysSent() >= 3)
                                                        <a href="{{ route('supplier.orders.complete',$dt->external_id) }}" class="btn btn-success"><i class="fa fa-check"></i> Manual Complete</a>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
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

    <script>
        $(function () {
            $('#datatable').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true,
                "responsive": true,
            });
        });
    </script>

@endsection
