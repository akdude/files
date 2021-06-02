<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="/css/main.css">
</head>
<body>

<nav class="navbar navbar-inverse visible-xs">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="#">Dashboard</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li class="active"><a data-toggle="pill" href="#section1" onclick="analyse('v1vsv2');">File is in v1 but not in v2</a></li>
        <li><a data-toggle="pill" href="#section2" onclick="analyse('v2vsv1');">File is in v2 but not in v1</a></li>
        <li><a data-toggle="pill" href="#section3" onclick="analyse('difference');">Common Files with different content</a></li>
        <li><a data-toggle="pill" href="#section3" onclick="analyse('differenceinfiles');">Differences in the files</a></li>
      </ul>
    </div>
  </div>
</nav>
<div class="container-fluid">
  <div class="row content">
    <div class="col-sm-3 sidenav hidden-xs">
      <h2>Analyse Files</h2>
      <ul class="nav nav-pills nav-stacked">
        <li class="active"><a data-toggle="pill" href="#section1" onclick="analyse('v1vsv2');">File is in v1 but not in v2</a></li>
        <li><a data-toggle="pill" href="#section2" onclick="analyse('v2vsv1');">File is in v2 but not in v1</a></li>
        <li><a data-toggle="pill" href="#section3" onclick="analyse('difference');">Common Files with different content</a></li>
        <li><a data-toggle="pill" href="#section3" onclick="analyse('differenceinfiles');">Differences in the files</a></li>
      </ul><br>
    </div>

    <br>
    <div class="col-sm-9">
        <div class="row" id="selectfile">
            
            <div class="col-sm-4 text-center">
                <div class="form-group row">
                  <div class="col-md-4">
                  <label for="sel1" class="mt-5">Compare File:</label>
                  </div>
                  <div class="col-md-6">
                    <select class="form-control" id="files">
                    
                  </select>
                  </div>
                  
                  
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12" id="part-1">
              <div class="well">
                <p id="result">Some text..</p>
              </div>
            </div>
            
        </div>
    </div>
    
  </div>
</div>

</body>
</html>

<script type="text/javascript">
    function analyse (action, file='') {
        $.ajax({
            url: '/diff/calc',
            type: "GET",
            data: {
                'action' : action,
                'file_name' : file
            },
            success: function (response) {
                if(action == 'differenceinfiles'){
                    $('#selectfile').removeClass('hide');
                    $('#result').html(response.content);
                    if(file == '') {
                        let options = null;
                        $(response.all_files).each(function(index, value){
                            options += '<option value="'+value+'">'+value+'</option>';
                        });
                        $('#files').html(options);
                    }
                    $('.diff-wrapper > thead > tr > th').each(function(){
                      if($(this).text() == 'Old') {
                        $(this).text('v1');
                      }
                      if($(this).text() == 'New') {
                        $(this).text('v2');
                      }
                    });
                } else {
                    $('#result').html(response);
                    $('#selectfile').addClass('hide');
                }
                
            },
            error: function(data) {
                
            }
        });
    }

    $("#files").change(function(e){
        let file = $(this).val();
        analyse('differenceinfiles', file);
    });

    

    analyse('v1vsv2');
</script>