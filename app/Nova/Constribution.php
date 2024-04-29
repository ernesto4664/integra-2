<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Trix;
use Whitecube\NovaFlexibleContent\Flexible;
use Laravel\Nova\Fields\Image;
use Outl1ne\NovaColorField\Color;

class Constribution extends Resource
{

    public static $group = 'Aportes';
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */

    public static function label()
    {
        return 'Aportes';
    }

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Constribution';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'title',
        'subtitle',
        'description',
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
            Text::make('Título', 'title')->rules('required'),
            Text::make('Subtítulo', 'subtitle')->rules('required'),
            Trix::make('Descripción', 'description')->rules('required'),
            DateTime::make('Fecha desde', 'init_date')->rules('required'),
            DateTime::make('Fecha hasta', 'end_date')->rules('required'),

            Text::make('Correo', 'email')->rules('required'),
            Textarea::make('Texto del check', 'text_check')->rules('required'),
            Textarea::make('Texto del gracias', 'gratitude')->rules('required'),
            Image::make('imagen del gracias (650 x 264)', 'gratitude_image')->rules('required'),
            Flexible::make('Precios', 'amounts')
                ->addLayout('Sección', 'amounts', [
                    Color::make('Color', 'color')->chrome(),
                    Text::make('Precio', 'value'),
                ]),

            Boolean::make('¿Es activo?', 'is_active')
                ->trueValue(1)
                ->falseValue(0)
                ->hideFromIndex(),
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
