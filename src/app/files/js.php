<?php
error_reporting(E_STRICT);

if (!isset($_REQUEST['p'])) {
    die;
}
// include __DIR__.'/../../lib/classes/Security.php';
// $request = FRM\Security::xss_clean($_GET);
$request = $_GET;

$plugins = base64_decode($request['p']);
$folder = $request['f'];
$prefix = (!empty($request['pre'])) ? $request['pre'] : '';
$plugins = explode(',', $plugins);
$timecache = 3600 * 24 * 365;

$base_cache = __DIR__ . '/../../temp/assets';
$cachefile = $base_cache . '/js' . $folder . $prefix . sha1($request['p']);

if (!function_exists('glob_recursive')) {
    // Does not support flag GLOB_BRACE
    function glob_recursive($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags);

        foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge($files, glob_recursive($dir . '/' . basename($pattern), $flags));
        }

        return $files;
    }
}

header('Content-type: text/javascript');
header('Expires: ' . gmdate("D, d M Y H:i:s", time() + $timecache) . ' GMT');

if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
    ob_start("ob_gzhandler");
} else {
    ob_start();
}

if (!file_exists($cachefile) || (intval(@filemtime($cachefile)) + $timecache) < time()) {
    // $files = glob_recursive("*.js");
    $jsmin_php = '';
    foreach ($plugins as $plugin) {

        $files = glob_recursive(__DIR__ . '/plugins/minify/' . $plugin . '/*.js');
        foreach ($files as $file) {
            $jsmin_php .= file_get_contents($file);
        }

    }

    $files = glob_recursive(__DIR__ . '/' . $folder . '/js/*.js');
    foreach ($files as $file) {
        $jsmin_php .= file_get_contents($file);
    }

    if (!empty($prefix)) {
        if (!is_dir($base_cache)) {
            mkdir($base_cache, 0755);
        }
        file_put_contents($cachefile, $jsmin_php);
    }
    echo $jsmin_php;
} else {
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($cachefile)) . ' GMT', true, 200);
    echo file_get_contents($cachefile);
}

