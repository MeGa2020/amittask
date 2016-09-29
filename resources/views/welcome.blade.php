<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>AMIT | Welcome Page</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">


    </head>
    <body style="background-color:#e9ebee;">
        <nav class="navbar navbar-inverse">
          <div class="container-fluid">
            <div class="navbar-header">
              <a class="navbar-brand" href="#">AMIT TASK</a>
            </div>
            <ul class="nav navbar-nav">
              <li class="active"><a href="retapi">Get Data From API</a></li>
              <li data-toggle="modal" data-target="#myModal"><a href="#">Add new Item</a></li>
              <!-- Modal -->
             <div class="modal fade" id="myModal" role="dialog">
               <form role="form" method="post" action="insert">
                 <input type="hidden" name="_token" value="{{ csrf_token()}}">
                 <div class="modal-dialog">

                   <!-- Modal content-->
                   <div class="modal-content">
                     <div class="modal-header">
                       <button type="button" class="close" data-dismiss="modal">&times;</button>
                       <h4 class="modal-title">ADD new Item Detail</h4>
                     </div>
                     <div class="modal-body">
                       <div class="form-group">
                         <input class="form-control " type="text" placeholder="Username" name="username">
                       </div>
                       <div class="form-group">
                         <input class="form-control " type="text" placeholder="Value" name="value">
                       </div>
                       <div class="form-group">
                         <textarea rows="2" class="form-control" placeholder="Message" name="message"></textarea>
                       </div>
                     </div>
                     <div class="modal-footer">
                       <input type="submit" class="btn btn-info" value="Add">
                       <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                     </div>
                   </div>
                 </div>
               </form>
             </div>
            </ul>
          </div>
        </nav>
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
                <div class="panel panel-primary">
                    <div class="panel-heading">Data get From Couchbase Server</div>
                    <div class="panel-body">
                          <table class="table table-hover">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>ID</th>
                                <th>message</th>
                                <th>Value</th>
                                <th>username</th>
                                <th>Edit</th>
                                <th>Delete</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                                if(!empty($getdata))
                                {
                                  for($i=0;$i<count($getdata);$i++)
                                  {
                                    ?>
                                      <tr>
                                        <td><?php echo ($i+1);?></td>
                                        <td><?php echo $getdata[$i]->documentId;?></td>
                                        <td><?php echo $getdata[$i]->message;?></td>
                                        <td><?php echo $getdata[$i]->value;?></td>
                                        <td><?php echo $getdata[$i]->username;?></td>
                                        <td>
                                          <p data-placement="top" data-toggle="tooltip" title="Edit">
                                            <button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#editrow<?php echo ($i+1);?>" >
                                              <span class="glyphicon glyphicon-pencil"></span>
                                            </button>
                                          </p>
                                          <!--Edit Model -->
                                          <div class="modal fade" id="editrow<?php echo ($i+1);?>" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
                                            <form role="form" method="post" action="update?arrow=<?php echo $getdata[$i]->documentId;?>">
                                              <input type="hidden" name="_token" value="{{ csrf_token()}}">
                                              <div class="modal-dialog">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
                                                    <h4 class="modal-title custom_align" id="Heading">Edit Your Detail</h4>
                                                  </div>
                                                  <div class="modal-body">
                                                    <div class="form-group">
                                                      <input class="form-control " type="text" placeholder="Username" name="editusername" value="<?php echo $getdata[$i]->username;?>">
                                                    </div>
                                                    <div class="form-group">
                                                      <input class="form-control " type="text" placeholder="Value" name="editvalue" value="<?php echo $getdata[$i]->value;?>">
                                                    </div>
                                                    <div class="form-group">
                                                      <textarea rows="2" class="form-control" placeholder="Message" name="editmessage"><?php echo $getdata[$i]->message;?></textarea>
                                                    </div>
                                                  </div>
                                                  <div class="modal-footer ">
                                                    <span class="glyphicon glyphicon-ok-sign"></span> <input type="submit" class="btn btn-warning" value="update" >
                                                    <span class="glyphicon glyphicon-remove"></span> <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                                  </div>
                                                </div>
                                                <!-- /.modal-content -->
                                              </div>
                                              <!-- /.modal-dialog -->
                                            </form>

                                          </div>
                                        </td>
                                        <td>
                                          <button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#deleterow<?php echo ($i+1);?>" >
                                            <span class="glyphicon glyphicon-trash"></span>
                                          </button>
                                          <!--delete Model -->
                                          <div class="modal fade" id="deleterow<?php echo ($i+1);?>" role="dialog">

                                            <form role="form" method="post" action="delete?arrow=<?php echo $getdata[$i]->documentId;?>">
                                              <input type="hidden" name="_token" value="{{ csrf_token()}}">
                                              <div class="modal-dialog">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
                                                    <h4 class="modal-title custom_align" id="Heading">Delete this entry</h4>
                                                  </div>
                                                  <div class="modal-body">
                                                    <div class="alert alert-danger"><span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to delete this Record <?php echo $getdata[$i]->documentId;?>? </div>
                                                  </div>
                                                  <div class="modal-footer ">
                                                    <span class='glyphicon glyphicon-ok-sign'></span><input type="submit" class="btn btn-danger" value="YES" >
                                                    <span class="glyphicon glyphicon-remove"></span> <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                                  </div>
                                                </div>
                                                <!-- /.modal-content -->
                                              </div>
                                              <!-- /.modal-dialog -->
                                            </form>
                                          </div>
                                        </td>
                                      </tr>
                                    <?php
                                  }
                                }
                                ?>
                            </tbody>
                          </table>






                    </div>
                </div>
            </div>
            <div class="col-sm-2"></div>
        </div>

    </body>
</html>
