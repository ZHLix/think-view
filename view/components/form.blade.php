@if($public_key ?? false)
    <meta name="public_key"
          content="{{ $public_key }}">
    <script src="{{ '/static/js/jsencrypt.min.js' }}"></script>
    {!! resource('js/jsencrypt.min.js') !!}
@endif

<div class="layui-form padding">
    <style>
        @if($form['label-width'] ?? false)
        label.layui-form-label {
            width: {{ $form['label-width'] }}px;
        }

        div.layui-input-block {
            margin-left: {{ $form['label-width'] + 30 }}px;
        }

        @endif


            div.layui-input-inline {
            margin-left: 30px;
        }
    </style>

    @if($form['data'] ?? false)
        @foreach($form['data'] as $k => $v)
            @if(($v['type'] ?? 'text') === 'captcha' && env('app_debug'))
                @break
            @endif

            <div class="layui-form-item">

                <label for="{{ $v['name'] }}"
                       class="layui-form-label">
                    {{ $v['title'] }}
                    @if(isset($v['verify']) && in_array('required', explode('|', $v['verify'])))
                        <span class="text-red">*</span>
                    @endif
                </label>

                <div class="layui-input-{{ !isset($v['aux']) ? $v['display'] ?? 'block' : 'inline' }}">

                    @if(($v['type'] ?? 'text') === 'select')

                        <select name="{{ $v['name'] }}"
                                id="{{ $v['name'] }}"
                                {{ !isset($v['search']) ?: 'lay-search' }}
                                lay-verify="{{ $v['verify'] ?? '' }}"
                                {{ !($v['disabled'] ?? false) ?: 'disabled' }}>

                            @if(isset($v['default']))
                                <option value=""></option>
                            @endif

                            @foreach($v['sub'] as $k2 => $v2)
                                <option value="{{ is_int($k2) ?: $v2 }}" {{ ($v['value'] ?? array_keys($v['sub'])[0]) === $k2 ?: 'selected' }}>{{ $v2 }}</option>
                            @endforeach

                        </select>

                    @elseif(($v['type'] ?? 'text') === 'select-radio')

                        <div id="form-select-{{ $k }}"></div>

                        <script>
                            layui.extend({
                                'xmSelect': '/static/layui/extend/xm-select'
                            }).use(['xmSelect'], function () {
                                var xmSelect = layui.xmSelect

                                var selectObj_{{ $k }} = xmSelect.render({
                                    el: '#form-select-{{ $k }}',
                                    name: '{{ $v['name'] }}',
                                    @if($v['placeholder'] ?? false)
                                    tips: '{{ $v['placeholder'] }}',
                                    @endif
                                            @if($v['prop'] ?? false)
                                    prop: JSON.parse('{!! json_encode($v['prop'] ?? []) !!}'),
                                    @endif
                                    radio: true,
                                    data: JSON.parse('{!! json_encode($v['sub']) !!}')
                                })

                                @if ($v['value'] ?? false)
                                selectObj_{{ $k }}.setValue(JSON.parse('{!! json_encode($v['value']) !!}'))
                                @endif
                            })
                        </script>

                    @elseif(($v['type'] ?? 'text') === 'select-checkbox')

                        <div id="form-select-{{ $k }}"></div>

                        <script>
                            layui.extend({
                                'xmSelect': '/static/layui/extend/xm-select'
                            }).use(['xmSelect'], function () {
                                var xmSelect = layui.xmSelect

                                var selectObj_{{ $k }} = xmSelect.render({
                                    el: '#form-select-{{ $k }}',
                                    name: '{{ $v['name'] }}',
                                    @if($v['placeholder'] ?? false)
                                    tips: '{{ $v['placeholder'] }}',
                                    @endif
                                            @if($v['prop'] ?? false)
                                    prop: JSON.parse('{!! json_encode($v['prop'] ?? []) !!}'),
                                    @endif
                                    on: function (obj) {
                                        console.log(obj.arr)
                                    },
                                    data: JSON.parse('{!! json_encode($v['sub']) !!}')
                                })

                                @if ($v['value'] ?? false)
                                selectObj_{{ $k }}.setValue(JSON.parse('{!! json_encode(explode(',', $v['value'])) !!}'))
                                @endif
                            })
                        </script>

                    @elseif(($v['type'] ?? 'text') === 'select-tree')
                        <link rel="stylesheet" href="{{ '/static/layui/extend/eleTree/eleTree.css' }}">
                        <div id="form-select-{{ $k }}"></div>

                        <script>
                            layui.extend({
                                'eleTree': '/static/layui/extend/eleTree/eleTree',
                                'xmSelect': '/static/layui/extend/xm-select'
                            }).use(['xmSelect', 'eleTree', 'jquery'], function () {
                                var xmSelect = layui.xmSelect, eleTree = layui.eleTree, $ = layui.jquery

                                var selectObj_{{ $k }} = xmSelect.render({
                                    el: '#form-select-{{ $k }}',
                                    name: '{{ $v['name'] }}',
                                    model: {
                                        label: {
                                            type: 'text',
                                            //使用字符串拼接的方式
                                            text: {
                                                //左边拼接的字符
                                                left: '',
                                                //右边拼接的字符
                                                right: '',
                                                //中间的分隔符
                                                separator: ',',
                                            },
                                        }
                                    },
                                    @if($v['placeholder'] ?? false)
                                    tips: '{{ $v['placeholder'] }}',
                                    @endif
                                            @if($v['prop'] ?? false)
                                    prop: JSON.parse('{!! json_encode($v['prop'] ?? []) !!}'),
                                    @endif
                                    content: '<div class="align-center flex flex-wrap xm-search" style="width: 100%;overflow: hidden;box-sizing: border-box;"><i class="xm-iconfont xm-icon-sousuo"></i><input type="text" class="xm-input xm-search-input" placeholder="请选择"></div><div id="form-select-{{ $k }}-tree" lay-filter="form-select-{{ $k }}-tree" style="width: 100%;max-height: 300px;overflow: hidden;overflow-y: auto;"></div>'
                                    // showCheckbox: true,
                                })

                                var selectTreeObj_{{ $k }} = eleTree.render({
                                    elem: '#form-select-{{ $k }}-tree',
                                    data: JSON.parse('{!! json_encode($v['sub']) !!}'),
                                    showCheckbox: true,
                                    defaultExpandAll: true,
                                    defaultCheckedKeys: JSON.parse('{!! json_encode(explode(',', $v['value'] ?? '')) !!}'),
                                    searchNodeMethod: function (value, data) {
                                        if (!value) return true;
                                        return data.label.indexOf(value) !== -1;
                                    }
                                })

                                // 监听下拉多选的选择
                                selectObj_{{ $k }}.update({
                                    on({arr, change, isAdd}) {
                                        if (isAdd === false) {//监听取消
                                            var id_arr = []
                                            for (var i in arr) {
                                                if (arr.hasOwnProperty(i)) {
                                                    id_arr.append(arr[i].id)
                                                }
                                            }
                                            console.log(arr)
                                            selectTreeObj_{{ $k }}.setChecked(id_arr, true);
                                        }
                                    },
                                });

                                // 监听树的选择
                                eleTree.on("nodeChecked(form-select-{{ $k }}-tree)", function (d) {
                                    var arr = selectTreeObj_{{ $k }}.getChecked(true, false)
                                    console.log(arr)
                                    selectObj_{{ $k }}.update({
                                        prop: JSON.parse('{!! json_encode($v['prop'] ?? []) !!}'),
                                        data: arr,
                                    }).setValue(arr)
                                })


                                $('.xm-search-input').keydown(function (e) {
                                    if (e.keyCode === 13) {
                                        selectTreeObj_{{ $k }}.search($(this).val())
                                    }
                                })

                                @if ($v['value'] ?? false)
                                selectObj_{{ $k }}.update({
                                    prop: JSON.parse('{!! json_encode($v['prop'] ?? []) !!}'),
                                    data: selectTreeObj_{{ $k }}.getChecked(true, false),
                                }).setValue(JSON.parse('{!! json_encode(explode(',', $v['value'])) !!}'))
                                @endif
                            })
                        </script>

                    @elseif(($v['type'] ?? 'text') === 'checkbox')
                        @foreach($v['sub'] as $k2 => $v2)
                            <input type="checkbox"
                                   name="{{ $v['name'] }}[{{ $k2 }}]"
                                   title="{{ $v2 }}"
                                   {{ !in_array($k2, explode(',', $v['value'])) ?: 'checked' }} lay-skin="{{ $v['skin'] ?? '' }}"
                                    {{ !($v2['disabled'] ?? false) ?: 'disabled' }}>
                        @endforeach

                    @elseif(($v['type'] ?? 'text') === 'switch')
                        <input type="checkbox"
                               name="{{ $v['name'] }}"
                               lay-skin="switch"
                               {{ !($v['value'] ?: false) ?: 'checked' }}
                               lay-text="{{ $v['text'] }}"
                                {{ !($v['disabled'] ?? false) ?: 'disabled' }}>

                    @elseif(($v['type'] ?? 'text') === 'radio')

                        @foreach($v['sub'] as $k2 => $v2)
                            <input type="radio"
                                   name="{{ $v['name'] }}"
                                   value="{{ $k2 }}"
                                   title="{{ $v2 }}"
                                    {{ ($v['value'] ?: array_keys($v['sub'])[0]) === $k2 ?: 'checked' }}
                                    {{ !($v2['disabled'] ?? false) ?: 'disabled' }}>
                        @endforeach


                    @elseif(($v['type'] ?? 'text') === 'textarea')

                        <textarea name="{{ $v['name'] }}"
                                  lay-verify="{{ $v['verify'] ?? '' }}"
                                  placeholder="{{ $v['placeholder'] ?? '' }}"
                                  class="layui-textarea"
                                {{ !($v['disabled'] ?? false) ?: 'disabled' }}>{{ $v['value'] ?? '' }}</textarea>


                    @elseif(($v['type'] ?? 'text') === 'captcha')

                        <div class="layui-input-inline" style="margin-left: 0">
                            <div class="layui-row">
                                <div class="layui-col-xs5 layui-col-sm7">
                                    <input type="text"
                                           name="{{ $v['name'] }}"
                                           id="{{ $v['name'] }}"
                                           class="layui-input"
                                           lay-verify="{{ $v['verify'] ?? '' }}"
                                           placeholder="{{ $v['placeholder'] ?? '' }}">
                                </div>
                                <div class="layui-col-xs6 layui-col-sm5 padding-left-sm">
                                    {!! captcha_img() !!}
                                </div>
                            </div>
                        </div>

                    @else

                        <input type="{{ $v['type'] ?? 'text' }}"
                               class="layui-input"
                               name="{{ $v['name'] }}"
                               id="{{ $v['name'] }}"
                               value="{{ $v['value'] ?? '' }}"
                               placeholder="{{ $v['placeholder'] ?? '' }}"
                               {{ !isset($v['autofocus']) ?: 'autofocus' }}
                               lay-verify="{{ $v['verify'] ?? '' }}"
                                {{ !($v['disabled'] ?? false) ?: 'disabled' }}>

                    @endif

                </div>

                @if(isset($v['aux']))
                    <div class="layui-form-mid layui-word-aux">{!! $v['aux'] !!}</div>
                @endif

            </div>

        @endforeach

        <layui-form-item>
            <div class="layui-input-block">
                <button class="layui-btn"
                        lay-submit
                        lay-filter="*">提交
                </button>
            </div>
        </layui-form-item>


        <script>
            layui.use(['form', 'jquery'], function () {
                var form = layui.form, $ = layui.jquery

                        @if(isset($public_key))

                var public_key = $('meta[name=public_key]').attr('content')

                @endif

                form.verify({
                    @if($form['verify'] ?? false)
                            @foreach($form['verify'] as $k => $v)
                    '{{ $k }}': [{{ $v[0] }}, '{{ $v['1'] }}']
                    @endforeach
                    @endif
                })

                form.on('submit(*)', function (data) {
                    @if($form['debug'] ?? false)
                    console.log(data)
                    return false
                            @endif
                            @if(isset($public_key))

                    var encrypt = new JSEncrypt()
                    encrypt.setPublicKey(public_key)
                    var params = {encryptedData: encrypt.encrypt(JSON.stringify(data.field))}

                            @else

                    var params = data.field

                    @endif

                    $.ajax({
                        url: '{{ $form['url'] ?? '' }}',
                        type: '{{ strtoupper($form['type'] ?? 'post') }}',
                        data: params,
                        success: function (res) {
                            layer.msg(res.msg, {
                                icon: res.code === 200 ? 6 : 5,
                                anim: res.code === 200 ? 0 : 6
                            }, function () {
                                if (res.code === 200) {
                                    @if(($form['back'] ?? false) && ($form['reload'] ?? false))
                                    xadmin.close(function () {
                                        parent.location.reload()
                                    })
                                    @elseif(($form['back'] ?? false) && !($form['reload'] ?? false))
                                    xadmin.close()
                                    @elseif(!($form['back'] ?? false) && ($form['reload'] ?? false))
                                    location.reload()
                                    @endif
                                }
                            })
                        }
                    })
                })
            })
        </script>

    @endif

</div>