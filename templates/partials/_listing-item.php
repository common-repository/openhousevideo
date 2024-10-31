<?php defined( 'ABSPATH' ) or exit; ?>
<div class="ohv-lt-item-tpl" style="display: none;">
  <div class="ohv-lt-item ohv-lt-item-#{status}" data-id="#{id}" data-title="#{street} #{city} #{state} #{postal}" data-created_at="#{created_at}">
    <div class="ohv-lt-header"></div>
    <div class="ohv-lt-body" style="background-image: url(#{thumb_url});">
      <span data-toggle="ohv-listing-select">
        <span class="ohv-lt-click-text"><?php esc_html_e('Click to select', 'openhousevideo'); ?></span>
        <span class="ohv-lt-click-text-selected"><span><?php esc_html_e('Selected', 'openhousevideo'); ?></span>
      </span>
    </div>
    <div class="ohv-lt-footer">
      <p class="ohv-lt-addr">
        <span class="ohv-lt-addr-street" title="#{street}">#{street}</span>
        <span class="ohv-lt-addr-city" title="#{city}">#{city}</span>
        <span class="ohv-lt-addr-state" title="#{state}">#{state}</span>
        <span class="ohv-lt-addr-postal" title="#{postal}">#{postal}</span>
      </p>
    </div>
  </div>
</div>
