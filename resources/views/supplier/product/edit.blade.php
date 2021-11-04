@extends('supplier.layouts.app')

@section('title')
    PVOT-Digital | Supplier Dashboard
@endsection

@section('css')

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="#">Product</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('headerTitle')
    Edit Product
@endsection

@section('content')

    <div class="container-fluid">
        <a href="{{ route('supplier.product') }}" class="btn btn-primary mb-3"><i class="fa fa-angle-left"></i> Back</a>
        <div class="row">

            <div class="col-12">
                @if (count($errors) > 0)
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-ban"></i> There were some problems with your input!</h4>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </div>
                @endif
                <!-- Default box -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Edit Product</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form id="updatePassword" class="fpms" action="{{ route('supplier.product.update',$data->uuid) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="exampleInputFile">Product Image</label> <br>
                                <img src="{{ asset($data->productPicture) }}" alt="" class="mb-3" style="width: 75px; height: 75px;">
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="exampleInputFile" name="productPicture">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Upload</span>
                                    </div>
                                </div>
                                <small>*Max size 5 MB</small>
                            </div>
                            <div class="form-group">
                                <label>Product Name</label>
                                <input type="text" name="productName" class="form-control" placeholder="Product Name" value="{{ $data->productName }}">
                            </div>
                            <div class="form-group">
                                <label>Product Description</label>
                                <input type="text" name="productDesc" class="form-control" placeholder="Product Description" value="{{ $data->productDesc }}">
                            </div>
                            <div class="form-group">
                                <label>Product Stock</label>
                                <input type="number" name="productStock" class="form-control" placeholder="Product Quantity" value="{{ $data->productStock }}">
                            </div>
                            <div class="form-group">
                                <label>Product Category</label>
                                <select class="form-control" name="productCategory">
                                    <option value="">Select Category</option>
                                    @foreach($category as $cats)
                                        <option value="{{ $cats->id }}"
                                            {{ $data->productCategory == $cats->id ? 'selected' : '' }}>
                                            {{ $cats->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Product Price</label>
                                <input type="number" name="productPrice" class="form-control" placeholder="Product Price" value="{{ $data->productPrice }}">
                                <small>Must greater than Rp, 1 Rupiah</small>
                            </div>
                        </form>
                    </div>

                    <div class="card-footer">
                        <button id="submitForm" class="btn btn-primary bpms">Save</button>
                    </div>

                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>

@endsection

@section('js')

    <script src="{{ asset('assets/adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

    <script>
        $('#submitForm').on('click',function(){
            $('#updatePassword').submit();
        });
    </script>

    <script>
        $(function () {
            bsCustomFileInput.init();
        });
    </script>

@endsection
