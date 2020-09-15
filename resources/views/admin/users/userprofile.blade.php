@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-sm-8">
            <div class="card">
                <div class="card-header">
                    <div>
                        <img  class="card-img-top rounded-circle" src="{{asset($user->imageurl)}}" alt="image of {{$user->name}}"/>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive-sm">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{$user->name}}</th> <th>Profile Information</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>role</td><td> {{$user->rolenote}}</td>
                            </tr>
                            <tr>
                                <td>Position</td><td> {{$user->positionname}}</td>
                            </tr>
                            <tr>
                                <td>Nativity</td><td> {{$user->locationname}} | {{$user->sublocationname}}</td>
                            </tr>
                            <tr>
                                <td>Mobile</td><td> {{$user->mobile}}</td>
                            </tr>
                            <tr>
                                <td>Address</td><td> {{$user->address}}</td>
                            </tr>
                            <tr>
                                <td>Bio</td><td></td><td> {{$user->about}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive-sm">

                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">

        </div>
    </div>
@endsection