<?php

namespace Feature;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ImportTest extends TestCase
{
    /**
     * @throws ConnectionException
     */
    public function testImportStore(): void
    {

        DB::connection('mysql_testing')->table('reservations')->delete();
        DB::connection('mysql_testing')->table('offers')->delete();
        DB::connection('mysql_testing')->table('properties')->delete();
        DB::connection('mysql_testing')->table('imports')->delete();

        $json = <<<ERT
{
  "supplier": "supplier-a",
  "external_import_id": "import-2026-09-01-001",
  "sent_at": "2026-09-01T10:00:00Z",
  "offers": [
    {
      "external_id": "offer-a-10001",
      "property": {
        "code": "BCN-0001",
        "name": "Apartment near Sagrada Familia",
        "city": "Barcelona"
      },
      "check_in": "2026-10-10",
      "check_out": "2026-10-15",
      "max_guests": 4,
      "price": 72500,
      "currency": "EUR",
      "available_units": 2,
      "expires_at": "2026-09-10T23:59:59Z"
    }
  ]
}

ERT;

        $data = json_decode($json, true);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])
        ->postJson(route('imports.store'), $data);

        $this->assertSame(202, $response->status());
        $this->assertArrayHasKey('data', $response->json());

        sleep(1);

        $this->assertDatabaseCount('imports', 1, 'mysql_testing');
        $this->assertDatabaseCount('offers', 1, 'mysql_testing');
        $this->assertDatabaseCount('properties', 1, 'mysql_testing');

    }

    public function testImportShow(): void
    {
        $id = DB::connection('mysql_testing')->table('imports')->first()->id;

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])
            ->getJson(route('imports.show', ['import' => $id]));

        $this->assertSame(200, $response->status());

        $this->assertArrayHasKey('data', $response->json());
    }
}
