<?php declare(strict_types=1);

namespace Mxncommerce\ChannelConnector\test;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_order_created_test()
    {
//        $response = $this->graphQL(/** @lang GraphQL */ '
//            mutation {
//              createOrder(input: {
//                channel_order_number: "V00397658"
//                currency_code: "KRW"
//                sub_total_order_amount: 14500
//                total_discount_amount:1500
//                total_tax_amount: 1500
//                total_shipping_amount: 3000
//                total_order_amount: 261100
//                customer_name: "홍길동"
//                customer_email: "exceedweb@gmail.com"
//                customer_phone: "010-9999-0000"
//                s_name: "홍길동"
//                s_address_1: "지구"
//                s_address_2: "하늘 아래 어떤곳"
//                s_city: "서울"
//                s_province: "성동구"
//                s_country_code: "KOR"
//                s_customs_clearance_code: "P200011980070"
//                b_name: "홍길동"
//                b_address_1: "지구"
//                b_city: "서울"
//                b_country_code: "KOR"
//                bundling: NA
//                orderItems: [
//                  {
//                    variant_id: 12
//                    product_id: 1009
//                    channel_order_number: "V00397658"
//                    quantity: 1
//                    c_item_id: "1911684"
//#                    c_item_product_id: "FAVPR000001"
//                    c_item_variant_id: "3003797032226952"
//                    c_item_sku: "FAVSKU000001"
//                    c_item_title: "[자케]JAKKE_W_DIANA DRESS_CREAM"
//                    c_item_options: "{\"option1\":{\"name\":\"color\",\"value\":\"LawnGreen\"},\"option2\":{\"name\":\"size\",\"value\":\"M\"}}"
//                    c_item_currency_code: "KRW"
//                    c_item_sales_price: 241100
//                    # c_item_msrp: 22500
//                    # c_item_discount: Float
//                    # c_item_tax_amount: Float
//                    # c_item_shipping_amount: Float
//                    c_item_supply_currency_code: "KRW"
//                    c_item_recorded_at: "2022-05-12 00:00:00"
//                  }
//                ]
//              }) {
//                id
//                channel_order_number
//              }
//            }
//            '
//        );
//        dd($response);
        $this->assertTrue(true);
    }
}
