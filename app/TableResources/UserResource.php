<?php

namespace App\TableResources;

use App\Models\User;
use Humweb\Table\Fields\Actions;
use Humweb\Table\Fields\Date;
use Humweb\Table\Fields\FieldCollection;
use Humweb\Table\Fields\ID;
use Humweb\Table\Fields\Text;
use Humweb\Table\Filters\FilterCollection;
use Humweb\Table\Filters\TextFilter;
use Humweb\Table\Resource;


class UserResource extends Resource
{

    protected $model = User::class;

    /**
     * Define fields for resource
     *
     * @return FieldCollection
     */
    public function fields(): FieldCollection
    {
        return new FieldCollection([
            ID::make('ID', 'id')->sortable()->searchable(),
            Text::make('Name', 'name')->sortable()->searchable(),
            Text::make('Email')->sortable()->searchable(),
            Date::make('Created', 'created_at')->sortable()->searchable(),
            Date::make('Updated', 'updated_at')->sortable()->searchable(),
            Actions::make(' ')->actions([
                ['label' => 'Edit', 'route'=> 'admin.users.edit', 'params' => ['id']]
            ])
        ]);
    }

    public function filters(): FilterCollection
    {
        return new FilterCollection([
            TextFilter::make('name')->fullSearch()->rules('string'),

        ]);
    }

    /**
     * Define a global filter for search
     *
     * @param $query
     * @param $value
     *
     * @return mixed
     */
    public function globalFilter($query, $value)
    {
        return $query->where(function ($query) use ($value) {
            $query->when(is_numeric($value), function ($query, $bool) use ($value) {
                $query->orWhere('id', $value);
            })->when(!is_numeric($value), function ($query, $bool) use ($value) {
                $query->orWhere('name', 'like', "%{$value}%")
                    ->orWhere('email', 'like', "%{$value}%");
            });
        });
    }

}
