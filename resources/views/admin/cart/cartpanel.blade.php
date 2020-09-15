@extends('layouts.admin')
@section('content')

<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <button type="button" class="nav-item nav-link btn-lg bg-transparent active" id="nav-manage-facilitys-tab" data-toggle="tab" data-target="#nav-manage-facilitys" role="tab" aria-controls="nav-manage-facilitys" aria-selected="false">Manage facilitys</button>
        <button type="button" class="nav-item nav-link btn-lg bg-transparent" id="nav-manage-categories-tab" data-toggle="tab" data-target="#nav-manage-categories" role="tab" aria-controls="nav-manage-categories" aria-selected="true">Manage categories</button>
        <button type="button" class="nav-item nav-link btn-lg bg-transparent" id="nav-manage-positions-tab" data-toggle="tab" data-target="#nav-manage-positions" role="tab" aria-controls="nav-manage-positions" aria-selected="false">Manage Others</button>
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
    <div class="tab-pane fade show active" id="nav-manage-facilitys" role="tabpanel" aria-labelledby="nav-manage-facilitys-tab">

        <div class="row" xmlns="http://www.w3.org/1999/html">

            <div class="col-sm-10">

                <div class="row">
                    <div class="row">{{ $carts->links() }}</div>
                    <div class="col-12">
                        <div class="table-responsive-sm">
                            <table class="table table-striped table-dark">
                                <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Delivery Date</th>
                                    <th>Date Placed</th>
                                    <th>Client</th>
                                    <th>Pay Status</th>
                                    <th>Order Information</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($carts as $c)
                                <tr>
                                    <td>{{$c->status}}  <button title="Order Status" data-content="{{$c->statusnote}}" data-toggle="popover"  data-html="true" data-trigger="focus" data-placement="auto" class="btn material-icons">more_vert</button></td>
                                    <td>{{$c->orderdate}} , {{$c->ordertime}} <a href="" onclick="event.preventDefault();" class="material-icons btn "  title="Order Note" data-content="{{$c->ordernote}}" data-placement="auto" data-toggle="popover" data-trigger="focus">more_vert</a> </td>
                                    <td>{{$c->dateofpublication}}</td>
                                    <td><div><img src="{{asset($c->userimageurl)}}" style="max-width: 90px; height: auto;" class="rounded-circle float-left" /> {{$c->username}}</div></td>
                                    <td>NGN{{ $c->orderamount}} Paid<span class="material-icon">question-mark</span> {{$c->paid}}</td>
                                    <td>
                                        <span>
                                            <small>order Category:</small>
                                            <br/>{{$c->cartcategoryname}}
                                            <br/> <small>order Type:</small>
                                            <br/> {{$c->carttypename}}
                                        </span><br/>

                                    </td>
                                    <td>
                                        <a href="{{route('editcart',["cartid"=>$c->id])}}" class="btn btn-warning rounded-pill material-icons">edit</a>
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-2"></div>
        </div>

    </div>

    <div class="tab-pane fade" id="nav-manage-categories" role="tabpanel" aria-labelledby="nav-manage-categories-tab">

        @foreach ($cartcategories as $r)
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
                    <form method="post" action="{{route('edit_cartcategory')}}">
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
                <h1 class="text-center"> Add New Order category <button data-target="#addcategory" data-toggle="collapse" class="btn bg-transparent material-icons">add</button></h1>

                <div class="row collapse" id="addcategory">
                    <div class="" >
                        <div class="">
                            <form method="post" action="{{route('create_cartcategory')}}">
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

        @foreach ($carttypes as $r)
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
                    <form method="post" action="{{route('edit_carttype')}}">
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
                <h1 class="text-center"> Add New Order Type <button data-target="#addcertificationlevel" data-toggle="collapse" class="btn bg-transparent material-icons">add</button></h1>

                <div class="row collapse" id="addcertificationlevel">
                    <div class="" >
                        <div class="">
                            <form method="post" action="{{route('create_carttype')}}">
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
@endsection