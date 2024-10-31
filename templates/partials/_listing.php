<?php defined( 'ABSPATH' ) or exit; ?>
<div id="ohv-listing" class="ohv-listing">

  <div id="ohv-breadcrumbs" class="ohv-breadcrumbs">
    <a href="javascript:;" data-toggle="ohv-listings-home"><?php esc_html_e('All listings', 'openhousevideo'); ?></a> <i class="fa fa-angle-right"></i> <span><?php esc_html_e('Preview', 'openhousevideo'); ?></span>
  </div><!-- /#ohv-breadcrumbs -->

  <div id="ohv-listing-toolbar" class="ohv-listing-toolbar ohv-clearfix">
    <div class="ohv-listing-search-with-order ohv-clearfix">
      <div class="ohv-listing-search">
        <input type="text" class="ohv-form-control" name="ohv-listing-search" data-toggle="ohv-listing-search" placeholder="<?php esc_html_e('Search', 'openhousevideo'); ?>">
      </div>
      <div class="ohv-listing-order">
        <select class="ohv-form-control" name="ohv-listing-order" data-toggle="ohv-listing-order">
          <option value="1"><?php esc_html_e('Newest', 'openhousevideo'); ?></option>
          <option value="2"><?php esc_html_e('Oldest', 'openhousevideo'); ?></option>
          <option value="3"><?php esc_html_e('A-Z', 'openhousevideo'); ?></option>
          <option value="4"><?php esc_html_e('Z-A', 'openhousevideo'); ?></option>
        </select>
      </div>
    </div>
    <div id="ohv-listing-filter" class="ohv-listing-filter">
      <!-- Statuses -->
      <ul class="ohv-ul-lt-status ohv-clearfix" data-toggle="ohv-listing-status">
        <?php $statuses = $data['statuses']; $active_status = ''; ?>
        <?php foreach ($statuses as $i => $status):
          if ($status['active']) $active_status = strtolower($status['title']); ?>
          <li>
            <label class="ohv-btn ohv-btn-<?php esc_html_e(strtolower($status['title'])); ?> <?php if ($status['active']): ?>ohv-active<?php endif; ?>"><input name="ohv-status-filter" type="radio" <?php if ($status['active']): ?>checked<?php endif; ?> value="<?php esc_html_e(strtolower($status['title'])); ?>" /> <?php esc_html_e($status['title']); ?></label>
          </li>
        <?php endforeach; ?>
      </ul><!-- /.ohv-ul-lt-status -->
    </div>
  </div>

  <div id="ohv-listing-block" class="ohv-listing-block ohv-filter-<?php esc_html_e($active_status); ?>">
    <!-- AJAX -->
  </div><!-- /#ohv-listing-block -->

  <div id="ohv-listing-preview" class="ohv-listing-preview">
    <div class="ohv-listing-preview-content"></div>
  </div><!-- /#ohv-listing-preview -->

  <?php ohv_the_template('templates/partials/_listing-item'); ?>

</div><!-- /#ohv-listing -->
