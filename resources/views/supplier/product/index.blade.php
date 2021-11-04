@extends('supplier.layouts.app')

@section('title')
    PVOT-Digital | Supplier Dashboard
@endsection

@section('css')

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
    <li class="breadcrumb-item active">Product</li>
@endsection

@section('headerTitle')
    Product
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
                        <h3 class="card-title">Product List</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('supplier.product.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus-circle"></i> Add Product
                        </a>
                        <div class="dataTables_wrapper dt-bootstrap4">
                            <table id="datatable" class="table table-bordered table-hover dataTable dtr-inline">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Status</th>
                                    <th>Product Name</th>
                                    <th>Product Desc</th>
                                    <th>Product Stock</th>
                                    <th>Product Price</th>
                                    <th>Product Image</th>
                                    <th>Variant</th>
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
                                    </tr>
                                @else
                                    @foreach($data as $key => $dt)
                                        <tr>
                                            <td>{{ $key+1 }}.</td>
                                            <td>
                                                @if($dt->status !== "Not Active")
                                                    <span class="badge bg-success">{{ $dt->status }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ $dt->status }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $dt->productName }}</td>
                                            <td>{{ $dt->productDesc }}</td>
                                            <td>{{ $dt->productStock }}</td>
                                            <td>Rp. {{ number_format($dt->productPrice) }}</td>
                                            <td>
                                                <img src="{{ asset($dt->productPicture) }}" alt="" style="width: 50px; height: 50px;">
                                            </td>
                                            <td>{{ count($dt->productVariant) > 0 ? 'Variant Avaliable' : 'No Variant' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($dt->created_at)->format('d-M-Y H:i') }}</td>
                                            <td>
                                                @if($dt->status == "Active")
                                                    <a href="{{ route('supplier.product.deactivate',$dt->uuid) }}" class="btn btn-danger">
                                                        Deactivate
                                                    </a>
                                                @else
                                                    <a href="{{ route('supplier.product.active',$dt->uuid) }}" class="btn btn-success">
                                                        Activate
                                                    </a>
                                                @endif
                                                <a href="{{ route('supplier.variant',$dt->uuid) }}" class="btn btn-info">
                                                    <i class="fa fa-bookmark"></i> Variants
                                                </a>
                                                <a href="{{ route('supplier.product.edit',$dt->uuid) }}" class="btn btn-primary">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                            <small>*Notes if Product is not active dropshipper is can't see or buy the product.</small>
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
