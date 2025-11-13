<div class="card">
  @if(isset($image))
    <img src="{{ $image }}" alt="{{ $title ?? 'image' }}" class="card-img">
  @endif
  <div class="card-body">
    <h3 class="card-title">{{ $title ?? '' }}</h3>
    <p class="card-text">{{ $slot ?? ($desc ?? '') }}</p>
    @if(isset($link))
      <a href="{{ $link }}" class="btn">Lihat</a>
    @endif
  </div>
</div>
