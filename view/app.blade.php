<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? '后台管理系统' }}</title>
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="https://www.layuicdn.com/layui-v2.5.6/css/layui.css">
    {!! resource('css/colorUi/main.css') !!}
    {!! resource('css/colorUi/animation.css') !!}
    <script src="https://www.layuicdn.com/layui-v2.5.6/layui.js"></script>
    <style>
        body {
            width: 100vw;
            height: 100vh;
        }

        .layui-input.sm {
            height: 30px;
        }

        .relative {
            position: relative;
        }

        .inline-icon {
            padding-left: 38px;
        }

        .inline-icon-sm {
            padding-left: 30px;
        }

        .label-inline-icon {
            width: 38px;
            position: absolute;
            left: 1px;
            top: 1px;
            line-height: 36px;
            text-align: center;
        }

        .label-inline-icon-sm {
            width: 30px;
            position: absolute;
            left: 1px;
            top: 1px;
            line-height: 28px;
            text-align: center;
        }
    </style>
    @yield('head')
    <script>
        layui.use(['jquery'], function () {
            var $ = layui.jquery
            var Xadmin = function () {
                this.v = '2.2'; //版本号
            }
            /**
             * [open 打开弹出层]
             * @param  {[type]}  title [弹出层标题]
             * @param  {[type]}  url   [弹出层地址]
             * @param  {[type]}  w     [宽]
             * @param  {[type]}  h     [高]
             * @param  {Boolean} full  [全屏]
             * @return {[type]}        [description]
             */
            Xadmin.prototype.open = function (title, url, w, h, full) {
                if (title == null || title == '') {
                    var title = false
                }

                if (url == null || url == '') {
                    var url = "404.html"
                }

                if (w == null || w == '') {
                    var w = ($(window).width() * 0.9)
                }

                if (h == null || h == '') {
                    var h = ($(window).height() - 50)
                }

                var index = layer.open({
                    type: 2,
                    area: [w + 'px', h + 'px'],
                    fix: false, //不固定
                    maxmin: true,
                    shadeClose: true,
                    shade: 0.4,
                    title: title,
                    content: url
                })
                if (full) {
                    layer.full(index)
                }
            }
            /**
             * [close 关闭弹出层]
             * @return {[type]} [description]
             */
            Xadmin.prototype.close = function (func) {
                var index = parent.layer.getFrameIndex(window.name)
                parent.layer.close(index)
                func()
            }

            window.xadmin = new Xadmin()
        })
    </script>
</head>
<body class="flex flex-direction">
@yield('body')
</body>
</html>