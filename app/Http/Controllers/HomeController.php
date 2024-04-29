<?php

namespace App\Http\Controllers;

use App\Constribution;
use App\Helpers\UtilHelper;
use App\Post;
use App\Release;
use App\Survey;
use App\Termn;
use App\User;
use App\welcomePage;
use Carbon\Carbon;
use App\Http\Controllers\LogUserController;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{


    public function termn()
    {
        $term = Termn::where('is_home', '!=', 0)->first();
        $termText = [];
        foreach ($term->name as $value) {
            $termText [] = [
                'name' => $value['attributes']['name']
            ];
        }
        return response()->json(
            [
                'is_logged' => auth('sanctum')->user() ? 1 : 0,
                'data' => [
                    'id' => $term->id,
                    'name' => $termText,
                    'is_home' => $term->is_home,
                ]
            ],
            200
        );
    }

    public function registerFull()
    {
        $user = auth()->user();
        $getUser = User::find($user->id);
        $getUser->full_register = 1;
        $getUser->save();
        return response()->json([
            'is_logged' => auth('sanctum')->user() ? 1 : 0,
            'data' => $getUser,
        ], 200);
    }

    public function updateUserTermCondition()
    {
        date_default_timezone_set('America/Santiago');
        $user = auth()->user();
        $getUser = User::find($user->id);
        $getUser->is_termn_home = 1;
        $getUser->updated_at_termn_service_home = date("Y-m-d H:i:s");
        $getUser->save();
        return response()->json(
            [
                'data' => $getUser,
                'is_logged' => auth('sanctum')->user() ? 1 : 0,
            ],
            200
        );
    }

    public function isCheckTermn()
    {
        $user = auth()->user();

        if ($termn) {
            return response()->json(
                [
                    'is_termn' => 1,
                    'is_logged' => auth('sanctum')->user() ? 1 : 0,
                ],
                200
            );
        }
        return response()->json(['is_termn' => 0], 200);
    }

    public function formatNew($news) {
        $newArray = [];
        if(isset($news) && !empty($news)) {
            foreach ($news as $value) {
                $dataImage = [
                    'src' => $value->getFirstMedia('image_new') ? $value->getFirstMediaUrl('image_new') : '',
                    'alt' => $value->getFirstMedia('image_new')->name ? $value->getFirstMedia('image_new')->name : '',
                ];
                $newArray[] = [
                    'id' => $value->id,
                    'slug' => $value->slug,
                    'img' => $dataImage,
                    'title' => $value->title,
                    'date' => date("d · m · Y", strtotime($value->datetime)),
                ];
            }
        }
        return $newArray;
    }

    public function formatBtnHome($welcomePage, $type = 1) {
        $pageArray = [];
        if(isset($welcomePage) && !empty($welcomePage)) {
            foreach ($welcomePage as $value) {
                if ($type == 1 && $value->view_home) {
                    $pageArray[] = [
                        'title' => $value->title,
                        'icon' => $value->icon,
                        'slug' => $value->slug,
                        'class' => $value->class,
                        'view_home' => $value->view_home,
                        'type_certificate' => $value->type_certificate,
                        'is_public' => $value->is_public
                    ];
                }else if ($type == 2 && $value->type_certificate) {
                    $pageArray[] = [
                        'title' => $value->title,
                        'icon' => $value->icon,
                        'slug' => $value->slug,
                        'class' => $value->class,
                        'view_home' => $value->view_home,
                        'type_certificate' => $value->type_certificate,
                        'is_public' => $value->is_public
                    ];
                }
            }
        }
        return $pageArray;
    }
    

    public function formatReleases($releases) {
        $releaseArray = [];
        if(isset($releases) && !empty($releases)) {
            foreach ($releases as $value) {
                $releaseArray[] = [
                    'id' => $value->id,
                    'slug' => $value->slug,
                    'title' => $value->title,
                    'icon' => $value->icon,
                    'date' => date("d · m · Y", strtotime($value->datetime)),
                ];
            }
        }
        return $releaseArray;
    }

    public function index()
    {
        $utilHelper = new UtilHelper();
        $user = auth('sanctum')->user();
        $pageArray = [];
        $news = [];
        $releaseArray = [];
        $newArray = [];
        $buttonArray = [];

        $now = Carbon::now();

        $util = new LogUserController;
        $util->store('home');

        $welcomePage = welcomePage::where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->orderBy('created_at', 'asc')
            ->get();
    
        // //user auth
        // $certificate = welcomePage::where('type_certificate', 1)
        // ->orderBy('created_at', 'asc')
        // ->get();

        if ($user == null or (isset($user->is_public)) && $user->is_public) {
            $news = Post::select('posts.*')
                ->where('datetime', '<=', $utilHelper->getDateCurrent())
                ->where('is_public', 1)
                ->orderBy('datetime', 'desc')
                ->limit(2)
                ->get();
            $releases = Release::select('releases.*')
                ->where('datetime', '<=', $utilHelper->getDateCurrent())
                ->where('is_public', 1)
                ->limit(2)
                ->orderBy('datetime', 'desc')
                ->distinct()
                ->get();
        } else {
            $region = UtilHelper::QueryRegion($user);
            $news = Post::select('posts.*')
                    ->join('post_districts', 'post_districts.post_id', 'posts.id')
                    ->join('districts', 'post_districts.district_id', 'districts.id')
                    ->join('post_positions', 'post_positions.post_id', 'posts.id')
                    ->join('positions', 'post_positions.position_id', 'positions.id')
                    ->join('post_regions', 'post_regions.post_id', 'posts.id')
                    ->join('regions', 'post_regions.region_id', 'regions.id')
                    ->where('datetime', '<=', $utilHelper->getDateCurrent())
                    ->where('datetime', '<=', $utilHelper->getDateCurrent())
                    ->where('positions.code', $user->persk)
                    ->whereRaw($region)
                    ->where('districts.code', $user->tipest)
                    ->where('posts.is_private', 1)
                    ->limit(2)
                    ->orderBy('datetime', 'desc')
                    ->distinct()
                    ->get();

            $releases = Release::select('releases.*')
                    ->join('release_districts', 'release_districts.release_id', 'releases.id')
                    ->join('districts', 'release_districts.district_id', 'districts.id')
                    ->join('release_positions', 'release_positions.release_id', 'releases.id')
                    ->join('positions', 'release_positions.position_id', 'positions.id')
                    ->join('release_regions', 'release_regions.release_id', 'releases.id')
                    ->join('regions', 'release_regions.region_id', 'regions.id')
                    ->where('datetime', '<=', $utilHelper->getDateCurrent())
                    ->where('datetime', '<=', $utilHelper->getDateCurrent())
                    ->where('positions.code', $user->persk)
                    ->whereRaw($region)
                    ->where('districts.code', $user->tipest)
                    ->where('releases.is_private', 1)
                    ->limit(2)
                    ->orderBy('datetime', 'desc')
                    ->distinct()
                    ->get();
        }
    
        $surveys = [];
        $results = [];
        if ($user) {
            $region = UtilHelper::QueryRegion($user);
            $surveys = Survey::select('surveys.*')
                    ->orderBy('date', 'desc')->join('survey_districts', 'survey_districts.survey_id', 'surveys.id')
                    ->join('districts', 'survey_districts.district_id', 'districts.id')
                    ->join('survey_positions', 'survey_positions.survey_id', 'surveys.id')
                    ->join('positions', 'survey_positions.position_id', 'positions.id')
                    ->join('survey_regions', 'survey_regions.survey_id', 'surveys.id')
                    ->join('regions', 'survey_regions.region_id', 'regions.id')
                    ->where('positions.code', $user->persk)
                    ->whereRaw($region)
                    ->where('districts.code', $user->tipest)
                    ->where('date', '<=', $now->toDateTimeString())
                    ->where('end_date', '>=', $now->toDateTimeString())
                    ->orderBy('date', 'desc')
                    ->distinct()
                    ->get();
            
            $surveysUser = Survey::select('surveys.*')
                ->orderBy('date', 'desc')
                ->join('user_rut_lists', 'user_rut_lists.id', 'surveys.user_rut_list_id')
                ->join('survey_users', 'survey_users.user_rut_list_id', 'user_rut_lists.id')
                ->join('users', 'survey_users.user_id', 'users.id')
                ->where('users.rut', $user->rut)
                ->where('date', '<=', $now->toDateTimeString())
                ->where('end_date', '>=', $now->toDateTimeString())
                ->orderBy('date', 'desc')
                ->distinct()
                ->get();
            if (count($surveys) > 0 && count($surveysUser) > 0) {
                $results = array_merge($surveysUser->toArray(), $surveys->toArray());
                $results = array_map("unserialize", array_unique(array_map("serialize", $results)));
            } elseif (count($surveys) > 0) {
                $results = $surveys;
            } else {
                $results = $surveysUser;
            }
        }
        //btn home format
        $pageArray = $this->formatBtnHome($welcomePage, 1);
        //format Releases
        $releaseArray = $this->formatReleases($releases);
        // format news
        $newArray = $this->formatNew($news);
        // format certificate
        $buttonArray = $this->formatBtnHome($welcomePage, 2);

        $alert = Constribution::where('init_date', '<=', $now->toDateTimeString())
            ->where('end_date', '>=', $now->toDateTimeString())
            ->first();

        return response()->json([
            'is_logged' => auth('sanctum')->user() ? 1 : 0,
            'alert' => $alert ? true : false,
            'alert_message' => $alert ? $alert->title : '',
            'data' => [
                'button' => array(
                    'data' => $buttonArray,
                ),
                'today' => array(
                    'data' => $pageArray,
                ),
                'releases' => array(
                    'data' => $releaseArray,
                ),
                'posts' => array(
                    'data' => $newArray,
                ),
                'survey' => array(
                    'data' => $results,
                ),
                'status' => true,
            ],
        ]);
    }
}
