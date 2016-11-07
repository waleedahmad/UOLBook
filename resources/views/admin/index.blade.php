@extends('layout')

@section('title')
    Verification Requests / Admin - UOLBook
@endsection

@section('content')
   <div class="container-fluid admin-verification">
       @include('admin.sidebar')

       <div class="content col-xs-12 col-sm-9 col-md-9 col-lg-9">
           <div class="dropdown">
               <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                   Filter Requests
                   <span class="caret"></span>
               </button>
               <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                   <li><a href="/admin/teachers">Teacher Requests</a></li>
                   <li><a href="/admin/students">Student Requests</a></li>
               </ul>
           </div>

           @foreach($v_requests as $v_request)
               <div class="row">
                   <div class="media">
                       <div class="media-left">
                           <a href="#">
                               <img class="media-object" src="/storage/{{$v_request->card_uri}}" alt="..."  style="width: 50px; height: 50px;">
                           </a>
                       </div>
                       <div class="media-body">
                           <h4 class="media-heading">
                               @if($v_request->type === 'teacher')
                                   {{$v_request->email}}
                               @else
                                   {{$v_request->registration_id}}
                               @endif</h4>
                       </div>
                   </div>
               </div>
           @endforeach
       </div>
   </div>
@endsection