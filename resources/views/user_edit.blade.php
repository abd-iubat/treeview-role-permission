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

                        <form action="{{route('user.edit', $user->id)}}" method="post">
                            @csrf
                            <div>
                                <h4>Role</h4>
                                @foreach($roles as $role)
                                    <div class="checkbox">
                                        <label><input {{(in_array($role->id, $user_roles)?'checked':'')}} type="checkbox" name="roles[]" value="{{$role->id}}">{{$role->name}}</label>
                                    </div>
                                @endforeach
                            </div>
                            <hr>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" value="{{$user->name}}" class="form-control" id="name" name="name"
                                       placeholder="Enter Name" required>
                            </div>

                            <hr>
                            <div>
                                <h4>Permission</h4>
                                <input id="bstree-data" type="hidden" name="permissions" data-ancestors="ALS:IDF">
                                <div id="mytree" class="bstree">
                                    <ul>
                                        <li data-id="root" data-level="0"><span>Root</span>
                                            <ul>
                                                {!! $html_tree !!}
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary pull-right">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
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
                }
            });

        })

    </script>
@endpush
