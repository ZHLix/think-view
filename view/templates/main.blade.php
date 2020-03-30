@if($topNavInfo)
    <div class="padding-lr bg-white shadow cu-bar">
                <span class="layui-breadcrumb">
                        @foreach($topNavInfo as $k => $v)
                        <a href="javascript:void(0);">
                                @if($k === count($topNavInfo) - 1)
                                <cite>{{ $v }}</cite>
                            @else
                                {{ $v }}
                            @endif
                            </a>
                    @endforeach
                </span>
        <div class="right">
            {!! $toolbar ?? '' !!}
        </div>
    </div>
@endif
<div class="main padding flex-sub" style="min-height: 0;overflow-y: auto">
    {!! $slot !!}
</div>