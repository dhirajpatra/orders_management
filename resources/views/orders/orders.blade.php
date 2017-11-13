<?php

?>
<!-- app/views/login.blade.php -->

@extends('layouts.default')

@section('content')
    <br clear="all">
    <div id="container">
        <div style="margin-top: 40px; color: red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>

        <div class="flash-message">
            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                @if(Session::has('alert-' . $msg))

                    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                @endif
            @endforeach
        </div> <!-- end .flash-message -->
        <div class="page-header">&nbsp;</div>
        <div class="row">
            <div class="col-md-offset-3 col-md-6">
                <h1>Add Order</h1>


            {{ Form::open(['route' => 'orders_post', 'class' => 'form']) }}

            <!-- if there are login errors, show them here -->

                <div class="form-group">
                    {{ Form::label('order_user', 'User ') }}                    
                    {{ Form::select('order_user', $users, null, ['class' => 'form-control']) }}
                </div>

                <div class="form-group">
                    {{ Form::label('order_product', 'Product ') }}
                    {{ Form::select('order_product', $products, null, ['class' => 'form-control']) }}
                </div>

                <div class="form-group">
                    {{ Form::label('order_qty', 'Quantity ') }}
                    <br />
                    {{ Form::text('order_qty', null, array('required|integer', 'placeholder' => 'order quantity')) }}
                </div>

                <div class="form-group">
                    {{ Form::submit('Submit') }}
                </div>
                {{ Form::close() }}

                @stop
            </div>
        </div>
    </div>