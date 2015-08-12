
    <div class="col s10 offset-s1" >
        <div align="center">
            <h5>WiFi networks</h5>
        </div>
        <ul class="collection">
        @forelse($wifi as $w)
            <li class="collection-item">
                {{$w}}
                    <a href="#passwordModal" class="modal-trigger"><i class="mdi-device-signal-wifi-4-bar right"></i></a>
            </li>
        @empty
            We're sorry! We can't find any wifi network!
        @endforelse
        </ul>
    </div>
    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <h4>Insert password</h4>
            <input type="password" id="password" name="wifi_password">
        </div>
        <div class="modal-footer">
            <a href="#!" onclick="Materialize.toast('The machine will try to connect to the specified network as soon as you save the settings!\n The website may become unresponsive. In case of error the macine will fallback to this connection.',25000,'rounded')" class=" modal-action modal-close waves-effect waves-green btn-flat">Connect</a>
        </div>
    </div>