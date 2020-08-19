<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!--for md bootstrap-->
    <!--end of md js-->

    <!-- Scripts -->
    <!--bootstrap 4 scripts added at the bottom of page-->
    <!--<script src="{{ asset('js/app.js') }}" defer></script>-->
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .vertical-nav {

            min-width:17rem;

            width:17rem;

            height:100vh;

            position:fixed;

            top:0;

            left:0;

            box-shadow:3px 3px 10px rgba(0,0,0,0.1);

            transition:all 0.4s;

        }



        .page-content {

            width: calc(100% -17rem);

            margin-left:17rem;

            transition:all 0.4s;

        }



        /* for toggle behavior */



        #sidebar.active {

            margin-left:-17rem;

        }



        #content.active {

            width:100%;

            margin:0;

        }



        @media (max-width:768px) {

            #sidebar {

                margin-left:-17rem;

            }

            #sidebar.active {

                margin-left:0;

            }

            #content {

                width:100%;

                margin:0;

            }

            #content.active {

                margin-left:17rem;
                42
                width: calc(100% -17rem);

            }

        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css" />
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <!--tinymce-->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

</head>
<body>
<div class="vertical-nav bg-dark" id="sidebar">

    <div class="py-4 px-3 mb-4 bg-dark">

        <div class="media-body">

            <h4 class="font-weight-white text-muted mb-0">Verical Navbar</h4>

        </div>

    </div>



    <p class="text-white font-weight-bold text-uppercase px-3 small pb-4 mb-0">Main</p>

    <ul class="nav flex-column bg-dark mb-0">

        <li class="nav-item">

            <a href="#" class="nav-link text-light font-italic bg-dark">

                dashboard

            </a>

        </li>

        <li class="nav-item">

            <a href="{{route('advertpanel')}}" class="nav-link text-light font-italic">

                Adverts

            </a>

        </li>

        <li class="nav-item">

            <a href="{{route('articlepanel')}}" class="nav-link text-light font-italic">

                Articles

            </a>

        </li>

        <li class="nav-item">

            <a href="{{route('clusterpanel')}}" class="nav-link text-light font-italic">

                Clusters

            </a>

        </li>

        <li class="nav-item">

            <a href="{{route('focalareapanel')}}" class="nav-link text-light font-italic">

                Departments

            </a>

        </li>

        <li class="nav-item">

            <a href="{{route('donationpanel')}}" class="nav-link text-light font-italic">

                Donations

            </a>

        </li>

        <li class="nav-item">

            <a href="{{route('eventpanel')}}" class="nav-link text-light font-italic">

                Events

            </a>

        </li>

        <li class="nav-item">

            <a href="{{route('facilitypanel')}}" class="nav-link text-light font-italic">

                Facilities

            </a>

        </li>

        <li class="nav-item">

            <a href="{{route('gallerypanel')}}" class="nav-link text-light font-italic">

                Gallery

            </a>

        </li>

        <li class="nav-item">

            <a href="{{route('organizationpanel')}}" class="nav-link text-light font-italic">

                Organizations

            </a>

        </li>

        <li class="nav-item">

            <a href="{{route('userspanel')}}" class="nav-link text-light font-italic">

                Users

            </a>

        </li>

    </ul>



</div>

<!--page-content-->
<div class="page-content p-5" id="content">



    <!-- Toggle button -->

    <button id="sidebarCollapse" type="button" class="btn btn-dark bg-dark rounded-pill shadow-sm px-4 mb-4">

        <small class="text-uppercase font-weight-bold">Toggle</small>

    </button>



    <!-- Page content -->

    @yield('content')




</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<!--script for the side bar menu-->
<script type="text/javascript">
    $(function() {

        $('#sidebarCollapse').on('click',function() {

            $('#sidebar, #content').toggleClass('active');

        });

    });
</script>


<!--end script for the side bar menu-->
<!--script for popover-->

<script>
    $(document).ready(function(){
        $('[data-toggle="popover"]').popover();
    });
</script
    <!--end script for popover-->
</body>
