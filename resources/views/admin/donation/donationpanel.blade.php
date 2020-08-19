@extends('layouts.admin')
@section('content')

<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <button type="button" class="nav-item nav-link btn-lg bg-transparent active" id="nav-manage-donations-tab" data-toggle="tab" data-target="#nav-manage-donations" role="tab" aria-controls="nav-manage-donations" aria-selected="false">Manage donations</button>
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
<div class="tab-pane fade show active" id="nav-manage-donations" role="tabpanel" aria-labelledby="nav-manage-donations-tab">
    <div class="row"  style="margin-top: 40px;">
        <div class="col-sm-6">
            <div class="py-0 form-group">
                <form method="post" action="{{route('userspanel')}}">
                    <div class="form-group form-row">
                        <div class="col col-2 form-group">    <label for="searchdonation" class="col-form-label">search donation</label> </div>
                        <div class="col col-8"> <input type="search" class="input-lg form-control form-control-lg" id="searchdonation" debounce="5"> </div>
                        <div class="col col-2">    <button type="submit" class="btn btn-lg btn-primary"><span class="material-icons outline">live_help</span></button></div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-sm-6">
            @if (!empty($Selecteddonations))
            @foreach ($Selecteddonations as $SUs)
            <div class="row">
                <div class="row-cols-2"><img src="{{asset($SUs->userimageurl)}}" class="img-fluid"></div>
                <div class="row-cols-1"><p>{{$SUs->description}}</p></div>
                <div class="row-cols-1"><a href="" class="btn btn-link">Select Me</a> </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>

    <div class="row">
        <h1 class="text-center">Add New donation <a href="{{route('admindonationform')}}" class="material-icons">add</a> </h1>
    </div>

    <div class="row">
        <div class="col-12">
            @if (!empty($Admindonations))
            <div class="row">{{ $Admindonations->links() }}</div>
            @foreach ($Admindonations as $AUs)
            <div class="row">
                <div class="col-1"><img src="{{asset($AUs->userimageurl)}}" class="card-img-top icon"></div>
                <div class="col-3 text-wrap">{{$AUs->description}} </div>
                <div class="col-3 text-wrap"><small>By </small><img src="{{asset($AUs->userimageurl)}}" style="width: 50px; height: 40px;" class="rounded img-thumbnail float-left"> {{$AUs->username}} </div>
                <div class="col-2"><small class="material-icons">time</small> {{$AUs->dateofdonation}} | Redeemed: {{$AUs->redeemed}}</div>
                <div class="col-1"><button class="btn bg-transparent"  title="More Details" data-content="Redeemed: {{$AUs->redeemed}} <br/>| Category: {{$AUs->focalareaname}} " data-toggle="popover" data-trigger="focus" data-html="true"  data-placement="auto"><span class="rounded-pill small material-icons">more_vert</span> </button></div>
                <!--<div class="col-2"><button class="btn btn-link" onclick="function(donation){donation.target.prdonationDefault(); document.getElementById('getdonation_form').submit();}"><span class="material-icons">create</span> </button> </div>
                -->
                <div class="col-1">
                    <form method="get" action="{{route('updatedonation')}}" id="getdonation_form">
                        @csrf
                        <input type="hidden" name="donationid" value="{{$AUs->id}}">
                        <button type="submit" class="btn btn-link"><span class="material-icons">create</span> </button>
                    </form>
                </div>
            </div>
            @endforeach
            <div class="row">{{ $Admindonations->links() }}</div>
            @endif
            <!--<div class="row">
                <div class="col">
                    <img src="/api/storage/app/public/ChinemeremPASSPORT.jpg">
                </div>
            </div>-->
        </div>
    </div>
</div>



    <div class="tab-pane fade" id="nav-manage-categories" role="tabpanel" aria-labelledby="nav-manage-positions-tab">
    </div>

    <div class="tab-pane fade" id="nav-manage-positions" role="tabpanel" aria-labelledby="nav-manage-positions-tab">
    </div>
</div>
</div>
@endsection