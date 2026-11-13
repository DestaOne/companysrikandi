{{-- Props: seconds (lama tanpa aktivitas sebelum logout otomatis, dalam detik) --}}
@props(['seconds'])

{{-- Form logout tersembunyi; dikirim otomatis bila tidak ada aktivitas selama $seconds detik --}}
<form method="POST" action="{{ route('logout') }}" class="hidden"
      x-data="idleLogout({{ (int) $seconds }})"
      x-init="reset()"
      @mousemove.window.throttle.500ms="reset()"
      @keydown.window="reset()"
      @click.window="reset()"
      @scroll.window.throttle.500ms="reset()"
      @touchstart.window.passive="reset()">
    @csrf
    <input type="hidden" name="idle" value="1">
</form>
