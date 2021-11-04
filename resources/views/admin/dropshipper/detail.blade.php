@extends('admin.layouts.app')

@section('title')
    PVOT-Digital | Admin Dashboard
@endsection

@section('css')
    .profilePicture {
        width: 200px;
        height: 200px;
    }
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="#">Dropshipper</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('headerTitle')
    Dropshipper Details
@endsection

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Default box -->
                <a href="{{ route('admin.dropshipper') }}" class="btn btn-primary mb-3"><i class="fa fa-angle-left"></i> Back</a>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Dropshipper Details</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="image">
                            <img src="https://dashboard.pvotdigital.com/public{{ $data->profilePicture }}" alt="" class="profilePicture">
                        </div>
                        <br>
                        <h5>Name: {{ $data->name }}</h5>
                        <br>
                        <p>Email: {{ $data->email }}</p>
                        <p>Email verified: <b>{{ $data->email_verified_at ? 'Verified' : 'Not Verified' }}</b></p>
                        <p>Phone: {{ $data->phone }}</p>
                        <p>Membership : {{ $data->Membership->status }}</p>
                        <p>Membership expired : {{ $data->Membership->expiredDate ?
                             \Carbon\Carbon::parse($data->Membership->expiredDate)->format('d-m-Y H:i:s') : '-' }}
                        </p>
                        <p>Company profile : {{ $data->namaPerusahaan ? $data->namaPerusahaan : '-' }}</p>
                        <p>Country : {{ $data->country ? $data->country : '-' }}</p>
                        <p>Address : {{ $data->alamat ? $data->alamat : '-' }}</p>
                        <p>City : {{ $data->city ? $data->city : '-' }}</p>
                        <p>Province : {{ $data->provinsi ? $data->provinsi : '-' }}</p>
                        <p>Postal code : {{ $data->kodepos ? $data->kodepos : '-' }}</p>
                        <p>Role : {{ $data->userRole }}</p>
                        <p>Created at : {{ \Carbon\Carbon::parse($data->created_at)->format('d-m-Y H:i:s') }}</p>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        Updated at {{ \Carbon\Carbon::parse($data->updated_at)->format('d-m-Y H:i:s') }}
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
