<?php
/**
 * Utility function to get specified template by it's name.
 *
 * @since 1.0.0
 * @param string  $name Template name without extension.
 * @param mixed   $data Data to be available from within template.
 * @return string       Template content. Returns empty string if template name is invalid or template file wasn't found.
 */
function ohv_get_template($name = '', $data = array())
{

    // Validate template name
    if (preg_match("/^(?!-)[a-z0-9-_]+(?<!-)(\/(?!-)[a-z0-9-_]+(?<!-))*$/", $name) !== 1) {
        return '';
    }

    // The full path to template file
    $file = plugin_dir_path(OHV_PLUGIN_FILE) . $name . '.php';

    // Look for a specified file
    if (file_exists($file)) {
        ob_start();
        include $file;
        $template = ob_get_contents();
        ob_end_clean();
    }

    return isset($template) ? $template : '';
}

/**
 * Utility function to display specified template by it's name.
 *
 * @since 1.0.0
 * @param string  $name Template name (without extension).
 * @param mixed   $data Template data to be passed to the template.
 */
function ohv_the_template($name, $data = null)
{
    echo ohv_get_template($name, $data);
}

/**
 * Get URL by Hash
 *
 * @since 1.0.0
 * @param string  $hash Url name.
 */
function ohv_get_url($hash)
{
    $base = OHV_LINK;
    $base_api = $base . '/api';
    switch ($hash) {
      case 'home':
        $url = $base . '/';
        break;
      case 'profile':
        $url = $base . '/user/myprofile.html';
        break;
      case 'new-listing':
        $url = $base . '/video/new.html';
        break;
      case 'api-login':
        $url = $base_api . '/login';
        break;
      case 'api-validate_token':
        $url = $base_api . '/validate_token';
        break;
      case 'api-user':
        $url = $base_api . '/user';
        break;
      case 'api-listing':
        $url = $base_api . '/listing';
        break;
      case 'api-info':
        $url = $base_api . '/info';
        break;
      default:
        $url = $base_api . '/';
    }

    return $url;
}

/**
 * Parse URL and SERVER into the same connection type
 *
 * @since 1.0.0
 * @param string $url
 */
function ohv_parse_type_connection($url)
{
    $is_secury = false;
    if (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == "on") {
        $is_secury = true;
    } elseif ((!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') ||
             (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on')) {
        $is_secury = true;
    }

    $type = $is_secury ? 'https://' : 'http://';
    $url_type = (strpos($url, 'https://') !== false) ? 'https://' : 'http://';
    if ($type !== $url_type) {
      $url = str_replace($url_type, $type, $url);
    }

    return $url;
}

/**
 * Get OHV token
 *
 * @since 1.0.0
 */
function ohv_get_token()
{
    return get_option('ohv_token');
}

/**
 * Update/Delete OHV token
 *
 * @since 1.0.0
 * @param mixed $token.
 */
function ohv_update_token($token = null)
{
    if (!is_null($token)) {
        update_option('ohv_token', $token);
    } else {
        delete_option('ohv_token');
    }
}
