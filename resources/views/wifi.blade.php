
    <div class="col s10 offset-s1" >
        <div align="center">
            <h5>WiFi networks</h5>
        </div>
        <ul class="collection">
        @forelse($wifi as $w)
            <li class="collection-item">
                {{$w}}
                    <a href="#"><i class="mdi-device-signal-wifi-4-bar right"></i></a>
            </li>
        @empty
            We're sorry! We can't find any wifi network!
        @endforelse
        </ul>
    </div>