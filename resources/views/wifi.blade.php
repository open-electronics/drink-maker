
    <div class="col s10 offset-s1" >
        <div align="center">
            <h5>WiFi networks</h5>
        </div>
		<script>
                $(document).ready(function () {
                	$('.modal-trigger').leanModal();
                	$('.modal-trigger').click(function () {
                		window.network = this.getAttribute('data-network');
                	});
                });
		</script>
        <ul class="collection">
        @forelse($wifi as $w)
            <li class="collection-item modal-trigger" style="cursor: pointer" data-target="passwordModal" data-network="{{$w}}" >
                {{$w}}
                @if($w!=$settings["wifi"])
                    <i data-target="passwordModal" style="color: dodgerblue;" class="mdi-device-signal-wifi-4-bar right"></i>
                @else
                    <i target="passwordModal" class="mdi-action-done right"></i>
                @endif
            </li>
        @empty
            We're sorry! We can't find any wifi network!
        @endforelse
        </ul>
    </div>
