@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <table id="userDataTable">
                            <thead>
                                <tr>
                                    <td>#SL</td>
                                    <td>Name</td>
                                    <td>Email</td>
                                    <td>Created</td>
                                    <td></td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script type="text/javascript">
        var userDataTable = null;
        $(window).on("load", function () {
            userDataTable = $('#userDataTable').DataTable({
                dom: '<"row"<"col-12 col-sm-6"l><"col-12 col-sm-6"f><"col-12 col-sm-12"t><"col-12 col-sm-6"i><"col-12 col-sm-6"p>>',
                lengthMenu: [[100, 25, 50, 100, -1], [100, 25, 50, 100, "All"]],
                buttons: [],
                ajax: {
                    url: 'home',
                    dataSrc: function (json) {
                        return json.data;
                    },
                    data: function (dataParam) {
                        return dataParam;
                    }
                },
                columns: [

                    {data: "Row_Index"},
                    {data: "name", name: 'name'},
                    {data: "email", name: 'email'},
                    {data: "created_at"},
                    {
                        'render' : function(data, type, row, ind){
                            return '<a href="{{url("/user")}}/'+row.id+'" class="btn btn-primary">Edit</a>'
                        }
                    },

                ],
                columnDefs: [
                    {searchable: false, orderable:false, targets: [0,3]}
                ],
                responsive: true,
                autoWidth: false,
                serverSide: true,
                processing: true,
            });
        });


    </script>
@endpush
