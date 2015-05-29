
@if (Session::has('flash_notification.message'))
    <div class="row dismissable">
        <div class="col offset-s1 s10 card-panel {{Session::get('flash_notification.level') }}">
            <div class="row">
                <i class="col s1 small mdi-{{Session::get('flash_notification.icon')}}"></i>
                <span class="col s10">{{ Session::get('flash_notification.message') }}</span>
                <i onclick="$(this).closest('div.dismissable').hide('slow');" class="dismisser col s1 small mdi-content-clear"></i>
            </div>
        </div>
    </div>
@endif
{{--{{Session::forget('flash_notification.level')}}--}}
{{--{{Session::forget('flash_notification.icon')}}--}}
{{--{{Session::forget('flash_notification.message')}}--}}