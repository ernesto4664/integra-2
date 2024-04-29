<?php

namespace App\Nova;

use App\Helpers\UtilHelper;
use Laravel\Nova\Fields\Slug;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Emilianotisato\NovaTinyMCE\NovaTinyMCE;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\MultiselectField\Multiselect;
use Laravel\Nova\Fields\Select;

class Release extends Resource
{
    public static $group = 'Publicaciones';
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */

    public static function label()
    {
        return 'Comunicados';
    }

    public static $model = 'App\Release';

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
        'title',
        'post_intro',
        'datetime',
        'icon',
        'slug',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $where = UtilHelper::getUserByRol();
        $icon = UtilHelper::getIco();
        return [
            ID::make()->sortable(),
            Boolean::make('¿Es oferta laboral?', 'is_job_offer'),
            Boolean::make('¿Es pubico?', 'is_public'),
            Boolean::make('¿Es privado?', 'is_private'),
            Text::make('Título', 'title')->rules('required'),
            Slug::make('slug')->from('title')->rules('required')->hideWhenUpdating(),
            DateTime::make('Fecha', 'datetime')->rules('required'),
            Select::make('Icono', 'icon')->options($icon)->displayUsingLabels(),
            
            Trix::make('Subtitulo', 'post_intro')->rules('required'),

            Multiselect::make('Beneficios', 'benefit')
            ->belongsToMany(\App\Nova\Benefits::class, false)
            ->placeholder('Seleccione beneficios'),

            NovaTinyMCE::make('Contenido', 'post_content')
                ->options([
                    'plugins' => [
                        'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                        'searchreplace wordcount visualblocks visualchars code fullscreen',
                        'insertdatetime media nonbreaking save table contextmenu directionality',
                        'emoticons template paste textcolor colorpicker textpattern',
                    ],
                    'toolbar' => 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media',
                    'relative_urls' => false,
                    'remove_script_host' => false,
                    'convert_urls' => true,
                    'use_lfm' => true,
                    'lfm_url' => 'filemanager',
                ]),

            Multiselect::make('Link', 'linkRelease')
                ->belongsToMany(\App\Nova\Link::class, false)
                ->placeholder('Seleccione los link'),
            
            Multiselect::make('Dependencia', 'district')
                ->belongsToMany(\App\Nova\District::class, false)
                ->placeholder('Seleccione los distritos'),

            Multiselect::make('Cargo', 'position')
                ->belongsToMany(\App\Nova\Position::class, false)
                ->placeholder('Seleccione los cargos'),

            Multiselect::make('Región', 'region')
                ->belongsToMany(\App\Nova\Region::class, false)
                ->placeholder('Seleccione las regiones'),

            Images::make('Imagen de la novedad', 'image_release')
                ->fullSize()
                ->singleMediaRules(['mimes:png,jpg,jpeg,gif,tiff,tif,raw,bmp,psd'])->rules('required'),
            Text::make('Código del video', 'video'),
        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        $where = UtilHelper::getUserByRol();
        return $query->distinct()->select('releases.*')->join('release_regions', 'release_regions.release_id', '=', 'releases.id')
            ->join('regions', 'regions.id', '=', 'release_regions.region_id')
            ->whereRaw($where)
            ->groupBy('releases.id');
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