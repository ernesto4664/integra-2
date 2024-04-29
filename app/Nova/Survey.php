<?php

namespace App\Nova;

use App\Helpers\UtilHelper;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\MultiselectField\Multiselect;

class Survey extends Resource
{
    public static $group = 'Encuestas';
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */

    public static function label()
    {
        return 'Encuestas';
    }

    public static $model = 'App\Survey';

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
        'slug',
        'title',
        'text',
        'date',
        'icon',
        'class',
        'url_survey',
        'title_button'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $icon = UtilHelper::getIco();
        $where = UtilHelper::getUserByRol();
        return [
            ID::make()->sortable(),
            Text::make('Título encuesta', 'title')
                ->creationRules('unique:surveys,title')
                ->sortable()
                ->updateRules('unique:surveys,title,{{resourceId}}'),
            Slug::make('slug')->from('title')->creationRules('unique:surveys,slug'),
            Text::make('Título boton encuesta', 'title_button'),
            DateTime::make('Fecha desde', 'date')->rules('required'),
            DateTime::make('Fecha hasta', 'end_date')->rules('required'),

            Select::make('Icono', 'icon')->options($icon)->displayUsingLabels(),
            Text::make('Clase', 'class'),
            Text::make('Url encueta', 'url_survey'),
           
            Multiselect::make('Dependencia', 'district')
                ->belongsToMany(\App\Nova\District::class, false)
                ->placeholder('Seleccione los distritos'),

            Multiselect::make('Cargo', 'position')
                ->belongsToMany(\App\Nova\Position::class, false)
                ->placeholder('Seleccione los cargos'),

            Multiselect::make('Región', 'region')
                ->belongsToMany(\App\Nova\Region::class, false)
                ->placeholder('Seleccione las regiones'),
           
            BelongsTo::make('Listado de usuario', 'UserRutList', 'App\Nova\UserRutList')->nullable(),
        ];
    }


    public static function indexQuery(NovaRequest $request, $query)
    {
        $where = UtilHelper::getUserByRol();
        return $query->distinct()->select('surveys.*')->leftJoin('survey_regions', 'survey_regions.survey_id', '=', 'surveys.id')
            ->leftJoin('regions', 'regions.id', '=', 'survey_regions.region_id')
            ->whereRaw($where)
            ->groupBy('surveys.id');;
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