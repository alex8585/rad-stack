<?php

function admin_vite($path = '/dist/admin/assets')
{
    $assetsPath = public_path().$path;
    $files = array_diff(scandir($assetsPath), ['..', '.']);
    foreach ($files as $file) {
        $parts = pathinfo($file);
        $ext = $parts['extension'];
        $url = url("{$path}/{$file}");
        if ('js' == $ext) {
            echo "<script type='module' crossorigin src='{$url}'></script>\n";
        } elseif ('css' == $ext) {
            echo "<link rel='stylesheet' href='{$url}'>\n";
        }
    }
}
