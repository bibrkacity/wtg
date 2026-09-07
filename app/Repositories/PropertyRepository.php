<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class PropertyRepository
{
    private string $sql;

    public function ApiResponseData(array $filters): array
    {
        $this->sql = $this->createSqlQuery($filters);
        return [
            'total' => $this->totalCount(),
            'data' => $this->pageData($filters),
        ];
    }
    public function pageData($filters): array
    {
        $sql = $this->sql." LIMIT ".($filters['page'] - 1) * $filters['per_page'].", ".$filters['per_page'];
        return DB::select($sql);
    }

    public function totalCount(): int
    {
        $sql = preg_replace('/SELECT(.|\n)+?FROM properties/', 'SELECT COUNT(properties.id) n FROM properties', $this->sql);

        $result = DB::select($sql);
        return $result[0]->n;
    }

    private function createSqlQuery(array $filters): string
    {
        /**
         * $filters was validated in PropertyIndexFormRequest, so we may trust it and
         * use it directly in the query
         */


        $sql = "
    SELECT
        properties.id, properties.name, properties.code, properties.city,
        suppliers.name AS supplier_name,
        offers.id AS offer_id,
        offers.price AS offer_price,
        offers.currency,
        offers.check_in,
        offers.check_out,
        offers.max_guests,
        offers.available_units,
        offers.expires_at
    FROM properties
    INNER JOIN offers
        ON offers.property_id = properties.id
    INNER JOIN (
        SELECT
            property_id,
            MIN(price) AS min_price
        FROM offers
        WHERE expires_at > NOW()
            AND available_units > 0
            AND max_guests >= ".$filters['guests']."
            AND check_in <= '".$filters['check_in']."'
            AND check_out >= '".$filters['check_out']."'
        GROUP BY property_id
    ) cheapest_offers
        ON (cheapest_offers.property_id = offers.property_id
            AND cheapest_offers.min_price = offers.price)

    INNER JOIN suppliers
        ON offers.supplier_id = suppliers.id
";

        if ($filters['city']) {
            $sql .= "WHERE properties.city = '".addslashes($filters['city'])."'";
        }
        return $sql;
    }

}
