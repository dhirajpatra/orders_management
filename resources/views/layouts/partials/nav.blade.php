<?php

?>
<style type="text/css">
    .divider-vertical {
    padding-top: 14px;
    padding-bottom: 14px;
}
</style>
<nav class="navbar navbar-inverse navbar-default navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <!-- <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar">Orders</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

        </div> -->

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            @if(Auth::user())
            <ul class="nav navbar-nav">
                <li class="active">{{ link_to_route('orders', 'Orders Management', [], ['class' => 'navbar-brand']) }}</li>
                <li class="divider-vertical">&#124;</li>
                <li><a href="/orders_list">Orders List</a></li>
                <li class="divider-vertical">&#124;</li>
                <li><a href="/orders">Create Order</a></li>
            </ul>
            @endif
            <ul class="nav navbar-nav navbar-right">
                @if(Auth::user())
                    <li class="dropdown">

                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            {{ Auth::user()->username }}<span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <!--li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li-->
                            <li class="divider"></li>
                            <li>{{ link_to_route('logout', 'Sign Out') }}</li>
                        </ul>

                    </li>
                @else
                    <li>{{  link_to_route('login', 'Log In')  }}</li>
                @endif
            </ul>
        </div>
    </div>
</nav>