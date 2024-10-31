<?php defined( 'ABSPATH' ) or exit; ?>
<div id="ohv-plugin-container">
  <div class="ohv-container">

    <div class="ohv-alert ohv-display-none" id="ohv-version-message" style="text-align: center;"><span></span></div>
    <div class="ohv-alert ohv-display-none" id="ohv-notify-message" style="text-align: center;"><span></span></div>

    <div class="ohv-header">
      <div class="ohv-logo-wrapper">
        <a href="<?php echo ohv_get_url('home'); ?>" target="_blank">
          <img class="ohv-logo" src="<?php echo esc_url( plugins_url( '../assets/images/logo.png', __FILE__ ) ); ?>" alt="OpenHouseVideo" />
        </a>
      </div>
    </div><!-- /.ohv-header -->

    <div class="ohv-body">
      <?php
        if (ohv_get_token()) {
          $this->the_template('templates/partials/_welcome');
        } else {
          $this->the_template('templates/partials/_login');
        }
      ?>
    </div>

  </div>
</div><!-- /#ohv-plugin-container -->
