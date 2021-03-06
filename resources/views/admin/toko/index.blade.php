@extends('admin.layouts.app')

@section('title')
    PVOT-Digital | Admin Dashboard
@endsection

@section('css')

@endsection

@section('headerTitle')
    Toko
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active">Toko</li>
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
                        <h3 class="card-title">List Toko</h3>

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
                                    <th>Shop Owner</th>
                                    <th>Shop Name</th>
                                    <th>Status</th>
                                    <th>Marketplace</th>
                                    <th>URL Toko</th>
                                    <th>Created Date</th>
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
                                        <td></td>
                                    </tr>
                                @else
                                    @foreach($data as $key => $dt)
                                        <tr>
                                            <td>{{ $dt->id }}.</td>
                                            <td>{{ $dt->Shop ? $dt->Shop->User->name : '-' }}</td>
                                            <td>{{ $dt->Shop ? $dt->Shop->namaToko : '-' }}</td>
                                            <td>{{ $dt->Shop ? $dt->Shop->status : '-' }}</td>
                                            <td>
                                                @if($dt->marketplaceSelect == 1)
                                                    Tokopedia
                                                @elseif($dt->marketplaceSelect == 2)
                                                    Shopee
                                                @else
                                                    Tokopedia & Shopee
                                                @endif
                                            </td>
                                            <td>
                                                @if($dt->marketplaceSelect == 1)
                                                    Tokopedia: <br> <a target="_blank" href="{{ $dt->Shop ? $dt->Shop->url_tokopedia : '#' }}">{{ $dt->Shop ? $dt->Shop->url_tokopedia : '-' }}</a>
                                                @elseif($dt->marketplaceSelect == 2)
                                                    Shopee: <br> <a target="_blank" href="{{ $dt->Shop ? $dt->Shop->url_shopee : '#' }}">{{ $dt->Shop ? $dt->Shop->url_shopee : '-' }}</a>
                                                @else
                                                    Tokopedia:  <br> <a target="_blank" href="{{ $dt->Shop ? $dt->Shop->url_tokopedia : '#' }}">{{ $dt->Shop ? $dt->Shop->url_tokopedia : '-' }}</a>  <br>
                                                    Shopee:  <br> <a target="_blank" href="{{ $dt->Shop ? $dt->Shop->url_shopee : '#' }}">{{ $dt->Shop ? $dt->Shop->url_shopee : '-' }}</a>
                                                @endif

                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($dt->created_at ? $dt->created_at : '-')->format('d-M-Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('admin.toko.detail',$dt->id) }}" class="btn btn-primary">
                                                    <i class="fa fa-eye"></i> Details
                                                </a>
                                                <a href="{{ route('admin.toko.edit',$dt->Shop->id) }}" class="btn btn-success">
                                                    <i class="fa fa-bookmark"></i> Add Marketplace
                                                </a>
                                                <a href="{{ route('admin.toko.editData',$dt->Shop->id) }}" class="btn btn-info">
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
