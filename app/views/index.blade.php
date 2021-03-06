
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Ubuntorrent</title>

    <!-- Bootstrap core CSS -->
    {{ HTML::style("bootstrap/css/bootstrap.css") }}
    {{ HTML::style("bootstrap/css/prettify.css") }}



    {{ HTML::style("css/flat-ui.css") }}

    <!-- Custom styles for this template -->
    {{ HTML::style("css/main.css") }}

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
    <!-- Fixed navbar -->
    <div class="navbar navbar-default navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
          <div class="container">
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target="#nav-collapse-01"></button>
            <a href="{{ URL::to('/') }}" class="navbar-brand">Ubuntorrent</a>

            <div class="nav-collapse collapse in" id="nav-collapse-01">
              <ul class="nav">
                <!-- Menu items go here -->
                <li>
                </li>
              </ul>
            </div>
          </div>
        </div>
    </div>
    <div class="container main-container">
      <div class="row">
        @include('modules.schedule_module', array('schedule'=>$schedule))
        @yield('schedule_module')
          <div class="col-md-8">
            <div class="well">
              <h4>Search:</h4>
              <p>
                Search for tv series and choose episodes to be downloaded.
              </p>
              <form action="search" method="post">
                <div class="form-group">
                  <div class="input-group input-group-hg">
                    <input class="form-control" name="search_term" type="search" placeholder="Search">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="submit"><span class="fui-search"></span></button>
                    </span>            
                  </div>
                </div>
              </form>
              </div>
          </div>
          @include('modules.recently_added_module', array('recently_added'=>$recently_added))
          @yield('recently_added_module')
      </div>
    </div> <!-- /main-container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    {{ HTML::script('js/jquery-2.0.3.min.js') }}
    {{ HTML::script('js/bootstrap.min.js') }}
    {{ HTML::script('js/application.js') }}
  </body>
</html>
