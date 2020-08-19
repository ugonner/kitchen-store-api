@extends('layouts.admin')
@section('content')

<div class="row">
    <div class="col-sm-2"></div>
    <div class="col-sm-10">


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

        @foreach ($focalareas as $r)
        <br/><br/>&nbsp;
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-4">
                {{$r->name}}
            </div>
            <div class="col-sm-3">
                <button class="btn btn-sm" title="focalarea Description" data-content="<small>{{$r->description}}</small>" data-placement="auto" data-toggle="popover" data-html="true" data-trigger="focus" role="toggle"><span class="material-icons">more_vert</span> </button>
            </div>
            <div class="col-sm-2">
                <button class="material-icons btn btn-sm" data-toggle="collapse" data-target="#editfocalarea{{$r->id}}" role="toggle" aria-controls="#editfocalarea{{$r->id}}">create</button>
            </div>
        </div>

        <div class="row collapse" id="editfocalarea{{$r->id}}">
            <div class="form-row" >
                <div class="form-group">
                    <form method="post" action="{{route('edit_focalarea')}}">
                        @csrf
                        <div class="row">
                            <div class="col-sm-3 form-group">
                                <input type="hidden" name="r" value="true">
                                <input type="hidden" name="id" value="{{$r->id}}">
                                <label for="name{{$r->id}}">{{__('Name')}}</label>
                                <input id="name{{$r->id}}" class="form-control form-control-lg" type="text" name="name" value="{{$r->name}}">
                            </div>
                            <div class="col-sm-5 form-group">
                                <label for="focalarea_desc{{$r->id}}">{{__('Description')}}</label>
                                <textarea id="focalarea_desc{{$r->id}}" class="form-control form-control-lg" name="description">{{$r->description}}</textarea>
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
                <h1 class="text-center"> Add New organization focalarea <button data-target="#addfocalarea" data-toggle="collapse" class="btn bg-transparent material-icons">add</button></h1>

                <div class="row collapse" id="addfocalarea">
                    <div class="" >
                        <div class="">
                            <form method="post" action="{{route('create_focalarea')}}">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-3 form-group">
                                        <input type="hidden" name="r" value="true">
                                        <label for="focalareaname">{{__('Name')}}</label>
                                        <input id="focalareaname" class="form-control form-control-lg" type="text" name="name" value="{{old('name','')}}" placeholder="Name of focalarea">
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="focalarea_desc">{{__('Description')}}</label>
                                        <textarea id="focalarea_desc" class="form-control form-control-lg" placeholder="short description" name="description">{{old('description','')}}</textarea>
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

@endsection


<div class="tab-pane fade" id="nav-manage-categories" role="tabpanel" aria-labelledby="nav-manage-categories-tab">

</div>
