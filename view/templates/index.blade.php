@extends('templates.body')

@section('main')
    <div class="layui-fluid padding-top" style="width: 100%;">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <blockquote class="layui-elem-quote">
                            <span>欢迎管理员：</span>
                            <span class="text-red">{{ $username }}</span>
                            <span>！当前时间：{{ date('Y-m-d H:i:s') }}</span>
                        </blockquote>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection