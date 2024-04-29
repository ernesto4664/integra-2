<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Outl1ne\MultiselectField\Multiselect;

class UserRutList extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */

    public static $group = 'Encuestas';

    public static $model = 'App\UserRutList';

    public static function label()
    {
        return 'Listado de rut';
    }

    public static function singularLabel()
    {
        return 'Listado de rut';
    }

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('Título', 'name')
            ->creationRules('unique:user_rut_lists,name')
            ->sortable()
            ->updateRules('unique:user_rut_lists,name,{{resourceId}}'),
            Slug::make('slug')->from('name')->creationRules('unique:user_rut_lists,slug'),

            Multiselect::make('Usuario', 'user')
            ->belongsToMany(\App\Nova\UserNova::class)
            ->placeholder('Seleccione los usuarios'),

            // MultiSelect::make('Usuario', 'user')
            // ->options(\App\UserNova::select('rut as name', 'id')
            // ->whereNotNull('rut')->get())
            // ->placeHolder('Seleccione los usuarios'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}