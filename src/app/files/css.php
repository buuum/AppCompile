<?php
error_reporting(E_STRICT);

if (!isset($_REQUEST['p'])) {
    die;
}
$request = $_GET;

$plugins = base64_decode($request['p']);
$folder = $request['f'];
$prefix = (!empty($request['pre'])) ? $request['pre'] : '';
$plugins = explode(',', $plugins);
$timecache = 3600 * 24 * 365;

$base_cache = __DIR__ . '/../../temp/assets';
$cachefile = $base_cache . '/css' . $folder . $prefix . sha1($request['p']);

header('Content-type: text/css');
header('Expires: ' . gmdate("D, d M Y H:i:s", time() + $timecache) . ' GMT');

if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
    ob_start("ob_gzhandler");
} else {
    ob_start();
}

function compress($buffer)
{
    /* remove comments */
    $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
    /* remove tabs, spaces, newlines, etc. */
    $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
    return $buffer;
}

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

if (!file_exists($cachefile) || (intval(@filemtime($cachefile)) + $timecache) < time()) {
    $jsmin_php = '';
    foreach ($plugins as $plugin) {
        $files = glob_recursive(__DIR__ . '/plugins/' . $plugin . '/*.css');
        foreach ($files as $file) {
            $jsmin_php .= file_get_contents($file);
        }
    }

    $files = glob_recursive(__DIR__ . '/' . $folder . '/css/*.css');
    foreach ($files as $file) {
        $jsmin_php .= file_get_contents($file);
    }

    $salida = compress($jsmin_php);

    if (!empty($prefix)) {
        if (!is_dir($base_cache)) {
            mkdir($base_cache, 0755);
        }
        file_put_contents($cachefile, $salida);
    }
    echo $salida;
} else {
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($cachefile)) . ' GMT', true, 200);
    echo file_get_contents($cachefile);
}
