@extends('layouts.admin')
@section('content')

<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <button type="button" class="nav-item nav-link btn-lg bg-transparent active" id="nav-manage-users-tab" data-toggle="tab" data-target="#nav-manage-users" role="tab" aria-controls="nav-manage-users" aria-selected="false">Manage Users</button>
        <button type="button" class="nav-item nav-link btn-lg bg-transparent" id="nav-manage-roles-tab" data-toggle="tab" data-target="#nav-manage-roles" role="tab" aria-controls="nav-manage-roles" aria-selected="true">Manage Roles</button>
        <button type="button" class="nav-item nav-link btn-lg bg-transparent" id="nav-manage-positions-tab" data-toggle="tab" data-target="#nav-manage-positions" role="tab" aria-controls="nav-manage-positions" aria-selected="false">Manage Positions</button>
    </div>
</nav>

<div class="row">

    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif


    @if ($message = Session::get('output'))
    <div class="alert alert-success">
        <strong>{{ $message }}</strong>
    </div>
    @endif

</div>

<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-manage-users" role="tabpanel" aria-labelledby="nav-manage-users-tab">
        <div class="row"  style="margin-top: 40px;">
            <div class="col-sm-6">
                <div class="py-0 form-group">
                    <form method="post" action="{{route('userspanel')}}">
                        <div class="form-group form-row">
                            <div class="col col-2 form-group">    <label for="searchuser" class="col-form-label">search user</label> </div>
                            <div class="col col-8"> <input type="search" class="input-lg form-control form-control-lg" id="searchuser" debounce="5"> </div>
                            <div class="col col-2">    <button type="submit" class="btn btn-lg btn-primary"><span class="material-icons outline">live_help</span></button></div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-sm-6">
                @if (!empty($SelectedUsers))
                    @foreach ($SelectedUsers as $SUs)
                        <div class="row">
                            <div class="row-cols-2"><img src="{{'/'.$SUs->imageurl}}" class="img-fluid"></div>
                            <div class="row-cols-1"><p>{{$SUs->name}}</p></div>
                            <div class="row-cols-1"><a href="" class="btn btn-link">Select Me</a> </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="row">
            <h1 class="text-center">Add New Member <a href="{{route('adminregform')}}" class="material-icons">add</a> </h1>
        </div>

        <div class="row">
            <div class="col-12">
                    @if (!empty($AdminUsers))
                <div class="row">{{ $AdminUsers->links() }}</div>
                    @foreach ($AdminUsers as $AUs)
                    <div class="row">
                        <div class="col-1"><img src="{{asset($AUs->imageurl)}}" class="card-img-top icon"></div>
                        <div class="col-7">{{$AUs->name}} </div>
                        <div class="col-2"><button class="btn bg-transparent"  title="More Details" data-content="Role: {{$AUs->rolename}} | Position: {{$AUs->positionname}} <br> <small>{{$AUs->rolenote}}</small>" data-toggle="popover" data-trigger="focus" data-html="true"  data-placement="auto"><span class="rounded-pill small material-icons">more_vert</span> </button></div>
                        <!--<div class="col-2"><button class="btn btn-link" onclick="function(event){event.target.preventDefault(); document.getElementById('getuser_form').submit();}"><span class="material-icons">create</span> </button> </div>
                        -->
                        <div class="col-2">
                            <form method="get" action="{{route('updateuser')}}" id="getuser_form">
                                @csrf
                                <input type="hidden" name="userid" value="{{$AUs->id}}">
                                <button type="submit" class="btn btn-link"><span class="material-icons">create</span> </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                    <div class="row">{{ $AdminUsers->links() }}</div>
                    @endif
                <!--<div class="row">
                    <div class="col">
                        <img src="/api/storage/app/public/ChinemeremPASSPORT.jpg">
                    </div>
                </div>-->
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="nav-manage-roles" role="tabpanel" aria-labelledby="nav-manage-roles-tab">
            @foreach ($roles as $r)
            <div class="row">
                <div class="col-sm-2">&nbsp;</div>
                <div class="col-sm-6">{{$r->name}}</div>
                <div class="col-sm-2">
                    <button class="btn btn-sm" title="Role Description" data-content="<small>{{$r->description}}</small>" data-placement="auto" data-toggle="popover" data-html="true" data-trigger="focus" role="toggle"><span class="material-icons">more_vert</span> </button>
                </div>
                <div class="col-sm-2">
                    <button class="material-icons btn btn-sm" data-toggle="collapse" data-target="#editrole{{$r->id}}" role="toggle" aria-controls="#editrole{{$r->id}}">create</button>
                </div>
            </div>

            <div class="row collapse" id="editrole{{$r->id}}">
                <div class="form-row" >
                    <div class="form-group">
                        <form method="post" action="{{route('edit_roles_or_positions')}}">
                            @csrf
                            <div class="row">
                                <div class="col-sm-3 form-group">
                                    <input type="hidden" name="r" value="true">
                                    <input type="hidden" name="id" value="{{$r->id}}">
                                    <label for="name{{$r->id}}">{{__('Name')}}</label>
                                    <input id="name{{$r->id}}" class="form-control form-control-lg" type="text" name="name" value="{{$r->name}}">
                                </div>
                                <div class="col-sm-5 form-group">
                                    <label for="role_desc{{$r->id}}">{{__('Description')}}</label>
                                    <textarea id="role_desc{{$r->id}}" class="form-control form-control-lg" name="description">{{$r->description}}</textarea>
                                </div>
                                <div class="col-sm-2 form-group">
                                    <label>{{__('Save')}}</label><br/>
                                    <button class="btn btn-sm" type="submit"><span class="material-icons">save</span> </button>
                                </div>
                                <div class="col-sm-2">&nbsp;
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @endforeach


        <div class="row">
            <div class="col-sm-12">
                <h1 class="text-center"> Add New Role <button data-target="#addrole" data-toggle="collapse" class="btn bg-transparent material-icons">add</button></h1>

                <div class="row collapse" id="addrole">
                    <div class="" >
                        <div class="">
                            <form method="post" action="{{route('create_roles_or_positions')}}">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-3 form-group">
                                        <input type="hidden" name="r" value="true">
                                        <label for="rolename">{{__('Name')}}</label>
                                        <input id="rolename" class="form-control form-control-lg" type="text" name="name" value="{{old('name','')}}" placeholder="Name of Role">
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="role_desc">{{__('Description')}}</label>
                                        <textarea id="role_desc" class="form-control form-control-lg" placeholder="short description" name="description">{{old('description','')}}</textarea>
                                    </div>
                                    <div class="col-sm-2 form-group">
                                        <label>{{__('Save')}}</label><br/>
                                        <button class="btn btn-sm" type="submit"><span class="material-icons">save</span> </button>
                                    </div>
                                    <div class="col-sm-1">&nbsp;
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        </div>

    <div class="tab-pane fade" id="nav-manage-positions" role="tabpanel" aria-labelledby="nav-manage-positions-tab">


            @foreach ($positions as $r)
            <div class="row">
                <div class="col-sm-2">&nbsp;</div>
                <div class="col-sm-6">{{$r->name}}</div>
                <div class="col-sm-2">
                    <button class="btn btn-sm" title="position Description" data-content="<small>{{$r->description}}</small>" data-placement="auto" data-toggle="popover" data-html="true" data-trigger="focus" position="toggle"><span class="material-icons">more_vert</span> </button>
                </div>
                <div class="col-sm-2">
                    <button class="material-icons btn btn-sm" data-toggle="collapse" data-target="#editposition{{$r->id}}" position="toggle" aria-controls="#editposition{{$r->id}}">create</button>
                </div>
            </div>

            <div class="row collapse" id="editposition{{$r->id}}">
                <div class="form-row" >
                    <div class="form-group">
                        <form method="post" action="{{route('edit_roles_or_positions')}}">
                            @csrf
                            <div class="row">
                                <div class="col-sm-3 form-group">
                                    <input type="hidden" name="p" value="true">
                                    <input type="hidden" name="id" value="{{$r->id}}">
                                    <label for="name{{$r->id}}">{{__('Name')}}</label>
                                    <input id="name{{$r->id}}" class="form-control form-control-lg" type="text" name="name" value="{{$r->name}}">
                                </div>
                                <div class="col-sm-5 form-group">
                                    <label for="position_desc{{$r->id}}">{{__('Description')}}</label>
                                    <textarea id="position_desc{{$r->id}}" class="form-control form-control-lg" name="description">{{$r->description}}</textarea>
                                </div>
                                <div class="col-sm-2 form-group">
                                    <label>{{__('Save')}}</label><br/>
                                    <button class="btn btn-sm" type="submit"><span class="material-icons">save</span> </button>
                                </div>
                                <div class="col-sm-2">&nbsp;
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @endforeach


            <div class="row">
                <div class="col-sm-12">
                    <h1 class="text-center"> Add New position <button data-target="#addposition" data-toggle="collapse" class="btn bg-transparent material-icons">add</button></h1>

                    <div class="row collapse" id="addposition">
                        <div class="" >
                            <div class="">
                                <form method="post" action="{{route('create_roles_or_positions')}}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-3 form-group">
                                            <input type="hidden" name="p" value="true">
                                            <label for="positionname">{{__('Name')}}</label>
                                            <input id="positionname" class="form-control form-control-lg" type="text" name="name" value="{{old('name','')}}" placeholder="Name of position">
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label for="position_desc">{{__('Description')}}</label>
                                            <textarea id="position_desc" class="form-control form-control-lg" placeholder="short description" name="description">{{old('description','')}}</textarea>
                                        </div>
                                        <div class="col-sm-2 form-group">
                                            <label>{{__('Save')}}</label><br/>
                                            <button class="btn btn-sm" type="submit"><span class="material-icons">save</span> </button>
                                        </div>
                                        <div class="col-sm-1">&nbsp;
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
    </div>

</div>
</div>
@endsection