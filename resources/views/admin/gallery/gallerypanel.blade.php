@extends('layouts.admin')
@section('content')

<div class="row">

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

        <div class="row files">

            <h3 class="text-capitalize">Files uploaded </h3>
            @if(!empty($Admingalleryfiles))
            <div class="row">
                <div class="row">
                    <h2 class="header">Gallery Files </h2>
                    <div>{{$Admingalleryfiles->links()}}</div>
                </div>
                <div class="row">
                    @foreach ($Admingalleryfiles as $af)
                    <div class="col-sm-3">

                        <div>
                            <button class="btn bg-transparent" title="Description" data-content="{{$af->description}} <br> by <img src='{{asset($af->userimageurl)}}' style='width:80x; height: auto;' /> <small>{{$af->username}}</small>" data-placement="auto" data-toggle="popover" data-trigger="focus" data-html="true" role="popover-toggle"><span class="material-icons">more_vert</span> </button>
                        </div>
                        @if (preg_match('/^image*/i', $af->filetype))
                        <img src="{{asset($af->fileurl)}}" class="img-fluid" />
                        @else
                        <button class="btn btn-block bg-transparent">{{$af->description}}</button>
                        <form action="" method="post" class="">
                            @csrf
                            <input type="hidden" name="fileid" value="{{$af->id}}">
                            <button type="submit" class="btn bg-transparent"><span class="material-icons">download</span> download</button>
                        </form>
                        @endif

                        <form action="{{route('delete_gallery_file')}}" method="post"  class="">
                            @csrf
                            <input type="hidden" name="fileid" value="{{$af->id}}">
                            <input type="hidden" name="fileurl" value="{{$af->fileurl}}">
                            <button type="submit" class="btn bg-transparent"><span class="material-icons">delete</span> delete</button>

                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <form method="post" action="{{route('create_gallery')}}" enctype="multipart/form-data">
                @csrf
                <h3 class="text-capitalize">You can add your interesting files to the Gallery</h3>
                <div class="row">
                    <div class="row">
                        <div class="col-sm-12"><div class="imgPreview"></div> </div>
                    </div>
                    @for ($i=0; $i<5; $i++)

                    <div class="form-group">
                        <label for="filedesc">Description</label>
                        <input type="text" name="galleryfiles{{$i}}label" placeholder="Description of files" class="w-100 form-control form-control-lg" @error('galleryfiles{{$i}}label') id-valid @enderror />

                        @error('galleryfiles{{$i}}label')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror

                        <div class="custom-file">
                            <label for="images" class="custom-file-label">{{ __('Add Files ')}}</label>
                            <input type="file" name="galleryfiles{{$i}}[]" class="images custom-file-input" id="images" multiple="multiple">
                        </div>
                    </div>

                    @endfor
                </div>
                <div class="row form-group">
                    <button type="submit" class="btn btn-lg btn-block btn-primary">Upload Files To Gallery</button>
                </div>
            </form>

        </div>

    </div>
</div>



<script type="text/javascript">


    $(document).ready(function() {

        $(function() {
            // Multiple images preview with JavaScript
            var multiImgPreview = function(input, imgPreviewPlaceholder) {

                if (input.files) {
                    var filesAmount = input.files.length;

                    for (i = 0; i < filesAmount; i++) {
                        var reader = new FileReader();

                        reader.onload = function(event) {
                            //create ie parseHTML img element within jquery selector $(''), add srt attr,
                            //and append to the named element
                            $($.parseHTML('<img src="" class="img-fluid w-25">')).attr('src', event.target.result).appendTo(imgPreviewPlaceholder);
                        }

                        reader.readAsDataURL(input.files[i]);
                    }
                }

            };


            $('.images').on('change', function() {
                multiImgPreview(this, 'div.imgPreview');
            });

        });



        // Material Select Initialization
        $('.mdb-select').materialSelect();
    });
</script>
@endsection