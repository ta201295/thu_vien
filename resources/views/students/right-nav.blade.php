<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-inverse-collapse">
                <i class="icon-reorder shaded"></i></a>
                <a class="brand" href="{{ URL::route('home') }}" style="color:#fff;">HNC LMS</a>
            <div class="nav-collapse collapse navbar-inverse-collapse">
                <ul class="nav pull-right">
                    <li class="nav-user dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ asset('images/avatar_default.jpeg') }}" class="nav-avatar" />{{ auth('student')->user()->full_name }}
                        <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="#" target="_blank">Sách đã mượn</a></li>
                            <li class="divider"></li>
                            <li><a href="{{ route('book-student.create') }}">Đăng ký mượn sách</a></li>
                            <li class="divider"></li>
                            <li><a href="{{ route('students.logout') }}">Đăng xuất</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
