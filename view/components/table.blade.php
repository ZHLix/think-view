{!! resource('js/layui/extend/ext/soulTable.css') !!}

@slot('toolbar')
    {{--<div class="layui-input-inline relative" title="搜索">
        <label class="label-inline-icon-sm layui-icon layui-icon-search"></label>
        <input type="text" class="layui-input inline-icon-sm sm" id="search" lay-skin="primary">
    </div>--}}
    @if($toolbar['add'] ?? false)
        <button class="layui-btn layui-btn-sm margin-left-sm layui-icon layui-icon-add-1" title="添加"
                onclick="xadmin.open('添加', '{{ $toolbar['add'] }}')"
        ></button>
    @endif
    @if($toolbar['refresh'] ?? false)
        <button class="layui-btn layui-btn-sm layui-icon layui-icon-refresh" title="重载表格" lay-submit
                lay-filter="refresh"></button>
    @endif
@endslot

<div class="cu-card" style="height: 100%;">
    <div class="cu-item margin-0 bg-white shadow padding-lr padding-tb-xs" style="height: 100%;">
        <table id="table" lay-filter="table"></table>
    </div>
</div>

<script>
    layui.extend({
        excel: '/static/layui/extend/ext/excel',
        tableChild: '/static/layui/extend/ext/tableChild',
        tableFilter: '/static/layui/extend/ext/tableFilter',
        tableMerge: '/static/layui/extend/ext/tableMerge',
        soulTable: '/static/layui/extend/ext/soulTable'  // 模块别名
    }).use(['form', 'table', 'soulTable'], function () {
        var form = layui.form, table = layui.table, soulTable = layui.soulTable

        var tableObj = table.render({
            elem: '#table',
            url: '{{ url($url) }}',
            method: '{{ strtoupper('post') }}',
            response: {
                statusName: 'code',
                statusCode: 200,
                msgName: 'msg',
                countName: 'total',
                dataName: 'data'
            },
            parseData: function (res) {
                return {
                    'code': res.code,
                    'msg': res.msg,
                    'total': res.data.total,
                    'data': res.data.data
                }
            },
            overflow: {
                type: 'tips'
                , hoverTime: 300 // 悬停时间，单位ms, 悬停 hoverTime 后才会显示，默认为 0
                , color: 'black' // 字体颜色
                , bgColor: 'white' // 背景色
                , minWidth: 100 // 最小宽度
                , maxWidth: 500 // 最大宽度
            },
            filter: {
                {{-- // 默认为 ['column','data','condition','editCondition','excel']
                     // 默认为 ['column','data','condition','editCondition','excel']
                     // 分别对应：表格列、筛选数据、筛选条件、编辑筛选条件、导出excel
                     // items: ['column', 'excel'], // 只显示表格列和导出excel两个菜单项 --}}
                items: ['column', 'condition', 'editCondition', 'excel'],
                bottom: false,
                clearFilter: true
            },
            @if($contextmenu ?? false)
            contextmenu: {
                @foreach($contextmenu as $k => $v)
                '{{ $k }}': [
                        @foreach($v as $k2 => $v2)
                        @switch((string)$k2)
                        @case('edit')
                    {
                        name: '{{ $v2['name'] }}',
                        click: function (obj) {
                            xadmin.open('修改', '{{ $v2['url'] }}/' + obj.row.id)
                        }
                    },
                        @break
                        @case('del')
                    {
                        name: '{{ $v2['name'] }}',
                        click: function (obj) {
                            layer.confirm('确认删除[' + obj.text + ']?', {icon: 3, title: '提示'}, function (index) {
                                layer.close(index)
                                $.ajax({
                                    url: '{{ $v2['url'] }}/' + obj.row.id,
                                    type: '{{ strtoupper('delete') }}',
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
                            })
                        }
                    },
                        @break
                        @default
                    {
                        name: '{{ $v2['name'] }}',
                        click: {!! $v2['handle'] !!}
                    },
                    @endswitch
                    @endforeach
                ]
                @endforeach
            },
            @endif
            height: 'full-170',
            limit: 30,
            page: true,
            cols: [JSON.parse('{!! json_encode($cols) !!}')],
            done: function () {
                soulTable.render(this)
            }
        })

        form.on('submit', function (data) {
            tableObj.reload({where: data.field})
        })

        {{--$('#search').keydown(function (e) {
            if (e.keyCode === 13) {
                tableObj.reload({where: {}})
            }
        })--}}
    })
</script>