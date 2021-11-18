@extends('admin.layouts.app')

@section('title')
    PVOT-Digital | Admin Dashboard
@endsection

@section('css')

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
    <li class="breadcrumb-item active"><a href="#">Toko</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('headerTitle')
    Edit Toko
@endsection

@section('content')

    <div class="container-fluid">
        <a href="{{ route('admin.toko') }}" class="btn btn-primary mb-3"><i class="fa fa-angle-left"></i> Back</a>
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
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Update Toko</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="updatePassword" class="fpms" action="{{ route('admin.toko.update',$data->shop_id) }}" method="POST">
                            @csrf
                            @if($data->marketplaceSelect == 1)
                                <div class="form-group">
                                    <label for="">Url Tokopedia</label>
                                    <input placeholder="ex: https://www.tokopedia.com/test" name="url_tokopedia" type="text" class="form-control" value="{{ $data->Shop->url_tokopedia }}">
                                </div>
                            @elseif($data->marketplaceSelect == 2)
                                <div class="form-group">
                                    <label for="">Url Shopee</label>
                                    <input placeholder="ex: https://www.shopee.co.id/test" name="url_shopee" type="text" class="form-control" value="{{ $data->Shop->url_shopee }}">
                                </div>
                            @else
                                <div class="form-group">
                                    <label for="">Url Tokopedia</label>
                                    <input placeholder="ex: https://www.tokopedia.com/test" name="url_tokopedia" type="text" class="form-control" value="{{ $data->Shop->url_tokopedia }}">
                                </div>
                                <div class="form-group">
                                    <label for="">Url Shopee</label>
                                    <input placeholder="ex: https://www.shopee.co.id/test" name="url_shopee" type="text" class="form-control" value="{{ $data->Shop->url_shopee }}">
                                </div>
                            @endif
                        </form>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button id="submitForm" class="btn btn-primary bpms">Save</button>
                    </div>
                    <!-- /.card-footer-->
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
