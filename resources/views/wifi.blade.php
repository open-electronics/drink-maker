
    <div class="col s10 offset-s1" >
        <div align="center">
            <h5>WiFi networks</h5>
        </div>
		<script>
                $(document).ready(function () {
                	console.log("I'm ready already");
                	$('.modal-trigger').leanModal();
                	$('.modal-trigger').click(function () {
                		console.log(this.getAttribute('data-network'));
                		window.network = this.getAttribute('data-network');
                	});
                });
		</script>
        <ul class="collection">
        @forelse($wifi as $w)
            <li class="collection-item {{$w!=$settings['wifi']?'modal-trigger':''}}" style="cursor: pointer" data-target="passwordModal" data-network="{{$w}}" >
                {{$w}}
                @if($w!=$settings["wifi"])
                    <i data-target="passwordModal" style="color: dodgerblue;" class="mdi-device-signal-wifi-4-bar right"></i>
                @else
                    <i class ="mdi-action-done right"></i>
                @endif
            </li>
        @empty
            We're sorry! We can't find any wifi network!
        @endforelse
        </ul>
    </div>
