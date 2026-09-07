<?php

namespace App\Jobs;

use App\Models\Import;
use App\Exceptions\ImportService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ImportOfferJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly array $offer,
        private readonly Import $import,
        private readonly int $i
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(ImportService $importService): void
    {
        $importService->importOneOffer($this->offer, $this->import, $this->i);
    }
}
