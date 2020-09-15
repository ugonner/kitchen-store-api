@extends('layouts.admin')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">

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

        <div class="row">


            <div class="form-group">
                <form method="POST" action="{{ route('update') }}" enctype="multipart/form-data">
                    @csrf

                    <div id="demo" class="carousel slide" data-interval="false">

                        <!-- The slideshow -->
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <h2>Personal Info (step 1 of 5)</h2>

                                <div class="form-group form-row">
                                    <input type="hidden" name="userid" value="{{$user->id}}">
                                    <div class="form-group col">
                                        <label for="name" class="col-form-label text-md-right">{{ __('Name') }}</label>
                                        <input id="name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" name="name" value="{{ (!empty($user)? $user->name: old('name')) }}" required autocomplete="name" autofocus>

                                        @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col offset-2">
                                        <label for="email" class="col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
                                        <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ (!empty($user)? $user->email : old('email')) }}" required autocomplete="email">

                                        @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col">
                                        <label for="password" class="col-form-label text-md-right">{{ __('Password') }}</label>
                                        <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required value="(!empty($user)? $user->password : old('password'))" autocomplete="new-password">
                                        @error('password')
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                 </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col offset-2">
                                        <label for="password-confirm" class="col-form-label text-md-right">{{ __('Confirm Password') }}</label>
                                        <input id="password-confirm" type="password" class="input-lg form-control form-control-lg" name="password_confirmation" required autocomplete="new-password">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col">
                                        <label for="mobile" class="col-form-label text-md-right">{{ __('Mobile') }}</label>
                                        <input id="mobile" type="tel" class="form-control form-control-lg @error('mobile') is-invalid @enderror" name="mobile" value="{{(!empty($user)? $user->mobile : old('mobile'))}}" required>
                                        @error('mobile')
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                 </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col offset-2">
                                        <label for="zip" class="col-form-label text-md-right">{{ __('Country Zip') }}</label>
                                        <input id="zip" type="number" class="input-lg form-control form-control-lg" name="zip" required value="{{old('zip')? old('zip'): '234'}}">
                                        @error('zip')
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                 </span>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Left and right controls -->
                                <div class="row">
                                    <div class="col text-left">
                                        <a class="" href="#demo" data-slide="prev">
                                            <span class="material-icons">west</span>
                                        </a>
                                    </div>
                                    <div class="col text-center">
                                        <button class="btn btn-lg btn-info rounded-pill" data-target="#demo" data-slide-to="1">Continue <span class=" material-icons">east</span></button>
                                    </div>
                                    <div class="col text-right">
                                        <a class="" href="#demo" data-slide="next">
                                            <span class="material-icons">east</span>
                                        </a>
                                    </div>
                                </div>

                            </div>


                            <div class="carousel-item">
                                <h2>Department And Position (Step 2 of 5)</h2>

                                <div class="row">
                                    <label for="" class="">Department</label>
                                    <div class="col">
                                    <div class="form-group">
                                        <input type="hidden" class="form-control" name="focalareascount" value="{{(count($focalareas) - count($user_focalareas))}}">
                                    </div>
                                    <!--@php $filled_focalareas = (!empty($user_focalareas)? $user_focalareas : old('')) @endphp-->

                                        @php
                                            for ($fs=0; $fs < count($focalareas); $fs++){
                                                for ($ufs=0; $ufs < count($user_focalareas); $ufs++){
                                                    if($focalareas[$fs]->id == $user_focalareas[$ufs]->focalareaid){
                                                        $focalareas[$fs]['selected'] = true;
                                                    }
                                                }

                                            }
                                        @endphp
                                        @for ($fa=0; $fa< count($focalareas); $fa++)
                                            <div class="row">
                                            @if ((isset($focalareas[$fa]['selected'])) && ($focalareas[$fa]['selected'] == true))
                                                <span></span>
                                            @else
                                                <div class="form-group custom-control custom-switch">
                                                    <input  id="focalarea{{$fa}}" class="custom-control-input" type="checkbox" name="focalarea{{$fa}}" value="{{$focalareas[$fa]->id}}"/>
                                                    <label for="focalarea{{$fa}}" class="custom-control-label">{{ __('Join '). $focalareas[$fa]->name}}</label>
                                                </div>
                                            @endif
                                            </div>
                                    @endfor
                                </div>

                                <div class="col">
                                    <div class="form-group">
                                        <label for="positionid" class="form-label text-md-right">{{ __('Position') }}</label>
                                        <select id="positionid" class="form-control form-control-lg @error('positionid') is-invalid @enderror" name="positionid" required>
                                            <option value="">Please Select Position</option>
                                            @foreach ($positions as $p)
                                            <option value="{{$p->id }}" @if ($user->positionid == $p->id) selected @endif>{{$p->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('positionid')
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                 </span>
                                        @enderror
                                    </div>
                                </div>
                                </div>

                                <!-- Left and right controls -->
                                <div class="row">
                                    <div class="col text-left">
                                        <a class="" href="#demo" data-slide="prev">
                                            <span class="material-icons">west</span>
                                        </a>
                                    </div>
                                    <div class="col text-center">
                                        <button class="btn btn-lg btn-info rounded-pill" data-target="#demo" data-slide-to="2">Continue <span class=" material-icons">east</span></button>
                                    </div>
                                    <div class="col text-right">
                                        <a class="" href="#demo" data-slide="next">
                                            <span class="material-icons">east</span>
                                        </a>
                                    </div>
                                </div>
                            </div>


                            <div class="carousel-item">

                                <h2>Role And Description (Step 3 of 5)</h2>
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="roleid" class="col-form-label text-md-right">{{ __('Role') }}</label>
                                        <select id="roleid" class="form-control form-control-lg @error('roleid') is-invalid @enderror" name="roleid" required>
                                            <option value="">Please Select role</option>
                                            @foreach ($roles as $role)
                                            <option value="{{$role->id}}" @if ($user->roleid == $role->id) selected @endif>{{$role->name}}</option>
                                            @endforeach
                                        </select>

                                        @error('roleid')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col offset-2">
                                        <label for="rolenote" class="col-form-label text-md-right">{{ __('Role Brief') }}</label>
                                        <textarea id="rolenote" class="input-lg form-control form-control-lg @error('rolenote') is-invalid @enderror" name="rolenote" required> {{(!empty($user)? $user->rolenote : 'Not Available') }}</textarea>
                                        @error('rolenote')
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                 </span>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Left and right controls -->
                                <div class="row">
                                    <div class="col text-left">
                                        <a class="" href="#demo" data-slide="prev">
                                            <span class="material-icons">west</span>
                                        </a>
                                    </div>
                                    <div class="col text-center">
                                        <button class="btn btn-lg btn-info rounded-pill" data-target="#demo" data-slide-to="3"> Continue <span class="material-icons">eat</span></button>
                                    </div>
                                    <div class="col text-right">
                                        <a class="" href="#demo" data-slide="next">
                                            <span class="material-icons">east</span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="carousel-item v-100">
                                <h2>Group (Step 4 of 5)</h2>
                                <div class="row">
                                    <div class="col-sm-12 form-group">

                                        <div class="form-group">
                                            <input type="hidden" class="form-control" name="clusterscount" value="{{(count($clusters) - count($user_clusters))}}">
                                        </div>


                                        @php
                                        for ($cl=0; $cl<count($clusters); $cl++){
                                        for ($ucs=0; $ucs<count($user_clusters); $ucs++){
                                        if($clusters[$cl]->id == $user_clusters[$ucs]->clusterid){
                                        $clusters[$cl]['selected'] = true;
                                        }
                                        }

                                        }
                                        @endphp
                                        @for ($cl=0; $cl< count($clusters); $cl++)

                                        <div class="row">
                                            @if ((isset($clusters[$cl]['selected'])) && ($clusters[$cl]['selected'] == true))
                                            <span></span>

                                            @else

                                            <div class="form-group custom-control custom-switch">
                                                <input  id="cluster{{$cl}}" class="custom-control-input" type="checkbox" name="cluster{{$cl}}"  value="{{$clusters[$cl]->id}}" />
                                                <label for="cluster{{$cl}}" class="custom-control-label">{{ __('Join '). $clusters[$cl]->name}}</label>
                                            </div>
                                            @endif
                                        </div>
                                        @endfor

                                    </div>
                                </div>

                                <!-- Left and right controls -->
                                <div class="row">
                                    <div class="col text-left">
                                        <a class="" href="#demo" data-slide="prev">
                                            <span class="material-icons">west</span>
                                        </a>
                                    </div>
                                    <div class="col text-center">
                                        <button class="btn btn-lg btn-info rounded-pill" data-target="#demo" data-slide-to="4">Continue<span class="material-icons">arrow_right_alt</span></button>
                                    </div>
                                    <div class="col text-right">
                                        <a class="" href="#demo" data-slide="next">
                                            <span class="material-icons">east</span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="carousel-item w-100">
                                <h2>Group (...Almost There)</h2>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label for="about" class="form-label">{{__('Bio Brief')}}</label>
                                                <textarea name="about" id="about" class="form-control form-control-lg @error('about') is-invalid @enderror">{{(!empty($user)? $user->about : 'Just me')}}</textarea>
                                            </div>
                                            @error('about')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Left and right controls -->
                                <div class="row">
                                    <div class="col text-left">
                                        <a class="" href="#demo" data-slide="prev">
                                            <span class="material-icons">west</span>
                                        </a>
                                    </div>
                                    <div class="col text-center">
                                        <button class="btn btn-lg btn-info rounded-pill" data-target="#demo" data-slide-to="5">Continue<span class="material-icons">arrow_right_alt</span></button>
                                    </div>
                                    <div class="col text-right">
                                        <a class="" href="#demo" data-slide="next">
                                            <span class="material-icons">east</span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="carousel-item">
                                <div class="row">
                                    <h2>Upload Profile Picture (Step 5 of 5)</h2>
                                    <div class="form-group">
                                        <div>
                                            <img src="{{asset($user->imageurl)}}" class="img-fluid">
                                            <input type="hidden" name="old_imageurl" value="{{$user->imageurl}}">
                                        </div>
                                        <label for="imageurl">{{ __('Change Profile Picture')}}</label>
                                        <input type="file" name="imageurl" class="btn btn-primary btn-sm rounded-pill form-control-file @error('imageurl') is-invalid @enderror " id="imageurl">
                                    </div>
                                    @error('imageurl')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- Left and right controls -->
                                <div class="row">
                                    <div class="col text-left">
                                        <a class="" href="#demo" data-slide="prev">
                                            <span class="material-icons">west</span>
                                        </a>
                                    </div>
                                    <div class="col text-center">
                                        <input type="hidden" name="old_email" value="{{$user->email}}" />
                                        <button type="submit" class="rounded-pill input-lg btn btn-primary btn-lg">
                                            {{ __('Update') }}
                                        </button>

                                    </div>
                                    <div class="col text-right">
                                        <a class="" href="#demo" data-slide="next">
                                            <span class="material-icons">east</span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>



                    </div>

                    <div class="form-group row">

                    </div>
                </form>
            </div>
        </div>
        </div>
        <div class="col-sm-2">
            <h3>In Departments</h3>
            @foreach ($user_focalareas as $uf)
                <h6 class="bg-dark-gray">{{$uf->focalareaname}}</h6>
            @endforeach<br>
            <h3>In Groups</h3>
            @foreach ($user_clusters as $uf)
                <h6 class="bg-dark-gray">{{$uf->clustername}}</h6>
            @endforeach
        </div>
    </div>


    <div class="row" style="margin-top: 40px;">
        <div class="col-sm-6">
            <h2>Departments</h2>
            <div class="form-group">
                <form method="POST"  action="{{route('remove_user_from_groups')}}">
                    @csrf
                    <div>
                        <input type="hidden" name="userid" value="{{$user->id}}">
                        <input type="hidden" name="focalareascount" value="{{count($user_focalareas)}}">
                        @for ($fa=0; $fa< count($user_focalareas); $fa++)
                        <div class="row">
                            <div class="form-group custom-control custom-switch">
                                <input  id="userfocalarea{{$fa}}" class="custom-control-input" type="checkbox" name="focalarea{{$fa}}"  value="{{$user_focalareas[$fa]->focalareaid}}" />
                                <label for="userfocalarea{{$fa}}" class="custom-control-label">{{ __('Leave '). $user_focalareas[$fa]->focalareaname}}</label>
                            </div>
                        </div>
                        @endfor
                    </div>

                    <div class="form-group">
                        <button class="btn btn-danger btn-lg rounded-pill" type="submit">__{{'Leave Departments'}}</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-sm-6">
            <h2>Groups</h2>
            <div class="form-group">
                <form method="POST" action="{{route('remove_user_from_groups')}}">
                    @csrf
                    <div>
                        <input type="hidden" name="userid" value="{{$user->id}}">
                        <input type="hidden" name="clusterscount" value="{{count($user_clusters)}}">
                        @for ($cl=0; $cl< count($user_clusters); $cl++)
                        <div class="row">
                            <div class="form-group custom-control custom-checkbox">
                                <input  id="usercluster{{$cl}}" class="custom-control-input" type="checkbox" name="cluster{{$cl}}"  value="{{$user_clusters[$cl]->clusterid}}" />
                                <label for="usercluster{{$cl}}" class="custom-control-label">{{ __('Leave '). $user_clusters[$cl]->clustername}}</label>
                            </div>

                        </div>
                        @endfor
                    </div>

                    <div class="form-group">
                        <button class="btn btn-danger btn-lg rounded-pill" type="submit">__{{'Leave Groups'}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    // Material Select Initialization
    $(document).ready(function() {
        $('.mdb-select').materialSelect();
    });
</script>
@endsection
