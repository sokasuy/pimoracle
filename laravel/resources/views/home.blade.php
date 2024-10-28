@extends('layouts.main', ['hasReadPermission' => $hasReadPermission])

@section('title')
    <title>{{ Config::get('app.name') }} | Dashboard</title>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@if ($hasReadPermission)
    @section('content')
        <div class="p-6 text-gray-900">
            {{ __("You're logged in!") }}
        </div>
        <!-- /.row -->
        <div class="row">
            <!-- /.col -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Users</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div id="showinfo"></div>
                        @if (session('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif

                        {{-- @if (Permission::where('role', Auth::user()->role)->where('view', 'users')->where('create', true)->exists()) --}}
                        {{-- @if ($hasCreateNewUsers)
                            <div class="row mb-0">
                                <div class="col-md-3" style="margin-bottom: 23px;">
                                    <a class="btn btn-primary btn-sm" href="{{ route('auth.adduser') }}" id="btn_adduser">
                                        <i class="fas fa-plus">&nbsp Add User</i></a>
                                </div>
                            </div>
                        @endif --}}

                        <table id="tbl_worklist" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>FROM</th>
                                    <th>TYPE</th>
                                    <th>SUBJECT</th>
                                    <th>DUE</th>
                                </tr>
                            </thead>
                            {{-- <div class="modal container fade" id="containermodal" tabindex="-1" role="basic"
                            aria-hidden="true">
                            <div class="modal-content" id="contentmodal">
                                <img src="{{ asset('assets/images/loading-buffering.gif') }}" height='200px' />
                            </div>
                        </div> --}}
                            <!-- Modal -->
                            <div class="modal fade" id="modal_container" tabindex="-1" role="dialog"
                                aria-labelledby="modal_containerlabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                                    <div class="modal-content" id="modal_content">
                                        <div class="overlay">
                                            <i class="fas fa-2x fa-sync fa-spin"></i>
                                        </div>
                                        {{-- <img src="{{ asset('assets/images/loading-buffering.gif') }}" height='150px'
                                    width='150px' class="center" /> --}}
                                    </div>
                                </div>
                            </div>

                            {{-- <tfoot>
                            <tr>
                                <th>NAME</th>
                                <th>EMAIL</th>
                                <th>ROLE</th>
                                <th>CREATED AT</th>
                                <th>UPDATED AT</th>
                                <th>ACTION</th>
                            </tr>
                        </tfoot> --}}
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
    @endsection
@else
    @section('content')
        <div style="text-align: center;">
            <div style="display: flex; flex-direction: column; align-items: center;">
                <img src="{{ asset('assets/dist/img/LaravelLOGO.png') }}" alt="MedboXLOGO" height="60" width="60"
                    style="margin-bottom: 20px;">
                <h1 style="color: #868686">USER NOT AUTHORIZED</h1>
            </div>
        </div>
    @endsection
@endif

@section('jsbawah')
    <script defer>
        document.addEventListener('DOMContentLoaded', (event) => {
            var tblUser = $("#tbl_worklist").DataTable({
                "dom": 'Bfrtip',
                "paging": true,
                "pageLength": 10,
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "deferRender": true,
                "processing": true,
                // "serverSide": true,
                "ajax": {
                    "url": '{{ route('home.getworklist') }}',
                    "type": "POST",
                    "data": {
                        _token: "{{ csrf_token() }}",
                        employee_id: 97
                    },
                    "xhrFields": {
                        withCredentials: true
                    }
                },
                "columns": [{
                    "data": "from_user"
                }, {
                    "data": "type_trx"
                }, {
                    "data": "subject"
                }, {
                    "data": "due_date",
                    // render: $.fn.dataTable.render.moment('YYYY-MM-DDTHH:mm:ss.SSSSZ',
                    //     'D MMM YYYY HH:mm:ss')
                }],
                order: {
                    name: 'due_date',
                    dir: 'desc'
                },
                lengthMenu: [10, 25, 50, {
                    label: 'All',
                    value: -1
                }],
                select: true,
                "buttons": ['pageLength', "copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#tbl_user_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection
