<div class="col-md-6 content">
     <?php echo tep_draw_form('login', tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL'), 'post', '', true); ?>
	 <h2 class="form-signin-heading">Please sign in</h2>
	 <div class="input-group input-group-lg">
	 <span class="input-group-addon glyphicon glyphicon-envelope"></span><?php echo tep_draw_input_field('email_address','','type="email" class="form-control x-large" placeholder="Email address" required autofocus'); ?>
	 </div>
	  <div class="input-group input-group-lg">
	   <span class="input-group-addon glyphicon glyphicon-lock"></span> <?php echo tep_draw_password_field('password','','class="form-control" placeholder="Password" required'); ?>
	 </div>
    <p><?php echo '<a href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '">' . MODULE_CONTENT_LOGIN_TEXT_PASSWORD_FORGOTTEN . '</a>'; ?></p>

    <p align="right"><?php echo tep_draw_button_booth(IMAGE_BUTTON_LOGIN, 'log-in', null, 'primary'); ?></p>

    </form>
</div>


<form class="form-signin" role="form">
