<?php defined( 'ABSPATH' ) or exit; ?>
<div class="ohv-block">
  <div class="ohv-form-wrapper">
    <form class="ohv-form" action="<?php echo ohv_get_url('login'); ?>" method="post" id="ohv-form-login">

      <div class="ohv-alert ohv-display-none"><span></span></div>

      <h3 class="ohv-form-heading"><?php esc_html_e( 'Login form', 'openhousevideo' ); ?></h3>
      <div class="ohv-form-group">
        <label for="ohv-email"><?php esc_html_e( 'Email', 'openhousevideo' ); ?>:</label>
        <input type="text" class="ohv-form-control" name="ohv-email" id="ohv-email" placeholder="Enter email" autocomplete="off">
      </div>
      <div class="ohv-form-group">
        <label for="ohv-password"><?php esc_html_e( 'Password', 'openhousevideo' ); ?>:</label>
        <input type="password" class="ohv-form-control" name="ohv-password" id="ohv-password" placeholder="Enter password" autocomplete="off">
      </div>
      <div class="ohv-form-action">
        <button type="submit" class="ohv-btn ohv-btn-primary" name="button"><?php esc_html_e( 'OK', 'openhousevideo' ); ?></button>
      </div>
    </form><!-- /#ohv-form-login  -->
  </div>
</div><!-- /.ohv-block -->
