<?php
  include_once('./js/header.php');
  echo page_head(false,false);
  $ajax = false;
?>
<script type="text/javascript" src="sha512.js"></script>
<script type="text/javascript" src="forms.js"></script>
<div class="span6" style="margin-left:25%">
<form class="form-horizontal" action="process_login.php" method="post" name="login_form">
<fieldset>
   <div class="control-group">
   <label class="control-label" for="email">Email:</label>
     <div class="controls">
        <input type="text" id="email" name="email" />
     </div>
   </div>
   <div class="control-group">
   <label class="control-label" for="password">Password:</label>
     <div class="controls">
        <input type="password" id="password" name="password" />
     </div>
   </div>
   </br>
<?php
if(isset($_GET['error'])) {
   echo '<p align="center" style="color: #B94A48;">Error Logging In!</p>';
}
?>
   <div class="control-group">
        <button type="submit" class="btn btn-primary pull-right">Login</button>
        <!-- <input type="button" value="Login" onclick="formhash(this.form, this.form.password);" /> -->
   </div>
</fieldset>
</form>
</div>
<div class="clearfix"></div>

</body>
</html>

<?php
 echo page_foot($ajax);
?>

