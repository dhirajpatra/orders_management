<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Crypt;
use Auth;
use Mail;
use Hash;
use Flash;
use App\User;
use App\Order;
use App\Product;
use Mockery\Exception;

class OrdersController extends Controller
{
    protected $mailer;

    /**
     * JobpostController constructor.
     */
    public function __construct(Mailer $mailer)
    {
        try {
            $this->mailer = $mailer;
            Redirect('guest');
        } catch (Exception $e) {
            Flash::message('Something went wrong' . $e->getMessage());
        }
    }

    /**
     * this will show the order creation form
     * @return mixed
     */
    public function showOrderForm()
    {
        try {

            // get all users for select
            $users = User::all()->pluck('name', 'id');

            // get all products for select which has stock
            $product = new Product();
            $products = $product->getAllProducts()->pluck('product_name', 'product_id');

            // show job post form
            return View::make('orders.orders', compact('users', 'products'));
        } catch (Exception $e) {
            Flash::message('Something went wrong ' . $e->getMessage());
        }
    }

    /**
     * this will process the order and send mail
     * @param Request $request
     */
    public function doOrder(Request $request)
    {
        try {

            // validation rules for form
            $rules = array(
                'order_user' => 'required',
                'order_product' => 'required',
                'order_qty' => 'required|integer|min:1'
            );

            // run the validation rules on the inputs from the form
            $validator = Validator::make(Input::only('order_user', 'order_product', 'order_qty'), $rules);

            // if the validator fails, redirect back to the form
            if ($validator->fails()) {

                return Redirect::back()->withInput()->withErrors($validator);
            } else {

                // get the user details from auth and db
                $userId = Input::get('order_user');
                $productId = Input::get('order_product');
                $qty = Input::get('order_qty');

                // save data to jobposting
                $orderObj = new Order();
                $jobId = $orderObj->saveOrderPost(
                    array(
                        'order_product_id' => $productId,
                        'order_user_id' => $userId,
                        'order_qty' => $qty
                    )
                );
                
            }

            $message = 'Order successfully saved';
            $request->session()->flash('alert-success', $message);

            return Redirect::route('orders');
        } catch (Exception $e) {
            Flash::message('Something went wrong ' . $e->getMessage());
        }
    }

    /**
     * This will fetch all orders
     * @return mixed
     */
    public function show()
    {
        try {
            $orders = Order::with('product', 'user')
                ->where('deleted', 0)
                ->orderBy('order_id', 'desc')
                ->paginate($_ENV['PAGINATION']);
            //echo '<pre>'; print_r($orders); exit;

            return View::make('orders.show')
                ->with('orders', $orders);

        } catch (Exception $e) {
            Flash::message('Something went wrong ' . $e->getMessage());
        }
    }

    /**
     * To edit a order
     * @param Request $request
     * @return mixed
     */
    public function edit(Request $request)
    {
        try {
            $orderId = Input::get('id');
            $order = Order::where('order_id', $orderId)
                ->first()
                ->toArray();

            // get all users for select
            $users = User::all()->pluck('name', 'id');

            // get all products for select which has stock
            $product = new Product();
            $products = $product->getAllProducts()->pluck('product_name', 'product_id');

            // show job post form
            return View::make('orders.edit', compact('users', 'products', 'order'));
        } catch (Exception $e) {
            Flash::message('Something went wrong ' . $e->getMessage());
        }
    }

    /**
     * this will process the order and send mail
     * @param Request $request
     */
    public function updateOrder(Request $request)
    {
        try {

            // validation rules for form
            $rules = array(
                'order_user' => 'required',
                'order_product' => 'required',
                'order_qty' => 'required|integer'
            );

            // run the validation rules on the inputs from the form
            $validator = Validator::make(Input::only('order_user', 'order_product', 'order_qty'), $rules);

            // if the validator fails, redirect back to the form
            if ($validator->fails()) {
                return Redirect::back()->withInput()->withErrors($validator);
            } else {

                // get the user details from auth and db
                $userId = Input::get('order_user');
                $productId = Input::get('order_product');
                $qty = Input::get('order_qty');
                $id = Input::get('id');
                $previous_product_id = Input::get('previous_product_id');
                $previous_order_qty = Input::get('previous_order_qty');

                // save data to jobposting
                $orderObj = new Order();
                $result = $orderObj->updateOrder(
                    [
                        'order_product_id' => $userId,
                        'order_user_id' => $productId,
                        'order_qty' => $qty,
                        'id' => $id,
                        'previous_product_id' => $previous_product_id,
                        'previous_order_qty' => $previous_order_qty
                    ]
                );

            }

            if($result == true) {
                $message = 'Order successfully saved';
            } else {
                $message = 'Order couldnt saved';
            }

            return Redirect::route('orders_list')->with('success', $message);
        } catch (Exception $e) {
            Flash::message('Something went wrong ' . $e->getMessage());
        }
    }

    /**
     * delete an order
     * @param Request $request
     * @return mixed
     */
    public function deleteOrder(Request $request)
    {
        try {

            // get the user details from auth and db
            $id = Input::get('id');

            // save data to jobposting
            $orderObj = new Order();
            $result = $orderObj->deleteOrder(
                [
                    'id' => $id
                ]
            );

            if($result == true) {
                $message = 'Order successfully deleted';
            } else {
                $message = 'Order couldnt be deleted';
            }

            return Redirect::route('orders_list')->with('success', $message);
        } catch (Exception $e) {
            Flash::message('Something went wrong ' . $e->getMessage());
        }
    }

    /**
     * fetch all orders with search conditions
     * @param Request $request
     * @return mixed
     */
    public function search(Request $request)
    {
        try {
            $orders = array();
            $filter = Input::get('filter');
            $searchKey = Input::get('search_key');

            if($filter != '') {
                switch ($filter) {
                    case 'all':
                        if($searchKey != '') {
                            $sql = "select * from orders o 
inner join users u on u.id = o.order_user_id 
inner join products p on p.product_id = o.order_product_id 
where o.deleted = 0
and (p.product_name like '%".$searchKey."%'
or u.username like '%".$searchKey."%') 
union 
select * from orders o 
inner join users u on u.id = o.order_user_id 
inner join products p on p.product_id = o.order_product_id 
where  o.deleted = 0 
and (u.username like '%".$searchKey."%'
or p.product_name like '%".$searchKey."%')
order by order_id desc";
                            $orders = DB::select($sql);
                            $orders = $this->arrayPaginator($orders, $request);

                        } else {
                            $sql = "select * from orders o 
inner join users u on u.id = o.order_user_id 
inner join products p on p.product_id = o.order_product_id 
where o.deleted = 0 order by o.order_id desc";
                            $orders = DB::select($sql);
                            $orders = $this->arrayPaginator($orders, $request);
                        }

                        break;
                    case 'week':
                        if($searchKey != '') {
                            $sql = "select * from orders o 
inner join users u on u.id = o.order_user_id 
inner join products p on p.product_id = o.order_product_id 
where o.deleted = 0
and o.created_at > DATE_SUB(NOW(), INTERVAL 7 DAY)
and (p.product_name like '%".$searchKey."%'
or u.username like '%".$searchKey."%') 
union
select * from orders o 
inner join users u on u.id = o.order_user_id 
inner join products p on p.product_id = o.order_product_id 
where  o.deleted = 0 
and o.created_at > DATE_SUB(NOW(), INTERVAL 7 DAY)
and (u.username like '%".$searchKey."%'
or p.product_name like '%".$searchKey."%')
order by order_id desc";
                            $orders = DB::select($sql);
                            $orders = $this->arrayPaginator($orders, $request);

                        } else {
                            $sql = "select * from orders o 
inner join users u on u.id = o.order_user_id 
inner join products p on p.product_id = o.order_product_id 
where o.deleted = 0
and o.created_at > DATE_SUB(NOW(), INTERVAL 7 DAY)
order by o.order_id desc";
                            $orders = DB::select($sql);
                            $orders = $this->arrayPaginator($orders, $request);
                        }

                        break;
                    case 'today':
                        if($searchKey != '') {
                            $sql = "select * from orders o 
inner join users u on u.id = o.order_user_id 
inner join products p on p.product_id = o.order_product_id 
where o.deleted = 0
and DATE(o.created_at) = CURDATE()
and (p.product_name like '%".$searchKey."%'
or u.username like '%".$searchKey."%') 
union 
select * from orders o 
inner join users u on u.id = o.order_user_id 
inner join products p on p.product_id = o.order_product_id 
where  o.deleted = 0 
and DATE(o.created_at) = CURDATE()
and (u.username like '%".$searchKey."%'
or p.product_name like '%".$searchKey."%')
order by order_id desc";
                            $orders = DB::select($sql);
                            $orders = $this->arrayPaginator($orders, $request);

                        } else {
                            $sql = "select * from orders o 
inner join users u on u.id = o.order_user_id 
inner join products p on p.product_id = o.order_product_id 
where o.deleted = 0
and DATE(o.created_at) = CURDATE()
order by o.order_id desc";
                            $orders = DB::select($sql);
                            $orders = $this->arrayPaginator($orders, $request);
                        }

                        break;
                }
            }
            //echo $sql;
            //echo '<pre>'; print_r($orders); exit;

            return view('orders.show')->with(['orders' => $orders, 'filter' => $filter, 'serachKey' => $searchKey]);
        } catch (Exception $e) {
            Flash::message('Something went wrong ' . $e->getMessage());
        }
    }

    /**
     * customize paginator
     * @param $array
     * @param $request
     * @return LengthAwarePaginator
     */
    private function arrayPaginator($array, $request)
    {
        $page = Input::get('page', 1);
        $perPage = $_ENV['PAGINATION'];
        $offset = ($page * $perPage) - $perPage;

        return new \Illuminate\Pagination\LengthAwarePaginator(array_slice($array, $offset, $perPage, true), count($array), $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]);
    }
}
