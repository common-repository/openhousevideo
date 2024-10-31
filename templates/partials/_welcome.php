<?php defined( 'ABSPATH' ) or exit; ?>
<div id="ohv-user-block" class="ohv-block ohv-loading">

  <div class="ohv-alert ohv-display-none"><span></span></div>

  <div class="ohv-user-inner-block"></div>

  <div class="ohv-logout-block">
    <p><button class="ohv-btn ohv-btn-default" data-toggle="ohv-logout"><?php esc_html_e( 'Logout', 'openhousevideo' ); ?></button></p>
  </div>

  <div class="ohv-user-tpl" style="display: none;">
    <h3><?php esc_html_e( 'Hi!', 'openhousevideo' ); ?> <a href="<?php echo ohv_get_url('profile'); ?>" target="_blank">#{first_name} #{last_name}</a> <span class="ohv-badge ohv-badge-#{user_role}">#{user_role}</span></h3>
    <div class="ohv-user-pic"><span style="background-image: url(#{pic});"></span></div>
    <div class="ohv-user-icon"><i class="fa fa-user-circle"></i></div>
    <p><code>#{user_name}</code></p>
    <p><?php esc_html_e( 'Subscription', 'openhousevideo' ); ?>: #{subscription} - #{price}</p>
  </div>
</div><!-- /.ohv-block -->
