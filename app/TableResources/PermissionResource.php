<?php

namespace App\TableResources;

use Humweb\Table\Fields\Actions;
use Humweb\Table\Fields\Date;
use Humweb\Table\Fields\FieldCollection;
use Humweb\Table\Fields\ID;
use Humweb\Table\Fields\Text;
use Humweb\Table\Filters\FilterCollection;
use Humweb\Table\Resource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;


class PermissionResource extends Resource
{

    protected $model = Permission::class;

    public function __construct(Request $request, $parameters = [])
    {
        parent::__construct($request);
        $this->parameters = $parameters;
    }

    public function fields(): FieldCollection
    {
        return new FieldCollection([
            ID::make('ID', 'id')->sortable()->searchable(),
            Text::make('Name', 'name')->sortable()->searchable(),
            Text::make('Guard', 'guard_name')->sortable()->searchable(),
            Date::make('Created', 'created_at')->sortable()->searchable(),
            Date::make('Updated', 'updated_at')->sortable()->searchable(),
            Actions::make(' ')->actions([
                ['label' => 'Edit', 'route' => 'admin.permissions.edit', 'params' => ['id']]
            ])
        ]);
    }

//    public function transform()
//    {
//        return fn($user) => [
//            'id'         => $user->id,
//            'name'       => $user->name,
//            'guard_name' => $user->guard_name,
//            'created_at' => $user->created_at->format('Y-m-d'),
//            'updated_at' => $user->updated_at->format('Y-m-d'),
//        ];
//    }

    public function filters(): FilterCollection
    {
        return new FilterCollection([
//            TextFilter::make('name')->fullSearch()->rules('string'),
//            TextFilter::make('email')->fullSearch()->rules('string'),
//TrashedFilter::make('trashed', 'Status'),
        ]);
    }

    public function globalFilter($query, $value)
    {
        return $query->where(function ($query) use ($value) {
            $query->when(is_numeric($value), function ($query, $bool) use ($value) {
                $query->orWhere('id', $value);
            })->when(!is_numeric($value), function ($query, $bool) use ($value) {
                $query->orWhere('name', 'like', "%{$value}%")
                    ->orWhere('guard_name', 'like', "%{$value}%");
            });
        });
    }

}
