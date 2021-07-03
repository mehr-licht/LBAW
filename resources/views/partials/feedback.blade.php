@if ($type === 1)
<div class="alert alert-success" role="alert">
  <p>{{ $message }}</p>
</div>
@elseif($type === 1)
<div class="alert alert-danger" role="alert">
  <p>{{ $message }}</p>
</div>
@endif