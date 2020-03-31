<?php

if (!function_exists('resource')) {
    /**
     * @param $path
     *
     * @return string
     * @throws \think\Exception
     */
    function resource ($path)
    {
        if (!strpos($path, '.')) {
            throw new \think\Exception('文件格式不正确');
        }

        [, $suffix] = explode('.', $path);
        $filename = __DIR__ . "/static/$suffix/$path";

        if (!file_exists($filename)) {
            throw new \think\Exception('文件不存在');
        }

        $content = file_get_contents($filename);

        switch ($suffix) {
            case 'css':
                return "<style>$content</style>";
            case 'js':
                return "<script>$content</script>";
        }
    }
}