@extends('supplier.layouts.app')

@section('title')
    PVOT-Digital | Supplier Dashboard
@endsection

@section('css')

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
    <li class="breadcrumb-item active">Dropshipper</li>
@endsection

@section('headerTitle')
    Dropshipper
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
                        <h3 class="card-title">Dropshipper List</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <p><i class="fa fa-user"></i> Dropshipper Count : {{ count($data) }}</p>
                        <div class="dataTables_wrapper dt-bootstrap4">
                            <table id="datatable" class="table table-bordered table-hover dataTable dtr-inline">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Shop Name</th>
                                    <th>Alamat</th>
                                    <th>Phone</th>
                                    <th>Transaction</th>
                                    <th>Complete Order</th>
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
                                    </tr>
                                @else
                                    @foreach($data as $key => $dt)
                                        <tr>
                                            <td>{{ $key+1 }}.</td>
                                            <td>{{ $dt->User->name }}</td>
                                            <td>{{ $dt->namaToko }}</td>
                                            <td>{{ $dt->User->alamat ? $dt->User->alamat : '-' }}</td>
                                            <td>{{ $dt->User->phone }}</td>
                                            <td>{{ $dt->OrderCount() }}x Transaction</td>
                                            <td>{{ $dt->completeOrder() }}x Transaction</td>
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
