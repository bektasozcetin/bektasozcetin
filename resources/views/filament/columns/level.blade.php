@php $level = (int) $getState(); @endphp
<div class="bk-level" style="--lv: {{ $level }}%">
    <span class="bk-level-track"><i></i></span>
    <span class="bk-level-num">%{{ $level }}</span>
</div>
