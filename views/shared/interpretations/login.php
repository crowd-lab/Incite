<?php

//unset($_SESSION['study2']);

include(dirname(__FILE__).'/../common/header.php');



?>

    <div style="margin-top: 20px;" class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Login</h3>
            </div>
            <div style="padding: 15px;">
                <form method="post">
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Username:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control-plaintext" id="username" value="" name="username" style="width:300px;">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Password:</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control-plaintext" id="password" value="" name="password" style="width:300px;">
                        </div>
                    </div>
                    <div class="form-group row">
                        <button type="submit" class="btn btn-primary pull-right" style="margin-right: 60px">Login</button>
                    </div>
                </form>
            </div>
        </div> 
    </div>
