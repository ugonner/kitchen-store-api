@extends('layouts.admin')
@section('content')

<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <button type="button" class="nav-item nav-link btn-lg bg-transparent active" id="nav-manage-organizations-tab" data-toggle="tab" data-target="#nav-manage-organizations" role="tab" aria-controls="nav-manage-organizations" aria-selected="false">Manage organizations</button>
        <button type="button" class="nav-item nav-link btn-lg bg-transparent" id="nav-manage-categories-tab" data-toggle="tab" data-target="#nav-manage-categories" role="tab" aria-controls="nav-manage-categories" aria-selected="true">Manage categories</button>
        <button type="button" class="nav-item nav-link btn-lg bg-transparent" id="nav-manage-positions-tab" data-toggle="tab" data-target="#nav-manage-positions" role="tab" aria-controls="nav-manage-positions" aria-selected="false">Manage Others</button>
    </div>
</nav>

<div class="row">


    @if ($errors->any())
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

@if (!empty($message))
<h5 class="alert alert-danger">{{$message}}</h5>
@endif
<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-manage-organizations" role="tabpanel" aria-labelledby="nav-manage-organizations-tab">
        <div class="row"  style="margin-top: 40px;">
            <div class="col-sm-6">
                <div class="py-0 form-group">
                    <form method="post" action="{{route('userspanel')}}">
                        <div class="form-group form-row">
                            <div class="col col-2 form-group">    <label for="searchorganization" class="col-form-label">search organization</label> </div>
                            <div class="col col-8"> <input type="search" class="input-lg form-control form-control-lg" id="searchorganization" debounce="5"> </div>
                            <div class="col col-2">    <button type="submit" class="btn btn-lg btn-primary"><span class="material-icons outline">live_help</span></button></div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-sm-6">
                @if (!empty($Selectedorganizations))
                @foreach ($Selectedorganizations as $SUs)
                <div class="row">
                    <div class="row-cols-2"><img src="{{asset($SUs->imageurl)}}" class="img-fluid"></div>
                    <div class="row-cols-1"><p>{{$SUs->name}}</p></div>
                    <div class="row-cols-1"><a href="" class="btn btn-link">Select Me</a> </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>

        <div class="row">
            <h1 class="text-center">Add New organization <a href="{{route('adminorganizationform')}}" class="material-icons">add</a> </h1>
        </div>

        <div class="row">
            <div class="col-12">
                @if (!empty($Adminorganizations))
                <div class="row">{{ $Adminorganizations->links() }}</div>
                @foreach ($Adminorganizations as $AUs)
                <div class="row">
                    <div class="col-1"><img src="{{asset($AUs->imageurl)}}" class="card-img-top icon"></div>
                    <div class="col-3 text-wrap">{{$AUs->name}} </div>
                    <div class="col-3 text-wrap"><small>By </small><img src="{{asset($AUs->userimageurl)}}" style="width: 50px; height: 40px;" class="rounded img-thumbnail float-left"> {{$AUs->username}} </div>
                    <div class="col-2"><small class="material-icons">time</small> {{$AUs->dateofformation}}</div>
                    <div class="col-1"><button class="btn bg-transparent"  title="More Details" data-content="Event category: {{$AUs->organizationcategoryname}} <br/>| Category: {{$AUs->categoryname}} <br/>| <small><span class='material-icons'>view</span> {{$AUs->no_of_views}} | <small><span class='material-icons'>comment</span> {{$AUs->no_of_comments}}</small>" data-toggle="popover" data-trigger="focus" data-html="true"  data-placement="auto"><span class="rounded-pill small material-icons">more_vert</span> </button></div>
                    <!--<div class="col-2"><button class="btn btn-link" onclick="function(organization){organization.target.prorganizationDefault(); document.getElementById('getorganization_form').submit();}"><span class="material-icons">create</span> </button> </div>
                    -->
                    <div class="col-1">
                        <form method="get" action="{{route('updateorganization')}}" id="getorganization_form">
                            @csrf
                            <input type="hidden" name="organizationid" value="{{$AUs->id}}">
                            <button type="submit" class="btn btn-link"><span class="material-icons">create</span> </button>
                        </form>
                    </div>
                </div>
                @endforeach
                <div class="row">{{ $Adminorganizations->links() }}</div>
                @endif
                <!--<div class="row">
                    <div class="col">
                        <img src="/api/storage/app/public/ChinemeremPASSPORT.jpg">
                    </div>
                </div>-->
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="nav-manage-categories" role="tabpanel" aria-labelledby="nav-manage-categories-tab">

        @foreach ($organizationcategories as $r)
        <br/><br/>&nbsp;
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-4">
                {{$r->name}}
            </div>
            <div class="col-sm-3">
                <button class="btn btn-sm" title="category Description" data-content="<small>{{$r->description}}</small>" data-placement="auto" data-toggle="popover" data-html="true" data-trigger="focus" role="toggle"><span class="material-icons">more_vert</span> </button>
            </div>
            <div class="col-sm-2">
                <button class="material-icons btn btn-sm" data-toggle="collapse" data-target="#editcategory{{$r->id}}" role="toggle" aria-controls="#editcategory{{$r->id}}">create</button>
            </div>
        </div>

        <div class="row collapse" id="editcategory{{$r->id}}">
            <div class="form-row" >
                <div class="form-group">
                    <form method="post" action="{{route('edit_organizationcategory')}}">
                        @csrf
                        <div class="row">
                            <div class="col-sm-3 form-group">
                                <input type="hidden" name="r" value="true">
                                <input type="hidden" name="id" value="{{$r->id}}">
                                <label for="name{{$r->id}}">{{__('Name')}}</label>
                                <input id="name{{$r->id}}" class="form-control form-control-lg" type="text" name="name" value="{{$r->name}}">
                            </div>
                            <div class="col-sm-5 form-group">
                                <label for="category_desc{{$r->id}}">{{__('Description')}}</label>
                                <textarea id="category_desc{{$r->id}}" class="form-control form-control-lg" name="description">{{$r->description}}</textarea>
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
                <h1 class="text-center"> Add New organization category <button data-target="#addcategory" data-toggle="collapse" class="btn bg-transparent material-icons">add</button></h1>

                <div class="row collapse" id="addcategory">
                    <div class="" >
                        <div class="">
                            <form method="post" action="{{route('create_organizationcategory')}}">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-3 form-group">
                                        <input type="hidden" name="r" value="true">
                                        <label for="categoryname">{{__('Name')}}</label>
                                        <input id="categoryname" class="form-control form-control-lg" type="text" name="name" value="{{old('name','')}}" placeholder="Name of category">
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="category_desc">{{__('Description')}}</label>
                                        <textarea id="category_desc" class="form-control form-control-lg" placeholder="short description" name="description">{{old('description','')}}</textarea>
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

        @foreach ($organizationcertificationlevels as $r)
        <br/><br/>&nbsp;
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-4">
                {{$r->name}}
            </div>
            <div class="col-sm-3">
                <button class="btn btn-sm" title="category Description" data-content="<small>{{$r->description}}</small>" data-placement="auto" data-toggle="popover" data-html="true" data-trigger="focus" role="toggle"><span class="material-icons">more_vert</span> </button>
            </div>
            <div class="col-sm-2">
                <button class="material-icons btn btn-sm" data-toggle="collapse" data-target="#editcertificationlevel{{$r->id}}" role="toggle" aria-controls="#editcertificationlevel{{$r->id}}">create</button>
            </div>
        </div>

        <div class="row collapse" id="editcertificationlevel{{$r->id}}">
            <div class="form-row" >
                <div class="form-group">
                    <form method="post" action="{{route('edit_organizationcertificationlevel')}}">
                        @csrf
                        <div class="row">
                            <div class="col-sm-3 form-group">
                                <input type="hidden" name="r" value="true">
                                <input type="hidden" name="id" value="{{$r->id}}">
                                <label for="name{{$r->id}}">{{__('Name')}}</label>
                                <input id="name{{$r->id}}" class="form-control form-control-lg" type="text" name="name" value="{{$r->name}}">
                            </div>
                            <div class="col-sm-5 form-group">
                                <label for="category_desc{{$r->id}}">{{__('Description')}}</label>
                                <textarea id="category_desc{{$r->id}}" class="form-control form-control-lg" name="description">{{$r->description}}</textarea>
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
                <h1 class="text-center"> Add New organization certification level <button data-target="#addcertificationlevel" data-toggle="collapse" class="btn bg-transparent material-icons">add</button></h1>

                <div class="row collapse" id="addcertificationlevel">
                    <div class="" >
                        <div class="">
                            <form method="post" action="{{route('create_organizationcertificationlevel')}}">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-3 form-group">
                                        <input type="hidden" name="r" value="true">
                                        <label for="categoryname">{{__('Name')}}</label>
                                        <input id="categoryname" class="form-control form-control-lg" type="text" name="name" value="{{old('name','')}}" placeholder="Name of category">
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="category_desc">{{__('Description')}}</label>
                                        <textarea id="category_desc" class="form-control form-control-lg" placeholder="short description" name="description">{{old('description','')}}</textarea>
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