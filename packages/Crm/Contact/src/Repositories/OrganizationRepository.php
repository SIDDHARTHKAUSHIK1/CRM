<?php

namespace Crm\Contact\Repositories;

use Illuminate\Container\Container;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Crm\Attribute\Repositories\AttributeRepository;
use Crm\Attribute\Repositories\AttributeValueRepository;
use Crm\Contact\Contracts\Organization;
use Crm\Core\Eloquent\Repository;

class OrganizationRepository extends Repository
{
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
        return Organization::class;
    }

    /**
     * Create.
     *
     * @return Organization
     */
    public function create(array $data)
    {
        if (isset($data['user_id'])) {
            $data['user_id'] = $data['user_id'] ?: null;
        }

        $organization = parent::create(Arr::except($data, ['entity_type']));

        $this->attributeValueRepository->save(array_merge($data, [
            'entity_id'   => $organization->id,
            'entity_type' => 'organizations',
        ]));

        return $organization;
    }

    /**
     * Update.
     *
     * @param  int  $id
     * @param  array  $attribute
     * @return Organization
     */
    public function update(array $data, $id, $attributes = [])
    {
        if (isset($data['user_id'])) {
            $data['user_id'] = $data['user_id'] ?: null;
        }

        $organization = parent::update(Arr::except($data, ['entity_type']), $id);

        $data['entity_type'] = 'organizations';

        /**
         * If attributes are provided then only save the provided attributes and return.
         */
        if (! empty($attributes)) {
            $conditions = ['entity_type' => 'organizations'];

            if (isset($data['quick_add'])) {
                $conditions['quick_add'] = 1;
            }

            $attributes = $this->attributeRepository->where($conditions)
                ->whereIn('code', $attributes)
                ->get();

            $this->attributeValueRepository->save(array_merge($data, [
                'entity_id' => $organization->id,
            ]), $attributes);

            return $organization;
        }

        $this->attributeValueRepository->save(array_merge($data, [
            'entity_id' => $organization->id,
        ]));

        return $organization;
    }

    /**
     * Delete organization and it's persons.
     *
     * @param  int  $id
     * @return @void
     */
    public function delete($id)
    {
        $organization = $this->findOrFail($id);

        DB::transaction(function () use ($organization, $id) {
            $this->attributeValueRepository->deleteWhere([
                'entity_id' => $id,
                'entity_type' => 'organizations',
            ]);

            $organization->delete();
        });
    }
}
