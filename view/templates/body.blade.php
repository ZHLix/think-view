@extends('app')

@section('body')
    <style>
        .body-header {
            background-color: #393D49;
        }

        .body-header .title {
            width: 220px;
            height: 100%;
        }

        .body-main {
            position: relative;
        }

        .body-main > #main {
            margin-left: 220px;
        }
    </style>

    <div class="body-header cu-bar">
        <a href="{{ url('index/index') }}" class="title text-white text-lg text-center"
           style="line-height: 60px;">{{ $title ?? '后台管理系统' }}</a>


        <ul class="layui-nav right">
            <li class="layui-nav-item">
                <a href="javascript:void(0);">{{ $username }}</a>
                <dl class="layui-nav-child">
                    {{--二级菜单--}}
                    <dd>
                        <a onclick="xadmin.open('个人信息', '{{ url('login/edit') }}')" id="personal">个人信息</a>
                    </dd>
                    <dd>
                        <a id="logout" href="javascript:void (0);">退出登录</a>
                    </dd>
                </dl>
            </li>
        </ul>
    </div>

    <div class="body-main flex-sub" style="min-height: 0;">
        <div id="hxNavbar"></div>
        <div id="main" class="flex flex-direction" style="height: 100%;overflow: hidden;">
            @yield('main')
        </div>
    </div>

    <script>
        layui.extend({
            hxNav: '/static/layui/extend/hxNav'
        }).use(['element', 'hxNav', 'layer', 'jquery'], function () {
            var $ = layui.jquery, layer = layui.layer

            layui.hxNav({
                element: '#hxNavbar',
                url: '{{ url('index/nav') }}',
                type: 'post',
                shrink: false,
                onSelect: function (v) {
                    if (!v.hasChildren && v.id !== Number('{{ $navSelected['id'] }}')) {
                        location.href = v.href
                    }
                },
                success: function (res, level) {
                    @if($navSelected)
                    if (res[0].hasOwnProperty('pid') && res[0].pid === {{ $navSelected['pid'] }}) {
                        layui.hxNav('select', {{ $navSelected['id'] }})
                    } else {
                        layui.hxNav('getData', {id: '{{ $navSelected['pid'] }}', level: level + 1})
                    }
                    @endif
                }
            })


            $('#logout').click(function () {
                $.ajax({
                    url: '{{ url('login/logout') }}',
                    success: function (res) {
                        layer.msg(res.msg,
                            {
                                icon: res.code === 200 ? 1 : 5,
                                anim: res.code === 200 ? 0 : 6
                            },
                            function () {
                                if (res.code === 200) {
                                    location.reload()
                                }
                            }
                        )
                    }
                })
            })
        })
    </script>
@endsection