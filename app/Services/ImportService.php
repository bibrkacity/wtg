<?php

namespace App\Services;

use App\Jobs\ImportOfferJob;
use App\Models\Import;
use App\Models\Offer;
use App\Models\Property;
use App\Models\Supplier;
use Illuminate\Contracts\Queue\ShouldQueue;
use Throwable;

class ImportService implements ShouldQueue
{
    public function import(array $data): void
    {
        $import = $this->createImport($data);

        $count = Import::query()
            ->where('supplier_id', $import->supplier_id)
            ->where('external_import_id', $import->external_import_id)
            ->count();
        if ($count > 1) {
            $import->error = 'Import already done';
            $import->status = 'failed';
            $import->save();
            return;
        }
        $i = 0;
        foreach ($data['offers'] as $offer) {
            if ($i === 0) {
                $import->status = 'processing';
                $import->save();
            }
            ImportOfferJob::dispatch($offer, $import, $i++);
        }

    }

    public function importOneOffer(array $offer, Import $import, int $i): void
    {
        try {
            $property = $this->getProperty($offer['property'], $import);

            $offer = Offer::updateOrCreate(
                [
                    'supplier_id' => $import->supplier_id,
                    'external_id' => $offer['external_id'],
                ],
                [
                    'property_id' => $property->id,
                    'check_in' => $offer['check_in'],
                    'check_out' => $offer['check_out'],
                    'max_guests' => $offer['max_guests'],
                    'price' => $offer['price'],
                    'currency' => $offer['currency'],
                    'available_units' => $offer['available_units'],
                    'expires_at' => $offer['expires_at'],
                ]
            );

            $import->processed_offers++;

        } catch (Throwable $e) {
            $import->error .= "Error in offer with external_id={$offer['external_id']}: {$e->getMessage()}";
        } finally {
            if (++$i === $import->total_offers) {
                $import->status = 'completed';
            }
            $import->save();
        }
    }

    private function createImport(array $data): Import
    {
        $import = new Import();

        $import->supplier_id = Supplier::query()
            ->where('name', $data['supplier'])
            ->first()
            ->id; // Exists because validation is done in the ImportStoreFormRequest

        $import->external_import_id = $data['external_import_id'];
        $import->sent_at = $data['sent_at'];
        $import->status = 'pending';
        $import->total_offers = count($data['offers']);
        $import->processed_offers = 0;
        $import->save();
        return $import;
    }

    private function getProperty(array $prop, Import $import): Property
    {
        $property = Property::query()
            ->where('code', $prop['code'])
            ->first();

        if (!$property) {
            $prop['supplier_id'] =  $import->supplier_id;
            $property = Property::create($prop);
        }
        return $property;
    }


}
