@extends('admin.layouts.app')

@section('title')
    PVOT-Digital | Admin Dashboard
@endsection

@section('css')

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
    <li class="breadcrumb-item active">Design</li>
@endsection

@section('headerTitle')
    Design
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
                        <h3 class="card-title">Design List</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('admin.design.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus-circle"></i> Add Design
                        </a>
                        <div class="dataTables_wrapper dt-bootstrap4">
                            <table id="datatable" class="table table-bordered table-hover dataTable dtr-inline">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Design Supplier</th>
                                    <th>Taken By</th>
                                    <th>Design Name</th>
                                    <th>Image</th>
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
                                    </tr>
                                @else
                                    @foreach($data as $key => $dt)
                                        <tr>
                                            <td>{{ $key+1 }}.</td>
                                            <td>{{ $dt->supplier_id ? $dt->Supplier->name : '-' }}</td>
                                            <td>{{ $dt->shop_id ? $dt->Shop->namaToko : '-' }}</td>
                                            <td>{{ $dt->designName }}</td>
                                            <td>
                                                <a  href="{{ asset($dt->designImage) }}" target="_blank">View Image</a>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($dt->created_at)->format('d-m-Y H:i:s') }}</td>
                                            <td>
                                                <a href="{{ route('admin.subDesign',$dt->id) }}" class="btn btn-primary">
                                                    <i class="fa fa-file-image"></i> Sub Design
                                                </a>
                                                <a href="{{ route('admin.design.edit',$dt->id) }}" class="btn btn-primary">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
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
