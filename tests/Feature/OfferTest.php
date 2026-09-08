<?php

namespace Feature;

use App\Models\Offer;
use Tests\TestCase;

class OfferTest extends TestCase
{
    public function testOfferStore(): void
    {
        $id = Offer::first()->id;
        $data = [
          "client_reference" => "web-order-9f782b1c",
          "customer_name" => "John Smith",
          "customer_email" => "john@example.com"
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])
            ->postJson(route('offers.reservation', ['offer' => $id]), $data);

        $this->assertSame(201, $response->status());

        $this->assertArrayHasKey('reservation', $response->json());
    }
}
