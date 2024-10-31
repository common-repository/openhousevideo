<?php defined( 'ABSPATH' ) or exit; ?>
<div class="ohv-popup-wrapper" style="display:none">
  <div id="ohv-popup" class="ohv-popup">
    <div class="ohv-popup-header">
      <h3 class="ohv-popup-heading"><?php esc_html_e('OHV Listings', 'openhousevideo'); ?></h3>
    </div>

    <div class="ohv-popup-body">
      <div class="ohv-alert ohv-display-none"><span></span></div>

      <?php if (isset($data['token']) && !empty($data['token'])): ?>
        <?php ohv_the_template('templates/partials/_listing', ['statuses' => $data['statuses']]); ?>

        <div class="ohv-logout-block" style="display: none;">
          <p><button class="ohv-btn ohv-btn-default" data-toggle="ohv-logout"><?php esc_html_e( 'Logout', 'openhousevideo' ); ?></button></p>
        </div>

        <div class="ohv-popup-footer">
          <div class="ohv-popup-version">
            <select name="ohv-listing-version">
              <option value="standard"><?php esc_html_e( 'Standard', 'openhousevideo' ); ?></option>
              <option value="mls"><?php esc_html_e( 'MLS', 'openhousevideo' ); ?></option>
            </select> <span><?php esc_html_e( 'version', 'openhousevideo' ); ?></span>
          </div>


          <button type="button" class="button button-large" name="ohv-live-preview" data-toggle="ohv-live-preview" disabled><i class="fa fa-eye"></i> <?php esc_html_e('Live preview', 'openhousevideo'); ?></button>
          <button type="button" class="button button-primary button-large" name="ohv-insert-shortcode" data-toggle="ohv-insert-shortcode" disabled><i class="fa fa-check"></i> <?php esc_html_e('Insert shortcode', 'openhousevideo'); ?></button>
        </div>
      <?php else: ?>
        <?php ohv_the_template('templates/partials/_login'); ?>
      <?php endif; ?>
    </div>
  </div>
</div>
