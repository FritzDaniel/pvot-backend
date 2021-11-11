@extends('admin.layouts.app')

@section('title')
    PVOT-Digital | Admin Dashboard
@endsection

@section('css')

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
    <li class="breadcrumb-item active">Withdraw</li>
@endsection

@section('headerTitle')
    Withdraw
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
                <!-- Default box -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Withdraw List</h3>

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
                                    <th>ID</th>
                                    <th>Status</th>
                                    <th>Supplier</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Bank</th>
                                    <th>Account Number</th>
                                    <th>Withdraw Amount</th>
                                    <th>Created Date</th>
                                    <th>Actions</th>
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
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @else
                                    @foreach($data as $key => $dt)
                                        <tr>
                                            <td>{{ $dt->uuid }}</td>
                                            <td><span class="badge bg-success">{{ $dt->status }}</span></td>
                                            <td>{{ $dt->Supplier->name }}</td>
                                            <td>{{ $dt->Supplier->email }}</td>
                                            <td>{{ $dt->Supplier->phone }}</td>
                                            <td>{{ $dt->bank }}</td>
                                            <td>{{ $dt->no_rek }}</td>
                                            <td>Rp. {{ number_format($dt->amount) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($dt->created_at)->format('d-M-Y H:i') }}</td>
                                            <td>
                                                @if($dt->status == "Pending")
                                                    <a href="{{ route('admin.withdraw.processed',$dt->uuid) }}" class="btn btn-success">
                                                        Processed
                                                    </a>
                                                @elseif($dt->status == "Processed")
                                                    <a href="{{ route('admin.withdraw.upload',$dt->uuid) }}" class="btn btn-primary">
                                                        <i class="fa fa-user"></i> Upload Transfer Receipt
                                                    </a>
                                                @else
                                                    <span class="badge bg-success">Settle</span>
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
