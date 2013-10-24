
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Fixed Top Navbar Example for Bootstrap</title>

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
          <div class="col-md-6">
            
          </div>
      </div>
    </div> <!-- /main-container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    {{ HTML::script('js/jquery.js') }}
    {{ HTML::script('js/bootstrap.min.js') }}
  </body>
</html>
