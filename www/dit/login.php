<?php
  include_once('./js/header.php');
  echo page_head(false,false);
  $ajax = false;
?>
<script type="text/javascript" src="sha512.js"></script>
<script type="text/javascript" src="forms.js"></script>
    <div class="">

      <form class="form-signin" role="form" action="process_login.php" method="post" name="login_form">
<!--	<h3 class="form-signin-heading">Please sign in</h3>-->
        <input type="email" id="email" name="email" class="form-control" placeholder="Email address" required autofocus>
        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
      </form>
<?php
if(isset($_GET['error'])) {
   echo '<p align="center" style="color: #B94A48;">Error Logging In!</p>';
}
?>

    </div>
<!--
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
   <div class="control-group">
        <button type="submit" class="btn btn-primary pull-right">Login</button>
   </div>
</fieldset>
</form>
</div>-->
<div class="clearfix"></div>

</body>
</html>

<?php
 echo page_foot($ajax);
?>

