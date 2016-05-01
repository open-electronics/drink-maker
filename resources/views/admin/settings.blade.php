
    <form id="form" action="{{url('configure')}}" method="POST">
        <input type="hidden" id="ssid" name="ssid" value="{{$settings["wifi"]}}">
        <input type="hidden" id="wifi_password" name="wifi_password" value="">
        <div class="row">
            <div class="col offset-s1 s5 input-field">
                <input type="text" value="{{$settings['username']}}" name="username" id="username" >
                <label for="username">Username</label>
            </div>
            <div class="col offset-s1 s5 input-field">
                <input type="password" name="old_psw" id="old_psw" >
                <label for="old_psw">Old password</label>
            </div>
        </div>
        <div class="row">
            <div class="col offset-s1 s5 input-field">
                <input type="password" name="new_psw" id="new_psw" >
                <label for="new_psw">New password</label>
            </div>
            <div class="col offset-s1 s5 input-field">
                <input type="password" name="re_new_psw" id="re_new_psw" >
                <label for="re_new_psw">Repeat new password</label>
            </div>
        </div>
        <div class="row">
            <div class="col offset-s1 s5 input-field">
                <input type="number" min="15" value="{{$settings['timeout_time']}}" max="3600" name="timeout_time" id="timeout_time" >
                <label for="timeout_time">Timeout time(seconds)</label>
            </div>
        </div>
        <div class="row">
            <div class="col offset-s1 s5 ">
                <label>Start method</label>
                <select name="start_method" class="browser-default">
                    <option value="0">Auto</option>
                    <option value="1">Button</option>
                    <option value="2">Coin</option>
                </select>
            </div>
            <div class="col offset-s1 s5 ">
                <label>Default order status</label>
                <select name="initial_status" class="browser-default">
                    <option value="0">To be approved</option>
                    <option value="1">Approved</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col offset-s1 s5">
                <div class="switch">
                    <label>Enable lights: </label>
                    <label>
                        Off
                        <input type="checkbox" {{$settings['has_lights']?'checked':'unchecked'}} name="has_lights" id="has_lights" >
                        <span class="lever"></span>
                        On
                    </label>
                </div>
            </div>
            <div class="col offset-s1 s5 ">
                <div class="switch">
                <label>Play notification sounds: </label>
                    <label>
                        Off
                        <input type="checkbox" {{$settings['play_sounds']?'checked':'unchecked'}} name="play_sounds" id="play_sounds" >
                        <span class="lever"></span>
                        On
                    </label>
                </div>
            </div>
        </div>
        <script>
            function doPool(){
                $.get("wifi", function(response){
                    $("#list").html(response);
                    setTimeout(doPool, 8000);
                });
            }
            doPool();
        </script>
        <div id = "warner" style="display: none" class="row">
            <div class="col s10 offset-s1">
                <div class="card-panel yellow">
                    The machine will try to connect to the specified network as soon as you save the settings!\n The website may become unresponsive. In case of error the macine will fallback to this connection.
                </div>
            </div>
        </div>
        <div class="row" id = "list">
        @include('wifi')
        </div>
        <div class="row">
            <div class=" col offset-s8 s3">
                <script>
                	$(document).ready(function () {
                		$("#submit").click(function () {
                			event.preventDefault();
                			if ($("#ssid").val() == "") {
                				Materialize.toast('Choose a wifi network!', 3000, 'rounded');
                			} else {
                				$("#form").submit();
                			}
                		});
                		$("#wifi-connect").click(function () {
                			$("#warner").show();
                			$("#wifi_password").val($("#tmp_wifi_password").val());
                			$("#ssid").val(window.network);
                		});
                		$("#wifi-cancel").click(function () {
                			if ($("#wifi_password").val() == "") {
                				$("#warner").hide();
                			}
                			$("#tmp_wifi_password").val('');
                			window.network = "";
                		});
                	});
                </script>
                <button id="submit" type="submit" class="btn waves-effect waves-light">
                    <i class="mdi-content-send right"></i>
                    Save
                </button>
            </div>
        </div>
    </form>
    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <h4>Insert password</h4>
            <div class="row">
                <div class="col s10 offset-s1 input-field">
                    <input type="password" id="tmp_wifi_password" name="tmp_wifi_password">
                    <label for="password">WiFi passowrd:</label>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#" id="wifi-connect" class="modal-close waves-effect waves-green btn-flat">Connect</a>
            <a href="#" id="wifi-cancel" class="modal-close waves-effect waves-green btn-flat">Cancel</a>
        </div>
    </div>
