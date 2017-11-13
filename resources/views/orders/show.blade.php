<?php

?>
<!-- app/views/show.blade.php -->

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
        <div class="container">
            {!! Form::open(['method' => 'get', 'id' => 'search-form', 'route' => ['orders_search']]) !!}
            <div class="row page-header">
                <div class="col-md-2"><b>Search</b></div>
                <div class="col-md-2">
                    {{ Form::select('filter', [
                    'all' => 'All time',
                    'week' => 'Last 7 days',
                    'today' => 'Today'
                    ], isset($filter) ? $filter : null, ['class' => 'form-control']) }}

                </div>
                <div class="col-md-2">
                    {{ Form::text('search_key', isset($serachKey) ? $serachKey : null, array('required|integer', 'placeholder' => 'search by user or product name')) }}
                </div>
                <div class="col-md-2">
                    {!! Form::button('Submit', array('type' => 'submit', 'class' => 'specialButton')) !!}
                </div>
            </div>
            {!! Form::close() !!}

        </div>
        <div class="container">
            <div class="row page-header">
                <div class="col-md-1"><b>Order Id</b></div>
                <div class="col-md-2"><b>Customer</b></div>
                <div class="col-md-2"><b>Product</b></div>
                <div class="col-md-1"><b>Price</b></div>
                <div class="col-md-1"><b>Qty</b></div>
                <div class="col-md-1"><b>Total</b></div>
                <div class="col-md-2"><b>Date</b></div>
                <div class="col-md-1"><b>Edit</b></div>
                <div class="col-md-1"><b>Delete</b></div>
            </div>

                @foreach($orders as $row)
                <div class="row">
                    <div class="col-md-1">{{ $row->order_id }}</div>
                    <div class="col-md-2">{{ isset($row->name) ? $row->name : $row->user->name }}</div>
                    <div class="col-md-2">{{ isset($row->product_name) ? $row->product_name : $row->product->product_name }}</div>
                    <div class="col-md-1">{{ isset($row->product_price) ? $row->product_price : $row->product->product_price }}</div>
                    <div class="col-md-1">{{ $row->order_qty }}</div>
                    <div class="col-md-1">{{ money_format('%(#1n', ($row->order_total)) }}</div>
                    <div class="col-md-2">{{ date_format($row->created_at, "d M Y, h:i A") }}</div>
                    <div class="col-md-1">
                        {!! Form::open(['method' => 'POST', 'route' => ['orders_edit']]) !!}

                        {!! Form::hidden('id', $row->order_id, ['class' => 'form-control']) !!}

                        {!! Form::button('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', array('type' => 'submit', 'class' => 'specialButton')) !!}

                        {!! Form::close() !!}
                    </div>
                    <div class="col-md-1">
                        {!! Form::open(['method' => 'DELETE', 'route' => ['orders_delete'], 'onsubmit' => 'ConfirmDelete()']) !!}

                        {!! Form::hidden('id', $row->order_id, ['class' => 'form-control']) !!}

                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', array('type' => 'submit', 'class' => 'specialButton')) !!}

                        {!! Form::close() !!}
                    </div>
                </div>
                @endforeach
                <div class="row">
                    <div class="col-md-4">
                        &nbsp;
                    </div>
                    <div class="col-md-8">
                        {!! $orders->appends(Input::except('page'))->links() !!}
                    </div>
                </div>
        </div>
    </div>
    <script type="text/javascript">

        function ConfirmDelete()
        {
            var x = confirm("Are you sure you want to delete this order?");
            if (x)
                return true;
            else
                return false;
        }

    </script>