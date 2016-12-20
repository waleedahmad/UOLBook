<nav class="navbar navbar-default">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">UOLBook</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

            <ul class="nav navbar-nav navbar-right">
                @if(Auth::check())

                    @if(Auth::user()->type === 'teacher')
                        <li>
                            <a href="/teacher/addClass">Create Class</a>
                        </li>
                    @endif
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{Auth::user()->first_name .' ' .Auth::user()->last_name}} <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        @if(Auth::user()->type != 'teacher' && Auth::user()->type != 'admin')
                            <li><a href="/profile/{{Auth::user()->id}}">Profile</a></li>
                        @endif
                        <li><a href="/user/settings">Setting</a></li>
                        <li>
                            <a href="/logout">Logout</a>
                        </li>
                    </ul>
                </li>
                @else
                    <li><a href="/register">Register</a></li>
                    <li><a href="/login">Login</a></li>
                @endif
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>