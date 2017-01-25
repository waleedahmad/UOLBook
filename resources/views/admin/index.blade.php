@extends('layout')

@section('title')
    Verification Requests / Admin - UOLBook
@endsection

@section('content')
   <div class="admin-verification">
       @include('admin.sidebar')

       <div class="admin-content col-xs-12 col-sm-9 col-md-9 col-lg-9">
           <div class="dropdown">
               <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                   @if(Request::path() === 'admin/teachers')
                       Filter: Teachers
                   @elseif(Request::path() === 'admin/students')
                       Filter: Students
                   @else
                       Filter Requests
                   @endif
                   <span class="caret"></span>
               </button>
               <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                   <li><a href="/admin/teachers">Teacher Requests</a></li>
                   <li><a href="/admin/students">Student Requests</a></li>
               </ul>
           </div>

           @if($v_requests->count())
               <table class="table requests">
                   <thead>
                   <tr>
                       <th>
                           Identity Card
                       </th>

                       <th>
                           Email/Username
                       </th>

                       <th>
                           Request Date
                       </th>

                       <th>Type</th>

                       <th>
                           Action
                       </th>
                   </tr>
                   </thead>

                   <tbody>

                   @foreach($v_requests as $v_request)
                       <tr class="request">
                           <td>
                               <a href="/storage{{$v_request->card_uri}}" target="_blank">
                                   <img class="media-object" src="/storage/{{$v_request->card_uri}}" alt="..."  style="width: 50px; height: 50px;">
                               </a>
                           </td>

                           <td>
                               @if($v_request->type === 'teacher')
                                   {{$v_request->email}}
                               @else
                                   {{$v_request->registration_id}}
                               @endif
                           </td>

                           <td>
                               {{$v_request->created_at}}
                           </td>

                           <td>
                               {{$v_request->type}}
                           </td>

                           <td>
                               <button class="btn btn-default approve-user" data-id="{{$v_request->id}}">Approve</button>
                               <button class="btn btn-danger disapprove-user" data-id="{{$v_request->id}}">Disapprove</button>
                           </td>
                       </tr>
                   @endforeach

                   </tbody>
               </table>
           @else
               <div class="alert alert-info" role="alert" style="margin-top: 20px;">No verification requests found.</div>
           @endif
       </div>
   </div>
@endsection