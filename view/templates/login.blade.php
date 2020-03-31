@extends('app')

@section('body')
    <meta name="public_key"
          content="{{ $public_key }}">
    <script src="{{ '/static/js/jsencrypt.min.js' }}"></script>
    <style>
        .login-wrapper {
            width: 100vw;
            height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 420px;
            background-color: transparent !important;
        }

        .forget_password {
            color: #009688;
        }
    </style>

    <div class="login-wrapper flex flex-direction">

        <div class="login-container flex-treble flex flex-direction justify-center align-center cu-card">

            <div class="container margin cu-item padding shadow layui-anim layui-anim-up">

                <div class="message padding margin-bottom-xl text-center text-xl">{{ $title ?? '后台管理系统' }}</div>

                <div class="layui-form padding-sm">

                    <div class="layui-form-item margin-bottom-xl relative">
                        <label for="username"
                               class="layui-icon layui-icon-username text-gray label-inline-icon"></label>
                        <input type="text"
                               name="username"
                               id="username"
                               class="layui-input inline-icon"
                               autofocus
                               lay-verify="required"
                               placeholder="用户名">
                    </div>

                    <div class="layui-form-item margin-bottom-xl relative">
                        <label for="password"
                               class="layui-icon layui-icon-password text-gray label-inline-icon"></label>
                        <input type="password"
                               name="password"
                               id="password"
                               class="layui-input inline-icon"
                               lay-verify="required|password"
                               placeholder="密码">
                    </div>

                    @if(!env('app_debug'))
                        <div class="layui-form-item margin-bottom-xl relative">
                            <div class="layui-row">
                                <div class="layui-col-xs5 layui-col-sm7">
                                    <label for="captcha"
                                           class="layui-icon layui-icon-vercode text-gray label-inline-icon"></label>
                                    <input type="text"
                                           name="captcha"
                                           id="captcha"
                                           class="layui-input inline-icon"
                                           lay-verify="required"
                                           placeholder="验证码">
                                </div>
                                <div class="layui-col-xs6 layui-col-sm5 padding-left-sm">
                                    {!! captcha_img() !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="layui-form-item">
                        <button class="layui-btn layui-btn-fluid"
                                lay-submit
                                lay-filter="login">登 录
                        </button>
                    </div>

                    <div class="layui-form-item">
                        <p class="text-center text-gray text-sm">&copy;{{ date('Y') }} {{ $_SERVER['HTTP_HOST'] }}</p>
                    </div>

                </div>

            </div>

        </div>

        <div class="flex-sub"></div>

    </div>

    <script>
        layui.use(['form', 'jquery'], function () {
            var form = layui.form, $ = layui.jquery

            var public_key = $('meta[name=public_key]').attr('content')

            form.verify({
                password: [
                    /^[\S]{6,18}$/,
                    '密码必须6到18位，且不能出现空格'
                ]
            })

            form.on('submit(login)', function (data) {
                var encrypt = new JSEncrypt()
                encrypt.setPublicKey(public_key)
                var encryptedData = encrypt.encrypt(JSON.stringify(data.field))

                $.ajax({
                    url: '',
                    type: 'post',
                    data: {encryptedData: encryptedData},
                    success: function (res) {
                        layer.msg(res.msg, {
                            icon: res.code === 200 ? 6 : 5,
                            anim: res.code === 200 ? 0 : 6
                        }, function () {
                            if (res.code === 200) {
                                location.reload()
                            }
                        })
                    }
                })

                return false;
            })

        })
    </script>
@endsection