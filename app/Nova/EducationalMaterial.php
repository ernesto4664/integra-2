<?php

namespace App\Nova;

use App\Helpers\UtilHelper;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Slug;
use Emilianotisato\NovaTinyMCE\NovaTinyMCE;
use Laravel\Nova\Http\Requests\NovaRequest;
use Whitecube\NovaFlexibleContent\Flexible;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Laravel\Nova\Fields\Select;

class EducationalMaterial extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */

    public static $group = 'Material Educativo';

    public static function label()
    {
        return 'Actividades';
    }

    protected static $sort = ['datetime' => 'desc'];

    /**
     * @return string
     */
    public static function singularLabel()
    {
        return 'Actividad';
    }

    public static $model = 'App\EducationalMaterial';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'slug',
        'title',
        'post_intro',
        'post_content',
        'datetime',
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
        return [
            ID::make()->sortable(),
            Boolean::make('¿Es actividad?', 'is_activity')->withMeta(["value" => 1, 'readonly' => true])
                ->hideFromIndex(),
            Text::make('Título', 'title')->rules('required')
                ->creationRules('unique:posts,title')
                ->updateRules('unique:posts,title,{{resourceId}}'),
            BelongsTo::make('Categoria', 'Category', 'App\Nova\Category')->rules('required'),

            Slug::make('slug')->from('title')->rules('required')->creationRules('unique:posts,slug'),
            DateTime::make('Fecha desde', 'datetime')->rules('required'),

            Trix::make('Subtitulo', 'post_intro')->rules('required'),

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

            Images::make('Imagen', 'image_educational_material')
            ->fullSize()
            ->singleMediaRules(['mimes:png,jpg,jpeg,gif,tiff,tif,raw,bmp,psd'])->rules('required'),                 // validation rules

            // Images::make('Imagen', 'image_educational_material')
            //     ->conversionOnDetailView('thumb')
            //     ->conversionOnIndexView('thumb')
            //     ->conversionOnForm('thumb')
            //     ->fullSize()
            //     ->singleMediaRules(['mimes:png,jpg,jpeg,gif,tiff,tif,raw,bmp,psd'])->rules('required'),

            Flexible::make('Docuemntos o vídeos', 'documents')
                ->addLayout('Sección', 'wysiwyg', [
                    Boolean::make('¿Es Video?', 'is_video'),
                    Text::make('Código del video', 'video'),

                    Select::make('Icono', 'icon')->options($icon)->displayUsingLabels(),
                    
                    Text::make('Título', 'title')->rules('required'),
                    File::make('Documento', 'document')
                        ->prunable()
                        ->disk('public')
                        ->storeAs(function (Request $request) {
                            return $request->file('document')->getClientOriginalName();
                        })->disableDownload(),
                ]),
        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        $query->getQuery()->orders = [];
        return $query->where('is_activity', 1)->orderBy(key(static::$sort), reset(static::$sort));
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \IlluminEducationalMaterialate\Http\Request  $request
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
