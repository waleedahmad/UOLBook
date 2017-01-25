@extends('layout')

@section('title')
    Societies / Admin - UOLBook
@endsection

@section('content')
    @include('admin.sidebar')

    <div class="content col-xs-12 col-sm-9 col-md-9 col-lg-9">
        @if($societies->count())
            <table class="table societies">
                <thead>
                <tr>
                    <th>
                        Name
                    </th>

                    <th>
                        Category
                    </th>

                    <th>
                        Verified
                    </th>

                    <th>Admin</th>

                    <th>
                        Action
                    </th>
                </tr>
                </thead>

                <tbody>

                @foreach($societies as $society)
                    <tr class="society">
                        <td>
                            {{$society->name}}
                        </td>

                        <td>
                            {{ucfirst($society->type)}}
                        </td>

                        <td>
                            @if($society->verified)
                                Yes
                            @else
                                No
                            @endif
                        </td>

                        <td>
                            {{$society->admin->first_name . ' ' . $society->admin->last_name}}
                        </td>

                        <td>
                            <button class="btn btn-danger delete-society" data-id="{{$society->id}}">Delete Society</button>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        @else
            <div class="alert alert-info" role="alert">No societies found.</div>
        @endif
    </div>
@endsection