
    <div class="col s10 offset-s1" >
        <div align="center">
            <h5>WiFi networks</h5>
        </div>
        <ul class="collection">
            <script>
                $(document).ready(function () {
                   $('.modal-trigger').leanModal();
                });
            </script>
        @forelse($wifi as $w)
            <li class="collection-item modal-trigger" style="cursor: pointer" data-target="passwordModal" >
                {{$w}}
                @if($w!=$settings["wifi"])
                    <i data-target="passwordModal" style="color: dodgerblue;" class="mdi-device-signal-wifi-4-bar right"></i>
                @else
                    <i data-target="passwordModal" class ="mdi-action-done right"></i>
                @endif
            </li>
        @empty
            We're sorry! We can't find any wifi network!
        @endforelse
        </ul>
    </div>
