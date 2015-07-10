<i class=" small mdi-social-person-outline"></i>
Status:<?php
            switch($order->status){
                case 0:
                    echo "This order hasn't been approved yet!";
                    break;
                case 1:
                    echo "This is approved, six and relax waiting for you turn!";
                    break;
                case 2:
                    echo "This order is being prepared!";
                    break;
                case 3:
                    echo "This order is ready!";
                    break;
                case 4:
                    echo "This order has been deleted";
                    break;
                case 5:
                    echo "Waiting for you to start the preparation, reach the drink-maker!";
                    break;
                case 6:
                    echo "You didn't activate the drink-maker in time, click below to re-order!";
                    break;
            }
            ?>
    There are {{$before}} orders before yours
@if($order->status==6)
    <form method="post" action="{{url('orders/'+$order->id+'/requeue')}}">
        <button type="submit" class="btn waves-light waves-effect">Re-order!</button>
    </form>
@endif