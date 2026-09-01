<?php

namespace Crm\Product\Repositories;

use Illuminate\Container\Container;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Crm\Attribute\Repositories\AttributeRepository;
use Crm\Attribute\Repositories\AttributeValueRepository;
use Crm\Core\Eloquent\Repository;
use Crm\Product\Contracts\Product;

class ProductRepository extends Repository
{
    /**
     * Searchable fields.
     */
    protected $fieldSearchable = [
        'sku',
        'name',
        'description',
    ];

    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct(
        protected AttributeRepository $attributeRepository,
        protected AttributeValueRepository $attributeValueRepository,
        protected ProductInventoryRepository $productInventoryRepository,
        Container $container
    ) {
        parent::__construct($container);
    }

    /**
     * Specify model class name.
     *
     * @return mixed
     */
    public function model()
    {
        return Product::class;
    }

    /**
     * Create.
     *
     * @return Product
     */
    public function create(array $data)
    {
        $product = parent::create(Arr::except($data, ['entity_type']));

        $this->attributeValueRepository->save(array_merge($data, [
            'entity_id'   => $product->id,
            'entity_type' => 'products',
        ]));

        return $product;
    }

    /**
     * Update.
     *
     * @param  int  $id
     * @param  array  $attribute
     * @return Product
     */
    public function update(array $data, $id, $attributes = [])
    {
        $product = parent::update(Arr::except($data, ['entity_type']), $id);

        $data['entity_type'] = 'products';

        /**
         * If attributes are provided then only save the provided attributes and return.
         */
        if (! empty($attributes)) {
            $conditions = ['entity_type' => 'products'];

            if (isset($data['quick_add'])) {
                $conditions['quick_add'] = 1;
            }

            $attributes = $this->attributeRepository->where($conditions)
                ->whereIn('code', $attributes)
                ->get();

            $this->attributeValueRepository->save(array_merge($data, [
                'entity_id' => $product->id,
            ]), $attributes);

            return $product;
        }

        $this->attributeValueRepository->save(array_merge($data, [
            'entity_id' => $product->id,
        ]));

        return $product;
    }

    /**
     * Save inventories.
     *
     * @param  int  $id
     * @param  ?int  $warehouseId
     * @return void
     */
    public function saveInventories(array $data, $id, $warehouseId = null)
    {
        $productInventories = $this->productInventoryRepository->where('product_id', $id);

        if ($warehouseId) {
            $productInventories = $productInventories->where('warehouse_id', $warehouseId);
        }

        $previousInventoryIds = $productInventories->pluck('id');

        if (isset($data['inventories'])) {
            foreach ($data['inventories'] as $inventoryId => $inventoryData) {
                if (Str::contains($inventoryId, 'inventory_')) {
                    $this->productInventoryRepository->create(array_merge($inventoryData, [
                        'product_id' => $id,
                        'warehouse_id' => $warehouseId,
                    ]));
                } else {
                    if (is_numeric($index = $previousInventoryIds->search($inventoryId))) {
                        $previousInventoryIds->forget($index);
                    }

                    $this->productInventoryRepository->update($inventoryData, $inventoryId);
                }
            }
        }

        foreach ($previousInventoryIds as $inventoryId) {
            $this->productInventoryRepository->delete($inventoryId);
        }
    }

    /**
     * Retrieves customers count based on date.
     *
     * @return int
     */
    public function getProductCount($startDate, $endDate)
    {
        return $this
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->count();
    }

    /**
     * Get inventories grouped by warehouse.
     *
     * @param  int  $id
     * @return array
     */
    public function getInventoriesGroupedByWarehouse($id)
    {
        $product = $this->findOrFail($id);

        $warehouses = [];

        foreach ($product->inventories as $inventory) {
            if (! isset($warehouses[$inventory->warehouse_id])) {
                $warehouses[$inventory->warehouse_id] = [
                    'id' => $inventory->warehouse_id,
                    'name' => $inventory->warehouse->name,
                    'in_stock' => $inventory->in_stock,
                    'allocated' => $inventory->allocated,
                    'on_hand' => $inventory->on_hand,
                ];
            } else {
                $warehouses[$inventory->warehouse_id]['in_stock'] += $inventory->in_stock;
                $warehouses[$inventory->warehouse_id]['allocated'] += $inventory->allocated;
                $warehouses[$inventory->warehouse_id]['on_hand'] += $inventory->on_hand;
            }

            $warehouses[$inventory->warehouse_id]['locations'][] = [
                'id' => $inventory->warehouse_location_id,
                'name' => $inventory->location->name,
                'in_stock' => $inventory->in_stock,
                'allocated' => $inventory->allocated,
                'on_hand' => $inventory->on_hand,
            ];
        }

        return $warehouses;
    }
}
