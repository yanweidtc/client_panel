<?php
  include_once('./js/header.php');
include 'db_connect.php';
include 'functions.php';

  sec_session_start($mysqli,false);

  echo page_head(false,false);
  $ajax = false;
?>
<script type="text/javascript" src="sha512.js"></script>
<script type="text/javascript" src="forms.js"></script>
<div class="col-md-4 col-md-offset-4">
<form class="form-horizontal" action="process_cli_login.php" method="post" name="login_form">
<fieldset>
   <div class="form-group">
   <label for="username">Username:</label>
     <div class="controls">
     	<input type="text" class="form-control" id="username" name="username" />
	<span class="help-block">The username you were provided when registering.</span>
     </div>
   </div>
   <div class="form-group">
   <label for="p">Password:</label>
     <div class="controls">
        <input class="form-control" type="password" id="p" name="p" />
     </div>
   </div>
   <br>
<?php
if(isset($_GET['error'])) { 
   echo '<p align="center" style="color: #B94A48;">Error Logging In!</p>';
}
?>   
   <div class="control-group">
        <!--<button type="submit" class="btn btn-primary pull-right" onclick="formhash(this.form, this.form.password);">Login</button>-->
        <button type="submit" class="btn btn-primary pull-right">Login</button>
   	<!-- <input type="button" value="Login" onclick="formhash(this.form, this.form.password);" /> -->
   </div>
</fieldset>
</form>
</div>
<div class="clearfix"></div>


<?php
 echo page_foot($ajax);
?>
