@extends('layouts.admin')
@section('content')

<nav>
    <div class="nav nav-tabs" id="nav-tab" category="tablist">
        <button type="button" class="nav-item nav-link btn-lg bg-transparent active" id="nav-manage-articles-tab" data-toggle="tab" data-target="#nav-manage-articles" category="tab" aria-controls="nav-manage-articles" aria-selected="false">Manage articles</button>
        <button type="button" class="nav-item nav-link btn-lg bg-transparent" id="nav-manage-categories-tab" data-toggle="tab" data-target="#nav-manage-categories" category="tab" aria-controls="nav-manage-categories" aria-selected="true">Manage categories</button>
        <button type="button" class="nav-item nav-link btn-lg bg-transparent" id="nav-manage-positions-tab" data-toggle="tab" data-target="#nav-manage-positions" category="tab" aria-controls="nav-manage-positions" aria-selected="false">Manage Others</button>
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
<div class="tab-pane fade show active" id="nav-manage-articles" category="tabpanel" aria-labelledby="nav-manage-articles-tab">
    <div class="row"  style="margin-top: 40px;">
        <div class="col-sm-6">
            <div class="py-0 form-group">
                <form method="post" action="{{route('userspanel')}}">
                    <div class="form-group form-row">
                        <div class="col col-2 form-group">    <label for="searcharticle" class="col-form-label">search article</label> </div>
                        <div class="col col-8"> <input type="search" class="input-lg form-control form-control-lg" id="searcharticle" debounce="5"> </div>
                        <div class="col col-2">    <button type="submit" class="btn btn-lg btn-primary"><span class="material-icons outline">live_help</span></button></div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-sm-6">
            @if (!empty($Selectedarticles))
            @foreach ($Selectedarticles as $SUs)
            <div class="row">
                <div class="row-cols-2"><img src="{{asset($SUs->imageurl)}}" class="img-fluid"></div>
                <div class="row-cols-1"><p>{{$SUs->title}}</p></div>
                <div class="row-cols-1"><a href="" class="btn btn-link">Select Me</a> </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>

    <div class="row">
        <h1 class="text-center">Add New Article <a href="{{route('adminarticleform')}}" class="material-icons">add</a> </h1>
    </div>

    <div class="row">
        <div class="col-12">
            @if (!empty($Adminarticles))
            <div class="row">{{ $Adminarticles->links() }}</div>
            @foreach ($Adminarticles as $AUs)
            <div class="row">
                <div class="col-1"><img src="{{asset($AUs->imageurl)}}" class="card-img-top icon"></div>
                <div class="col-3 text-wrap">{{$AUs->title}} </div>
                <div class="col-3 text-wrap"><small>By </small><img src="{{asset($AUs->userimageurl)}}" style="width: 50px; height: 40px;" class="rounded img-thumbnail float-left"> {{$AUs->username}} </div>
                <div class="col-2"><small class="material-icons">time</small> {{$AUs->dateofpublication}}</div>
                <div class="col-1"><button class="btn bg-transparent"  title="More Details" data-content="category: {{$AUs->categoryname}} | <small><span class='material-icons'>eye</span> {{$AUs->no_of_views}} | <small><span class='material-icons'>comment</span> {{$AUs->no_of_comments}}</small>" data-toggle="popover" data-trigger="focus" data-html="true"  data-placement="auto"><span class="rounded-pill small material-icons">more_vert</span> </button></div>
                <!--<div class="col-2"><button class="btn btn-link" onclick="function(event){event.target.preventDefault(); document.getElementById('getarticle_form').submit();}"><span class="material-icons">create</span> </button> </div>
                -->
                <div class="col-1">
                    <form method="get" action="{{route('updatearticle')}}" id="getarticle_form">
                        @csrf
                        <input type="hidden" name="articleid" value="{{$AUs->id}}">
                        <button type="submit" class="btn btn-link"><span class="material-icons">create</span> </button>
                    </form>
                </div>
            </div>
            @endforeach
            <div class="row">{{ $Adminarticles->links() }}</div>
            @endif
            <!--<div class="row">
                <div class="col">
                    <img src="/api/storage/app/public/ChinemeremPASSPORT.jpg">
                </div>
            </div>-->
        </div>
    </div>
</div>

<div class="tab-pane fade" id="nav-manage-categories" category="tabpanel" aria-labelledby="nav-manage-categories-tab">

    @foreach ($categories as $r)
    <br/><br/>&nbsp;
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-4">
            {{$r->name}} <br>
            <small>
                @if((!empty($r->parentcategory)) && ($r->parentcategory->id != $r->id))

                    parent, &nbsp;{{$r->parentcategory->name}} | <i>{{$r->parentcategory->description}}</i>

                @endif
            </small>
        </div>
        <div class="col-sm-3">
            <button class="btn btn-sm" title="category Description" data-content="<small>{{$r->description}}</small>" data-placement="auto" data-toggle="popover" data-html="true" data-trigger="focus" category="toggle"><span class="material-icons">more_vert</span> </button>
        </div>
        <div class="col-sm-2">
            <button class="material-icons btn btn-sm" data-toggle="collapse" data-target="#editcategory{{$r->id}}" category="toggle" aria-controls="#editcategory{{$r->id}}">create</button>
        </div>
    </div>

    <div class="row collapse" id="editcategory{{$r->id}}">
        <div class="form-row" >
            <div class="form-group">
                <form method="post" action="{{route('edit_category')}}">
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

                        <div class="row">
                            <label for="parentcategory" class="text-capitalize">{{__('if this is a sub-category choose a parent category')}}</label>
                            <select id="parentcategory" class="form-control md-select" name="parentcategoryid">
                                <option value="0">No Parent </option>
                                @foreach($categories as $c)
                                <option value="{{$c->id}}" @if($c->id == $c->parentcategory->id) selected @endif>{{$c->name}}</option>
                                @endforeach
                            </select>
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
            <h1 class="text-center"> Add New category <button data-target="#addcategory" data-toggle="collapse" class="btn bg-transparent material-icons">add</button></h1>

            <div class="row collapse" id="addcategory">
                <div class="" >
                    <div class="">
                        <form method="post" action="{{route('create_category')}}">
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
                                <div class="row">
                                    <label for="parentcategory" class="text-capitalize">{{__('if this is a sub-category choose a parent category')}}</label>
                                    <select id="parentcategory" class="md-select form-control form-control-lg" name="parentcategoryid">
                                        <option value="0">No Parent </option>
                                        @foreach($categories as $c)
                                        <option value="{{$c->id}}" @if(old('parentcategoryid')== $c->id) selected @endif>{{$c->name}}</option>
                                        @endforeach
                                    </select>
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

<div class="tab-pane fade" id="nav-manage-positions" category="tabpanel" aria-labelledby="nav-manage-positions-tab">

</div>

</div>
</div>
@endsection