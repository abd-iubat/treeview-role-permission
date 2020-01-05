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

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <input id="bstree-data" type="hidden" name="bstree-data" data-ancestors="ALS:IDF">
        <div id="mytree" class="bstree">
            <ul>
                <li data-id="root" data-level="0"><span>Root</span>
                   <ul>
                       {!! $permission_tree !!}
                   </ul>
                </li>
            </ul>
        </div>
    </div>
@endsection
@push('js')
    <script type="text/javascript">
        $('document').ready(function () {
            $hiddenInput = $('#bstree-data');
            $('#mytree').bstree({
                dataSource: $hiddenInput,
                initValues: $hiddenInput.data('ancestors'),
                onDataPush: function (values) {
                    var def = '<strong class="pull-left">Values:&nbsp;</strong>'
                    for (var i in values) {
                        def += '<span class="pull-left">' + values[i] + '&nbsp;</span>'
                    }
                    $('#status').html(def)
                },
                // updateNodeTitle: function (node, title) {
                //     return '[' + node.attr('data-id') + '] ' + title + ' (' + node.attr('data-level') + ')'
                // }
            });

        })


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
