@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">

            <div class="form-group">
                <form method="POST" action="{{ route('adminreg') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group form-row">

                        <div class="form-group col">
                            <label for="name" class="col-form-label text-md-right">{{ __('Name') }}</label>
                            <input id="name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                            @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="form-group col offset-2">
                            <label for="email" class="col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
                            <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

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
                            <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
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
                            <input id="mobile" type="number" class="form-control form-control-lg @error('mobile') is-invalid @enderror" name="mobile" required>
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


                    <div class="form-row">
                        <div class="form-group col">
                            <label for="roleid" class="col-form-label text-md-right">{{ __('Role') }}</label>
                            <select id="roleid" class="form-control form-control-lg @error('roleid') is-invalid @enderror" name="roleid" required>
                                <option value="">Please Select role</option>
                                @foreach ($roles as $role)
                                    <option value="{{$role->id}}" @if (old('roleid') == $role->id) selected @endif>{{$role->name}}</option>
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
                            <textarea id="rolenote" class="input-lg form-control form-control-lg @error('rolenote') is-invalid @enderror" name="rolenote" required> {{old('rolenote','okay')}}</textarea>
                            @error('rolenote')
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                 </span>
                            @enderror
                        </div>
                    </div>


                    <div class="row">
                        <div class="form-group">
                            <input type="hidden" class="form-control" name="focalareascount" value="{{count($focalareas)}}">
                        </div>
                        @for ($fa=0; $fa< count($focalareas); $fa++)
                        <div class="row">
                            @if (old('focalareaid')[$fa] == $focalareas[$fa])
                            <div class="form-group custom-control custom-switch">
                                <input  id="focalarea{{$fa}}" class="custom-control-input" type="checkbox" name="focalareaid{{$fa}}"  value="{{$focalareas[$fa]->id}}" checked />
                                <label for="focalarea{{$fa}}" class="custom-control-label">{{ __('Leave '). $focalareas[$fa]->name}}</label>
                            </div>

                            @else

                            <div class="form-group custom-control custom-switch">
                                <input  id="focalarea{{$fa}}" class="custom-control-input" type="checkbox" name="focalarea{{$fa}}" value="{{$focalareas[$fa]->id}}"/>
                                <label for="focalarea{{$fa}}" class="custom-control-label">{{ __('Join '). $focalareas[$fa]->name}}</label>
                            </div>
                            @endif
                        </div>
                        @endfor
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label for="positionid" class="form-label text-md-right">{{ __('Position') }}</label>
                            <select id="positionid" class="form-control form-control-lg @error('positionid') is-invalid @enderror" name="positionid" required>
                                <option value="">Please Select Position</option>
                                @foreach ($positions as $p)
                                    <option value="{{$p->id }}" @if (old('positionid') == $p->id) selected @endif>{{$p->name }}</option>
                                @endforeach
                            </select>
                            @error('positionid')
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                 </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="hidden" class="form-control" name="clusterscount" value="{{count($clusters)}}">
                    </div>
                    @for ($cl=0; $cl< count($clusters); $cl++)
                    <div class="row">
                        @if (old('clusterid')[$cl] == $clusters[$cl])
                        <div class="form-group custom-control custom-checkbox">
                            <input  id="cluster{{$cl}}" class="custom-control-input" type="checkbox" name="cluster{{$cl}}"  value="{{$clusters[$cl]->id}}" checked />
                            <label for="cluster{{$cl}}" class="custom-control-label">{{ __('Leave '). $clusters[$cl]->name}}</label>
                        </div>

                        @else

                        <div class="form-group custom-control custom-switch">
                            <input  id="cluster{{$cl}}" class="custom-control-input" type="checkbox" name="cluster{{$cl}}"  value="{{$clusters[$cl]->id}}" />
                            <label for="cluster{{$cl}}" class="custom-control-label">{{ __('Join '). $clusters[$cl]->name}}</label>
                        </div>
                        @endif
                    </div>
                    @endfor

                    <div class="row">
                        <div class="form-group">
                            <label for="about" class="form-label">{{__('Bio Brief')}}</label>
                            <textarea name="about" id="about" class="form-control form-control-lg @error('about') is-invalid @enderror"></textarea>
                        </div>
                        @error('about')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label for="imageurl">{{ __('Upload Profile Picture')}}</label>
                            <input type="file" name="imageurl" class="form-control-file @error('imageurl') is-invalid @enderror " id="imageurl">
                        </div>
                        @error('imageurl')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group row">
                        <div class="">
                            <button type="submit" class="rounded-pill input-lg btn btn-primary btn-lg btn-block">
                                {{ __('Register') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-sm-2"></div>
    </div>
</div>
<script type="text/javascript">
    // Material Select Initialization
    $(document).ready(function() {
        $('.mdb-select').materialSelect();
    });
</script>
@endsection
