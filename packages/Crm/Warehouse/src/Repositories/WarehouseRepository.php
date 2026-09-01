<?php

namespace Crm\Warehouse\Repositories;

use Illuminate\Container\Container;
use Illuminate\Support\Arr;
use Crm\Attribute\Repositories\AttributeRepository;
use Crm\Attribute\Repositories\AttributeValueRepository;
use Crm\Core\Eloquent\Repository;
use Crm\Warehouse\Contracts\Warehouse;

class WarehouseRepository extends Repository
{
    /**
     * Searchable fields.
     */
    protected $fieldSearchable = [
        'name',
        'contact_name',
        'contact_emails',
        'contact_numbers',
        'contact_address',
    ];

    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct(
        protected AttributeRepository $attributeRepository,
        protected AttributeValueRepository $attributeValueRepository,
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
        return Warehouse::class;
    }

    /**
     * Create.
     *
     * @return Warehouse
     */
    public function create(array $data)
    {
        $warehouse = parent::create(Arr::except($data, ['entity_type']));

        $this->attributeValueRepository->save(array_merge($data, [
            'entity_id'   => $warehouse->id,
            'entity_type' => 'warehouses',
        ]));

        return $warehouse;
    }

    /**
     * Update.
     *
     * @param  int  $id
     * @param  array  $attribute
     * @return Warehouse
     */
    public function update(array $data, $id, $attributes = [])
    {
        $warehouse = parent::update(Arr::except($data, ['entity_type']), $id);

        $data['entity_type'] = 'warehouses';

        /**
         * If attributes are provided then only save the provided attributes and return.
         */
        if (! empty($attributes)) {
            $conditions = ['entity_type' => 'warehouses'];

            if (isset($data['quick_add'])) {
                $conditions['quick_add'] = 1;
            }

            $attributes = $this->attributeRepository->where($conditions)
                ->whereIn('code', $attributes)
                ->get();

            $this->attributeValueRepository->save(array_merge($data, [
                'entity_id' => $warehouse->id,
            ]), $attributes);

            return $warehouse;
        }

        $this->attributeValueRepository->save(array_merge($data, [
            'entity_id' => $warehouse->id,
        ]));

        return $warehouse;
    }
}
