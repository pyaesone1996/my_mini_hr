<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
   
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
 
    <!-- Bootstrap core CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
  
    <!-- Material Design Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/css/mdb.min.css" rel="stylesheet">

    {{--DataTable Links  --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">

    {{-- DateRange Picker --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    {{-- Select2 plugin --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">

    {{-- ViewerCss --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.10.2/viewer.min.css">

    <link rel="stylesheet" href="{{ asset('style.css') }}">
    @yield('extra-css')

</head>
<body>
    <div class="page-wrapper chiller-theme">

        <nav id="sidebar" class="sidebar-wrapper">
            <div class="sidebar-content">
            <div class="sidebar-brand">
                <a href="#">MY HR</a>
                <div id="close-sidebar">
                <i class="fas fa-times"></i>
                </div>
            </div>
            <div class="sidebar-header">
                <div class="user-pic">
                <img class="img-responsive img-rounded" src="{{ auth()->user()->profile() }}"
                    alt="{{ auth()->user()->name,"-profile" }}">
                </div>
                <div class="user-info">
                <span class="user-name">{{ auth()->user()->name }}</span>
                <span class="user-role">
                    {{ auth()->user()->department ? 
                    auth()->user()->department->name :
                    'No Deaprtment' 
                    }}
                </span>
                <span class="user-status">
                    <i class="fa fa-circle"></i>
                    <span>Online</span>
                </span>
                </div>
            </div>
            <!-- sidebar-header  -->
            <div class="sidebar-menu">
                <ul>
                    <li class="header-menu">
                        <span>Menu</span>
                    </li>
                    <li>
                        <a href="/">
                        <i class="fa fa-home"></i>
                        <span>Home</span>
                        </a>
                    </li>
                    @can ('view_company_setting')
                    <li>
                        <a href="{{ route('company_setting.show',1) }}">
                        <i class="fa fa-building"></i>
                        <span>Company Setting</span>
                        </a>
                    </li>
                    @endcan
                    @can ('view_employee')
                    <li>
                        <a href="{{ route('employee.index') }}">
                        <i class="fa fa-users"></i>
                        <span>Employees</span>
                        </a>
                    </li>
                    @endcan
                    @can ('view_salary')
                        <li>
                            <a href="{{ route('salary.index') }}">
                            <i class="fa fa-money-bill"></i>
                            <span>Salary</span>
                            </a>
                        </li>
                    @endcan
                    @can ('view_department')
                    <li>
                        <a href="{{ route('department.index') }}">
                        <i class="fa fa-sitemap"></i>
                        <span>Departments</span>
                        </a>
                    </li>
                    @endcan
                    @can ('view_role')
                        <li>
                            <a href="{{ route('role.index') }}">
                            <i class="fa fa-user-shield"></i>
                            <span>Roles</span>
                            </a>
                        </li>
                    @endcan
                    @can ('view_permission')
                        <li>
                            <a href="{{ route('permission.index') }}">
                            <i class="fa fa-shield-alt"></i>
                            <span>Permissions</span>
                            </a>
                        </li>
                    @endcan
                    @can ('create_project')
                        <li>
                            <a href="{{ route('project.index') }}">
                            <i class="fa fa-briefcase"></i>
                            <span>Projects</span>
                            </a> 
                        </li>
                    @endcan
                    @can ('view_attendance')
                        <li>
                            <a href="{{ route('attendance.index') }}">
                            <i class="fa fa-calendar-alt"></i>
                            <span>Attendance</span>
                            </a>
                        </li>
                    @endcan
                    @can ('view_attendance')
                        <li>
                            <a href="{{ route('attendance.overview') }}">
                            <i class="fa fa-calendar-alt"></i>
                            <span>Attendance (Overview)</span>
                            </a>
                        </li>
                    @endcan
                    @can ('view_payroll')
                        <li>
                            <a href="{{ route('payroll') }}">
                            <i class="fa fa-money-bill"></i>
                            <span>Payroll</span>
                            </a> 
                        </li>
                    @endcan
                    
                    
                </ul>
            </div>
            <!-- sidebar-menu  -->
            </div>
            <!-- sidebar-content  -->
        </nav>

        <div class="main-menu">
            <div class="d-flex justify-content-center">
                <div class="col-md-10">
                    <div class="d-flex justify-content-between align-items-center">
                        @if (request()->is('/'))
                            <a href="#" id="show-sidebar"><i class="fas fa-bars"></i></a>
                        @else
                            <a href="{{ url()->previous() }}"><i class="fas fa-chevron-left"></i></a>
                        @endif
                        <h5 class="mb-0">@yield('title')</h5>
                        <a href=""></a>
                    </div>
                    <a href=""></a>
                </div>
            </div>
        </div>
        <div class="py-4  main-content">
            <div class="d-flex justify-content-center">
                <div class="col-md-10">
                    @yield('content')
                </div>
            </div>
        </div>
        <div class="bottom-menu">
            <div class="d-flex justify-content-center">
                <div class="col-md-10">
                    <div class="d-flex justify-content-between">
                       <a href="{{ route('home') }}">
                           <i class="fa fa-home"></i>
                           <p class="mb-0">Home</p>
                        </a>
                        <a href="{{ route('attendance-scan') }}">
                            <i class="fa fa-user-clock"></i>
                            <p class="mb-0">Attendance</p>
                        </a>
                        <a href="{{ route('my-project.index') }}">
                            <i class="fa fa-tasks"></i>
                            <p class="mb-0">My Project</p>
                        </a>
                        <a href="{{ route('profiles.profile') }}">
                            <i class="fa fa-user"></i>
                            <p class="mb-0">Profile</p>
                       </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
    <!-- JQuery -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
   
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js"></script>
   
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/js/mdb.min.js"></script>

    {{-- Datatables --}}

    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

     {{-- DataTableHeightlight Mark --}}
    <script src="https://cdn.jsdelivr.net/g/mark.js(jquery.mark.min.js)"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.13/features/mark.js/datatables.mark.js"></script>
    

    {{-- DateRange Picker --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    {{-- jsvalidator  --}}
    <script type="text/javascript" src="{{ url('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    
    {{-- sweet alert 2  --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

     {{-- sweet alert 1  --}}
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

     {{-- Select2 plugin --}}
     <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
     {{-- larapass --}}
    <script src="{{ asset('vendor/larapass/js/larapass.js') }}"></script>

    {{-- ViewerJS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.10.2/viewer.min.js"></script>

    {{-- SortAble --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

   
    
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });

        $(function ($) {

            let token = document.head.querySelector('meta[name="csrf-token"]');
            if(token){
                $.ajaxSetup({
                    headers: { 'X-CSRF-TOKEN': token.content }
                });
            }else{
                console.log('csrf-not-found');
            }


            $('.select-aries').select2({
                placeholder: 'Please Choose',
                allowClear: true,
                theme: "bootstrap4"
            });

            $(".sidebar-dropdown > a").click(function() {
            $(".sidebar-submenu").slideUp(200);
            if ($(this).parent().hasClass("active")) 
            {
                $(".sidebar-dropdown").removeClass("active");
                $(this).parent().removeClass("active");
            } else 
            {
                $(".sidebar-dropdown").removeClass("active");
                $(this).next(".sidebar-submenu").slideDown(200);
                $(this).parent().addClass("active");
            }
            });

            $("#close-sidebar").click(function(e) {
                e.preventDefault();
                $(".page-wrapper").removeClass("toggled");
            });
            $("#show-sidebar").click(function(e) {
                e.preventDefault();
                $(".page-wrapper").addClass("toggled");
            });

            @if (request()->is('/'))
                document.addEventListener('click',function(event){
                    if(document.getElementById('show-sidebar').contains(event.target)){
                        $(".page-wrapper").addClass("toggled");
                    }else if(!document.getElementById('sidebar').contains(event.target)){
                        $(".page-wrapper").removeClass("toggled");
                    }
                })
            @endif

            
            @if(session('create'))
            Swal.fire({
                title: 'Successfully Created!',
                text: "{{ session('create') }}",
                icon: 'success',
                confirmButtonText:'Continue'
            }).then((success)=>{
               location.reload();
            });
            @elseif (session('update'))
                Swal.fire({
                title: 'Successfully Updated!',
                text: "{{ session('update') }}",
                icon: 'success',
                confirmButtonText:'Continue'
            }).then((success)=>{
               location.reload();
            });
            @endif
               
    
            $.extend(true, $.fn.dataTable.defaults, {
                responsive: true,
                processing: true,
                serverSide: true,
                mark: true,
                language:{
                    "paginate": {
                        "next": "<i class='far fa-arrow-alt-circle-right'></i>",
                        "previous": "<i class='far fa-arrow-alt-circle-left'></i>"
                    },
                    "processing": ".....loading....."
                },columnDefs:[
                    {
                        "targets" : [0],
                        "class" : "control",
                    },
                    {
                        "targets" : 'no-sort',
                        "orderable" : false,
                    },
                    {
                        "targets" : 'no-seach',
                        "searchable" : false,
                    },
                    {
                        "targets" : 'hidden',
                        "visible" : false,
                    }
                ]
            });



        });

    </script>

    @yield('extra-js')
</body>
</html>
