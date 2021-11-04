@extends('supplier.layouts.app')

@section('title')
    PVOT-Digital | Supplier Dashboard
@endsection

@section('css')

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="#">Withdraw</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('headerTitle')
    Create Withdraw
@endsection

@section('content')

    <div class="container-fluid">
        <a href="{{ route('supplier.withdraw') }}" class="btn btn-primary mb-3"><i class="fa fa-angle-left"></i> Back</a>
        <div class="row">

            <div class="col-12">
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-times"></i> Error!</h4>
                        {{ session('error') }}
                    </div>
                @endif
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
                        <h3 class="card-title">Create Withdraw Data</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form id="updatePassword" class="fpms" action="{{ route('supplier.withdraw.store') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label>Bank</label>
                                <select name="bank" class="form-control">
                                    <option value="">Select Bank</option>
                                    @foreach($noRek as $nr)
                                        <option value="{{ $nr->id }}">{{ $nr->bank }} - {{ $nr->account_number }}</option>
                                    @endforeach
                                </select>
                                @if(count($noRek) == 0)
                                    <small>Add your account number <a href="{{ route('supplier.withdraw') }}">Here</a></small>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="text" name="amount" class="form-control" placeholder="Amount" value="{{ old('amount') }}">
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
