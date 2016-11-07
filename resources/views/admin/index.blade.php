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
                           @endif</h4>
                       </td>

                       <td>
                           {{$v_request->created_at}}
                       </td>

                       <td>
                           {{$v_request->type}}
                       </td>

                       <td>
                           <button class="btn btn-default approve-user" data-id="{{$v_request->id}}">Approve</button>
                       </td>
                   </tr>
               @endforeach

               </tbody>
           </table>


       </div>
   </div>
@endsection