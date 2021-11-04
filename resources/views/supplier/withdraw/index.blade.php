@extends('supplier.layouts.app')

@section('title')
    PVOT-Digital | Supplier Dashboard
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
            </div>
            <div class="col-lg-3 col-6">
                <!-- small card -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><sup style="font-size: 20px">Rp</sup> {{ number_format(Auth::user()->EWallet->balance) }}</h3>

                        <p>E-Wallet</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-bill"></i>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Account Number List</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="dataTables_wrapper dt-bootstrap4">
                            <table class="table table-bordered table-hover dataTable dtr-inline">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Bank</th>
                                    <th>Account Number</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($noRek->isEmpty())
                                    <tr>
                                        <td>No Data</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @else
                                    @foreach($noRek as $key => $no)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $no->bank }}</td>
                                            <td>{{ $no->account_number }}</td>
                                            <td>
                                                <a href="{{ route('supplier.account.delete',$no->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        Account
                    </div>
                    <div class="card-body">
                        <h4>Create Accounts</h4>
                        <form class="fpms" action="{{ route('supplier.account.store') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label>Bank</label>
                                <input type="text" value="{{ old('bank') }}" placeholder="Bank Name" name="bank" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Account Number</label>
                                <input type="number" value="{{ old('account_number') }}" placeholder="Account Number" name="account_number" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="submit" value="Save" class="btn btn-primary bpms">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-9">
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
                        <a href="{{ route('supplier.withdraw.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus-circle"></i> Create Withdraw Data
                        </a>
                        <div class="dataTables_wrapper dt-bootstrap4">
                            <table id="datatable" class="table table-bordered table-hover dataTable dtr-inline">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Status</th>
                                    <th>Bank</th>
                                    <th>No Rek</th>
                                    <th>Amount</th>
                                    <th>Transfer Receipt</th>
                                    <th>Created Date</th>
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
                                            <td>
                                                <span class="badge bg-primary">{{ $dt->status }}</span>
                                            </td>
                                            <td>{{ $dt->bank }}</td>
                                            <td><strong>{{ $dt->no_rek }}</strong></td>
                                            <td>Rp. {{ number_format($dt->amount) }}</td>
                                            <td>
                                                @if($dt->buktiTransfer)
                                                    <a download="{{ $dt->uuid }}.jpg" href="{{ asset($dt->buktiTransfer) }}" title="ImageName">
                                                        Download
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($dt->created_at)->format('d-M-Y H:i') }}
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
                        Updated at {{ \Carbon\Carbon::now()->format('d-M-Y H:i') }}
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
