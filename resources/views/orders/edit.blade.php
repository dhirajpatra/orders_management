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

        @if(isset($success))
            <div class="alert alert-success"> {{ $success }} </div>
        @endif
        <div class="page-header">&nbsp;</div>
        <div class="row">
            <div class="col-md-offset-3 col-md-6">
                <h1>Update Order</h1>


            {!! Form::open(['method' => 'PUT', 'route' => ['orders_update']]) !!}

            <!-- if there are login errors, show them here -->
                {{ Form::hidden('id', $order['order_id']) }}
                {{ Form::hidden('previous_product_id', $order['order_product_id']) }}
                {{ Form::hidden('previous_order_qty', $order['order_qty']) }}

                <div class="form-group">
                    {{ Form::label('order_user', 'User ') }}
                    {{ Form::select('order_user', $users, $order['order_user_id'], ['class' => 'form-control']) }}
                </div>

                <div class="form-group">
                    {{ Form::label('order_product', 'Product ') }}
                    {{ Form::select('order_product', $products, $order['order_product_id'], ['class' => 'form-control']) }}
                </div>

                <div class="form-group">
                    {{ Form::label('order_qty', 'Quantity ') }}
                    <br />
                    {{ Form::text('order_qty', $order['order_qty'], array('required|integer')) }}
                </div>

                <div class="form-group">
                    {{ Form::submit('Submit') }}
                </div>
                {{ Form::close() }}

                @stop
            </div>
        </div>
    </div>