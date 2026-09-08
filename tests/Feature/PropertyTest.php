<?php

namespace Feature;

use Tests\TestCase;

class PropertyTest extends TestCase
{
    public function testPropertyIndex(): void
    {

        $data = [
            'city' => 'Barcelona',
            'check_in' => '2026-10-10',
            'check_out' => '2026-10-15',
            'guests' => 2,
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])
            ->getJson(route('properties.index').'?'.http_build_query($data));

        $this->assertSame(200, $response->status());

        $this->assertArrayHasKey('data', $response->json());
    }
}
