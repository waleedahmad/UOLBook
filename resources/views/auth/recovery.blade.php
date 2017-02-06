@extends('layout')


@section('title')
    Recover Password - UOLBook
@endsection

@section('content')
    <div class="auth row col-sm-12 col-xs-12 col-md-5 col-lg-5 center-block" style="float: none">
        <form class="form-horizontal" method="post" action="/recover/password">

            <div class="form-group @if($errors->has('email')) has-error @endif">
                <label for="email" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" name="email" placeholder="Email">
                    @if($errors->has('email'))
                        {{$errors->first('email')}}
                    @endif
                </div>
            </div>


            {{csrf_field()}}

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    @if(Session::has('message'))
                        <div class="alert alert-info" role="alert">{{Session::get('message')}}</div>
                    @endif
                    <button type="submit" class="btn btn-default">Recover Password</button>
                </div>
            </div>
        </form>
    </div>
@endsection