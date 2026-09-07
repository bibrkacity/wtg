<?php

namespace App\Http\FormResponses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

readonly class IndexResponse implements Responsable
{
    public const string ROUTE_NAME = 'properties.index';
    public function __construct(
        protected readonly array $data,
        protected readonly array $filters,
        protected readonly int $total,
    ) {

    }

    public function toResponse($request): JsonResponse
    {

        return new JsonResponse(
            data: $this->toData(),
            status: ResponseAlias::HTTP_OK,
            json: false,
        );
    }

    protected function toData(): array
    {
        $array = [];

        $array['data'] = $this->prepareData($this->data);
        $array['links'] = $this->toLinks();
        $array['meta'] = $this->toMeta();

        return $array;

    }

    protected function toLinks(): array
    {
        $links = [];
        $link = $this->toLink(true);

        $lastPage = $this->filters['per_page'] === 0 ? 1 : (int) ceil($this->total / $this->filters['per_page']);

        $prefix = str_contains($link, '?') ? '&' : '?';

        $links['first'] = $link;
        $links['last'] = $link.$prefix.'page='.$lastPage;

        $links['prev'] = $this->filters['page'] > 1 ? $link.$prefix.'page='.($this->filters['page'] - 1) : null;

        $links['next'] = $this->filters['page'] < $lastPage ? $link.$prefix.'page='.($this->filters['page'] + 1) : null;

        return $links;
    }

    protected function toMeta(): array
    {
        $meta = [];
        $meta['current_page'] = $this->filters['page'];
        $meta['last_page'] = $this->filters['per_page'] === 0 ? 1 : (int) ceil($this->total / $this->filters['per_page']);
        $meta['path'] = route(static::ROUTE_NAME);
        $meta['per_page'] = $this->filters['per_page'];
        $meta['total'] = $this->total;

        return $meta;
    }

    protected function toPagination(): array
    {
        $pagination = [];
        $pagination['current_page'] = $this->filters['page'];
        $pagination['last_page'] = ceil($this->total / $this->filters['per_page']);
        $pagination['per_page'] = $this->filters['per_page'];
        $pagination['total'] = $this->total;

        return $pagination;
    }

    private function toLink(bool $withoutPage = false): string
    {
        $defaults = ['page' => 1, 'per_page' => config('app.default_per_page')];

        $args = [];
        foreach ($this->filters as $name => $value) {
            if ((key_exists($name, $defaults) && $value == $defaults[$name]) || ($withoutPage && $name == 'page')) {
                continue;
            }
            $args[$name] = $value;
        }

        $query = http_build_query($args);
        if ($query !== '') {
            $query = '?'.$query;
        }

        return route(static::ROUTE_NAME).$query;
    }

    private function prepareData(array $data): array
    {
        $preparedData = [];

        foreach ($data as $datum) {

            $datum = (array) $datum;

            $item = [];

            $item['id'] = $datum['id'];
            $item['name'] = $datum['name'];
            $item['code'] = $datum['code'];
            $item['city'] = $datum['city'];

            $item['best_offer'] = [
                'id' => $datum['offer_id'],
                'price' => $datum['offer_price'],
                'currency' => $datum['currency'],
                'check_in' => $datum['check_in'],
                'check_out' => $datum['check_out'],
                'available_units' => $datum['available_units'],
                'expires_at' => $datum['expires_at'],
                'supplier' => $datum['supplier_name'],
            ];
            $preparedData[] = $item;
        }


        return $preparedData;
    }
}
