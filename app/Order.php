<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;

/**
 * Class Order
 * @package App
 */
class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    private $discountQty = 3;
    private $discount = .20; // 20%
    //public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id', 'order_product_id', 'order_qty', 'order_user_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        try {
            return $this->hasOne('App\User', 'id', 'order_user_id');
        } catch (\Exception $e) {
            Flash::message('Something went wrong' . $e->getMessage());
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        try {
            return $this->hasOne('App\Product', 'product_id', 'order_product_id');
        } catch (\Exception $e) {
            Flash::message('Something went wrong' . $e->getMessage());
        }
    }


    /**
     * save a order post
     * @param $input
     * @return int|mixed
     */
    public function saveOrderPost($input)
    {
        try {
            $data = new Order();

            $data->order_product_id = $input['order_product_id'];
            $data->order_user_id = $input['order_user_id'];
            $data->order_qty = $input['order_qty'];
            // calculate the total along with business rule
            $total = $this->businessRuleDiscount($input);

            if($total == -1) { // stock is not there
                return 0;
            }

            $data->order_total = $total;

            if ($data->save()) {
                // need to decrease the stock on product
                $product = Product::find($input['order_product_id']);
                $affected = $product->decrement('product_stock', $input['order_qty']);

                return $data->id;
            } else {
                return 0;
            }
        } catch (\Exception $e) {
            Flash::message('Something went wrong' . $e->getMessage());
        }
    }

    /**
     * update order
     * @param $input
     * @return int|mixed
     */
    public function updateOrder($input)
    {   
        try {
            $affected = Order::where('order_id', $input['id'])
            ->update(['order_product_id' => $input['order_product_id'],
                'order_user_id' => $input['order_user_id'],
                'order_qty' => $input['order_qty']]);

            if($affected > 0) {
                // need to adjust the stock
                if($input['previous_product_id'] != $input['order_product_id']) { // different product choosen
                    $product = Product::find($input['previous_product_id']);
                    $affected = $product->increment('product_stock', $input['previous_order_qty']);

                    $product = Product::find($input['order_product_id']);
                    $affected = $product->decrement('product_stock', $input['order_qty']);

                } elseif($input['previous_order_qty'] > $input['order_qty']) {
                    $product = Product::find($input['order_product_id']);
                    $affected = $product->increment('product_stock', ($input['previous_order_qty'] - $input['order_qty']));

                } elseif($input['previous_order_qty'] < $input['order_qty']) {
                    $product = Product::find($input['order_product_id']);
                    $affected = $product->decrement('product_stock', ($input['order_qty'] - $input['previous_order_qty']));
                }

                return true;
            } else {
                return false;
            }

        } catch (\Exception $e) {
            Flash::message('Something went wrong' . $e->getMessage());
        }
    }

    /**
     * delete an order
     * @param $input
     * @return bool
     */
    public function deleteOrder($input)
    {
        try {
            $order = Order::find($input['id']);
            $order->deleted = 1;

            if($order->save()) {
                return true;
            } else {
                return false;
            }

        } catch (\Exception $e) {
            Flash::message('Something went wrong' . $e->getMessage());
        }
    }

    /**
     * this will check whether the stock is there and
     * also check the special business rule eg. discount
     * @param $data
     * @return int
     */
    private function businessRuleDiscount($data)
    {
        $total = -1;
        if($data['order_qty'] >= $this->discountQty) {
            // check if it is Pepsi Cola
            $productDetails = Product::where('product_id', $data['order_product_id'])
                ->where('product_stock', '>=', $data['order_qty'])
                ->first()
                ->toArray();

            if(!empty($productDetails) && ($productDetails['product_name'] == 'Pepsi Cola')) {
                $totalBeforeDiscount = ($productDetails['product_price'] * $data['order_qty']);
                $total = $totalBeforeDiscount - ($this->discount * $totalBeforeDiscount);
            } else {
                $total = ($productDetails['product_price'] * $data['order_qty']);
            }

        } else {
            $productDetails = Product::where('product_id', $data['order_product_id'])
                ->where('product_stock', '>=', $data['order_qty'])
                ->first()
                ->toArray();

            if(!empty($productDetails)) {
                $total = ($productDetails['product_price'] * $data['order_qty']);
            }

        }

        return $total;
    }
}
