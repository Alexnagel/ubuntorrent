
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
    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
          <div class="container">
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target="#nav-collapse-01"></button>
            <a href="{{ URL::to('/') }}" class="navbar-brand">Ubuntorrent</a>

            <div class="navbar-collapse collapse in" id="nav-collapse-01">
              <ul class="nav">
                <!-- Menu items go here -->
                <li>
                  <a href="{{ URL::to('/') }}">Home</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
    </div>
    <div class="container main-container">
      <div class="row">
          <div class="col-md-12">
            <div class="well">
              <h4>Search:</h4>
              <p>
                Search for a tv series and choose episodes to be downloaded.
              </p>
              <form action="/search" method="post">
                <div class="form-group">
                  <div class="input-group input-group-hg">
                    <input class="form-control" name="search_term" type="search" placeholder="Search">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button"><span class="fui-search"></span></button>
                    </span>            
                  </div>
                </div>
              </form>
            </div>
          </div>
      </div>
       @if(isset($results))
      <div class="row">
        <div class="col-md-12">
          <div class="well">
            <h4>Search Results</h4>
            <table class="table table-striped table-hover search-results">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Date</th>
                  <th>Seeders</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>

                @foreach($results as $result)
                  <tr>
                    <td><a href="{{ route('show', array('name' => $result['name']))}}">{{ $result['name'] }}</a> - {{ $result['season'] }}@if($result['episode'] != "none"), {{ $result['episode'] }}
                    @endif
                    </td>
                    <td>{{ $result['date'] }}</td>
                    <td>{{ $result['seeders'] }}</td>
                    <td><a class="btn btn-primary btn-xs" href="{{$result['magnet']}}">Add to downloads</a></td>
                  </tr> 
                @endforeach 

              </tbody>
            </table>
          </div>
        </div>
      </div>
      @endif
    </div> <!-- /main-container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    {{ HTML::script('js/jquery-2.0.3.min.js') }}
    {{ HTML::script('js/bootstrap.min.js') }}
    {{ HTML::script('js/application.js') }}
    {{ HTML::script('js/jquery.dataTables.min.js') }}
    {{ HTML::script('js/bootstrap.paging.js') }}
    <script type="text/javascript">
      $(document).ready(function(){
         $('.search-results').dataTable( {
              "sDom": "<'row'<'span8'l><'span8'f>r>t<'row'<'span8'i><'span8'p>>",
              "sPaginationType": "bootstrap",
              "aaSorting": [[ 2, "desc" ]],
          } );
      });
    </script>
  </body>
</html>
