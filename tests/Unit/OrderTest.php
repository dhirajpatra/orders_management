<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

/**
 * Class OrderTest
 * @package Tests\Unit
 */
class OrderTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * OrderTest constructor.
     */
    public function __construct()
    {
        // We have no need to testing Eloquent
        $this->mock = \Mockery::mock('Eloquent', 'Order');
    }

    /**
     * close mockery
     */
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * create mock user
     */
    /*protected function mockUser()
    {
        $userSettings = User::getDefaultSettings();
        $userSettings['check_status_code'] = false;

        $user = new User(
            [
                'name'          => 'Tester',
                'email'         => 'tester@email.com',
                'username'      => 'tester',
                'name'          => 'tester',
                'password'      => 'password',
                'settings'      => $userSettings,
            ]
        );
        $user->setAttribute('id', 0);
        $this->be($user);
    }*/

    /**
     * @param $method
     * @param $args
     * @return \Illuminate\Http\Response
     * @throws BadMethodCallException
     */
    public function __call($method, $args)
    {
        if (in_array($method, ['get', 'post', 'put', 'patch', 'delete']))
        {
            return $this->call($method, $args[0]);
        }

        throw new BadMethodCallException;
    }

    /**
     * this will test create order
     */
    public function testCreate()
    {
        // by passing authentication
        $this->withoutMiddleware();
        $response = $this->call('POST', 'orders_post', array(
            '_token' => csrf_token(),
        ));
        $this->assertEquals(302, $response->getStatusCode());
        // redirect to same form home url after save the order
        $this->assertRedirectedTo('');
    }

    /**
     * this will test the form validation and save for create order
     */
    public function testCreateOrderFormValidation()
    {
        $this->withoutMiddleware();

        $parameters = array(
            'order_user' =>  '', // change this data as per your DB
            'order_product' => '', // change this datat as per your DB
            'order_qty' => 0
        );
        $this->call('POST', 'orders_post', $parameters);
        // it will fail to save for validation
        $this->assertRedirectedTo('');
    }

    /**
     * this will test order list show function
     */
    public function testShowOrder()
    {
        $this->withoutMiddleware();

        $this->mock
            ->shouldReceive('all')
            ->once();

        $this->app->instance('Order', $this->mock);

        $response = $this->call('GET', 'orders_list');

        $this->assertContains('orders', $response->getContent());
    }

}
