<?php

namespace Workdo\Holidayz\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\Holidayz\Entities\Hotels;
use Workdo\Holidayz\Entities\HotelThemeSettings;
use Workdo\Holidayz\Events\ChangeHotelTheme;
use Workdo\Holidayz\Events\UpdateHotelTheme;

class ThemesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('holidayz::index');
    }

    public function changeTheme(Request $request, $slug)
    {
        if (\Auth::user()->isAbleTo('themes edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'hotel_theme_color' => 'required',
                    'themefile' => 'required',
                ]
            );

            if ($request->enable_domain == 'enable_domain') {
                $validator = validator()->make(
                    $request->all(), [
                        'domains' => 'required',
                    ]
                );
            }
            if ($request->enable_domain == 'enable_subdomain') {
                $validator = \Validator::make(
                    $request->all(), [
                        'subdomain' => 'required',
                    ]
                );
            }

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            
            if ($request->enable_domain == 'enable_domain') {
                $input = $request->domains;
                $input = trim($input, '/');
                if (!preg_match('#^http(s)?://#', $input)) {
                    $input = 'http://' . $input;
                }
                $urlParts = parse_url($input);
                $domain_name = preg_replace('/^www\./', '', $urlParts['host']);
            }

            if ($request->enable_domain == 'enable_subdomain') {
                $input = env('APP_URL');
                $input = trim($input, '/');
                if (!preg_match('#^http(s)?://#', $input)) {
                    $input = 'http://' . $input;
                }
                $urlParts = parse_url($input);
                $subdomain_name = preg_replace('/^www\./', '', $urlParts['host']);
                $subdomain_name = $request->subdomain . '.' . $subdomain_name;
            }

            if ($request->enable_domain == 'enable_domain') {
                $request['domains'] = $domain_name;
            }
            if ($request->enable_domain == 'enable_subdomain') {
                $request['subdomain'] = $subdomain_name;
            }

            $request['enable_storelink'] = ($request->enable_domain == 'enable_storelink' || empty($request->enable_domain)) ? 'on' : 'off';
            $request['enable_subdomain'] = ($request->enable_domain == 'enable_subdomain') ? 'on' : 'off';
            $request['enable_domain'] = ($request->enable_domain == 'enable_domain') ? 'on' : 'off';

            
            $hotel = Hotels::find($slug);
            $hotel['hotel_theme'] = $request->hotel_theme_color;
            $hotel['theme_dir'] = $request->themefile;

            $hotel['enable_subdomain'] = $request->enable_subdomain;
            $hotel['enable_storelink'] = $request->enable_storelink;
            $hotel['subdomain'] = $request->subdomain;
            $hotel['enable_domain'] = $request->enable_domain;
            $hotel['domains'] = $request->domains;

            $hotel->save();
            event(new ChangeHotelTheme($request,$hotel));
            return redirect()->back()->with('success', __('Theme Successfully Updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function EditTheme($slug, $theme)
    {
        if(\Auth::user()->isAbleTo('themes edit')){
            $hotel = Hotels::where('slug', $slug)->first();
            if(!empty($hotel)){
                $getHotelThemeSetting = \Workdo\Holidayz\Entities\Utility::getHotelThemeSetting($hotel->workspace, $theme);
                $getHotelThemeSetting1 = [];
                
                if(!empty($getHotelThemeSetting['dashboard'])) {
                    $getHotelThemeSetting1 = $getHotelThemeSetting;
                    $getHotelThemeSetting = json_decode($getHotelThemeSetting['dashboard'], true);
                }
                if (empty($getHotelThemeSetting)) {
                    $path = asset('packages/workdo/Holidayz/src/Resources/assets/'. $hotel->theme_dir . "/" . $hotel->theme_dir . ".json" );
                    $getHotelThemeSetting = json_decode(file_get_contents($path), true);
                }
                return view('holidayz::themes.edit_themes', compact('hotel', 'theme', 'getHotelThemeSetting','getHotelThemeSetting1'));
            }else{
                return redirect()->back()->with('error', __('Hotel not found.'));
            }
        }
        else{
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function HotelEditTheme(Request $request, $slug, $theme)
    {
        $hotel = Hotels::where('slug', $slug)->first();
        $json = $request->array;
        foreach ($json as $key => $jsn) {
            foreach ($jsn['inner-list'] as $IN_key => $js) {
                if ($js['field_type'] == 'multi file upload') {

                    if (!empty($js['multi_image'])) {
                        foreach ($js['multi_image'] as $file) {
                            $filenameWithExt = $file->getClientOriginalName();
                            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME) . '_brand';
                            $extension = $file->getClientOriginalExtension();
                            $fileNameToStore = $IN_key . '_' . rand(10, 100) . '_' . date('ymd') . time() . '.' . $extension;
                            $file_name[] = $fileNameToStore;

                            if(admin_setting('storage_setting')=='local'){
                                $dir = $hotel->theme_dir . '/header';
                            }
                            else{
                                $dir = $hotel->theme_dir . '/header';
                            }

                            $path = multi_upload_file($file,'field_default_text',$fileNameToStore,$dir,[]);

                            if($path['flag'] == 1){
                                $url = $path['url'];
                            }else{
                                return redirect()->back()->with('error', __($path['msg']));
                            }
                            $new_path = $hotel->theme_dir . '/header/' . $fileNameToStore;
                            $json[$key]['inner-list'][$IN_key]['image_path'][] = $new_path;

                            $next_key_p_image = !empty($key_file) ? $key_file : 0;
                            if (!empty($jsn['prev_image'])) {
                                foreach ($jsn['prev_image'] as $p_key => $p_value) {
                                    $next_key_p_image = $next_key_p_image + 1;
                                    $json[$key]['inner-list'][$IN_key]['image_path'][] = $p_value;

                                }
                            }

                        }
                    }else {

                        if(!empty($jsn['prev_image'])) {
                            foreach ($jsn['prev_image'] as $p_key => $p_value) {
                                $json[$key]['inner-list'][$IN_key]['image_path'][] = $p_value;
                            }
                        }
                    }

                }

                if ($js['field_type'] == 'photo upload') {
                    if ($jsn['array_type'] == 'multi-inner-list') {

                        for ($i = 0; $i < $jsn['loop_number']; $i++) {
                            if (empty($json[$key][$js['field_slug']][$i]['field_prev_text'])) {
                                $json[$key][$js['field_slug']][$i]['field_prev_text'] = $js['field_default_text'];
                            }

                            if (!empty($json[$key][$js['field_slug']][$i]['image']) && gettype($json[$key][$js['field_slug']][$i]['image']) == 'object') {

                                $file = $json[$key][$js['field_slug']][$i]['image'];
                                $filenameWithExt = $file->getClientOriginalName();
                                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                                $extension = $file->getClientOriginalExtension();
                                $fileNameToStore = $i . '_' . rand(10, 100) . '_' . date('ymd') . time() . '.' . $extension;
                                $file_name[] = $fileNameToStore;

                                if(admin_setting('storage_setting')=='local'){
                                    $dir = $hotel->theme_dir . '/header';
                                }
                                else{
                                    $dir = $hotel->theme_dir . '/header';
                                }

                                $path = multi_upload_file($file,'field_default_text',$fileNameToStore,$dir,[]);

                                if($path['flag'] == 1){
                                    $url = $path['url'];
                                }else{
                                    return redirect()->back()->with('error', __($path['msg']));
                                }
                                if (!empty($file_name) && count($file_name) > 0) {
                                    $json[$key][$js['field_slug']][$i]['image'] = $hotel->theme_dir . '/header/' . $fileNameToStore;
                                    $json[$key][$js['field_slug']][$i]['field_prev_text'] = $hotel->theme_dir . '/header/' . $fileNameToStore;
                                }
                            }
                        }

                    } else {
                        if (gettype($js['field_default_text']) == 'object') {

                            $file = $js['field_default_text'];
                            $filenameWithExt = $file->getClientOriginalName();
                            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                            $extension = $file->getClientOriginalExtension();
                            $fileNameToStore = rand(1,99999).time(). date('ymd') . time() . '.' . $extension;
                            $file_name[] = $fileNameToStore;

                            if(admin_setting('storage_setting')=='local'){
                                $dir  = 'uploads/'. $hotel->theme_dir . '/header';
                            }
                            else{
                                $dir = 'uploads/'. $hotel->theme_dir . '/header';
                            }
                            $path = \Workdo\Holidayz\Entities\Utility::json_upload_file($js,'field_default_text',$fileNameToStore,$dir,[]);
                            if($path['flag'] == 1){
                                $url = $path['url'];
                            }else{
                                return redirect()->back()->with('error', __($path['msg']));
                            }

                            if (!empty($file_name) && count($file_name) > 0) {
                                $post['Bckground Image'] = implode(',', $file_name);
                                $headerImage = $hotel->theme_dir . '/header/' . $post['Bckground Image'];
                                $json[$key]['inner-list'][$IN_key]['field_default_text'] = $headerImage;
                            }
                        }
                    }

                }
            }
        }

        $json = json_encode($json);
        $hotel = Hotels::where('slug', $slug)->where('created_by', creatorId())->first();
        $arr = [
            'name' => 'dashboard',
            'value' => $json,
            'type' => null,
            'workspace' => getActiveWorkSpace(),
            'theme_name' => $hotel->theme_dir,
            'created_by' => creatorId(),
        ];

        if (!empty($json)) {
            HotelThemeSettings::updateOrCreate([
                'name' => 'dashboard',
                'workspace' => getActiveWorkSpace(),
                'theme_name' => $hotel->theme_dir,
            ], $arr);
        }

        $post = [];

        //  top bar settings
        if (isset($request->enable_top_bar) && !empty($request->enable_top_bar) && $request->enable_top_bar == 'on') {
            if($theme == 'theme1'){
                $validator = \Validator::make(
                    $request->all(), [
                        'top_bar_title' => 'required|string|max:255',
                        'top_bar_number' => 'required|string|max:255',
                    ]
                );
            }
            else{
                $validator = \Validator::make(
                    $request->all(), [
                        'top_bar_title' => 'required|string|max:255',
                    ]
                );
            }

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['enable_top_bar'] = $request->enable_top_bar;
            $post['top_bar_title'] = $request->top_bar_title;
            $post['top_bar_number'] = $request->top_bar_number;
            $post['top_bar_whatsapp'] = $request->top_bar_whatsapp;
            $post['top_bar_instagram'] = $request->top_bar_instagram;
            $post['top_bar_twitter'] = $request->top_bar_twitter;
            $post['top_bar_messenger'] = $request->top_bar_messenger;
        } else {
            $post['enable_top_bar'] = 'off';
        }

        foreach ($post as $key => $data) {
            $arr = [
                'name' => $key,
                'value' => $data,
                'type' => null,
                'workspace' => getActiveWorkSpace(),
                'theme_name' => $hotel->theme_dir,
                'created_by' => creatorId(),
            ];
            HotelThemeSettings::updateOrCreate(
                [
                    'name' => $key,
                    'workspace' => getActiveWorkSpace(),
                    'theme_name' => $hotel->theme_dir,
                ], $arr
            );
        }

        $hotel_theme = HotelThemeSettings::where('workspace',getActiveWorkSpace())->where('theme_name',$hotel->theme_dir)->get();
        event(new UpdateHotelTheme($request,$hotel_theme));
        return redirect()->back()->with('success', __('Theme Settings Successfully Saved!'));
    }


    public function HotelDeleteThemeImage($slug, $theme, $key)
    {
        $hotel = Hotels::where('slug', $slug)->first();
        if(!empty($hotel)){
            $getHotelThemeSetting = \Workdo\Holidayz\Entities\Utility::getHotelThemeSetting($hotel->workspace, $theme);
            if (empty($getHotelThemeSetting)) {
                return redirect()->back()->with('error', __('Hotel Theme not found.'));
            }
            if(!empty($getHotelThemeSetting['dashboard'])) {
                $getHotelThemeSetting = json_decode($getHotelThemeSetting['dashboard'], true);
                if($getHotelThemeSetting[9]['section_name'] == 'Instagram' && !empty($getHotelThemeSetting[9]['prev_image']) && !empty($getHotelThemeSetting[9]['inner-list'][0]['image_path'])){

                    if(isset($getHotelThemeSetting[9]['inner-list'][0]['image_path'][$key])){
                        if (check_file('uploads/'. $getHotelThemeSetting[9]['inner-list'][0]['image_path'][$key])) {
                            delete_file('uploads/'. $getHotelThemeSetting[9]['inner-list'][0]['image_path'][$key]);
                        }
                    }
                    if (isset($getHotelThemeSetting[9]['prev_image'][$key]) && check_file('uploads/'. $getHotelThemeSetting[9]['prev_image'][$key])) {
                        delete_file('uploads/'. $getHotelThemeSetting[9]['prev_image'][$key]);
                    }

                    unset($getHotelThemeSetting[9]['prev_image'][$key]);
                    unset($getHotelThemeSetting[9]['inner-list'][0]['image_path'][$key]);

                    HotelThemeSettings::updateOrCreate(['name' =>  'dashboard'],['value' => $getHotelThemeSetting]);

                    return redirect()->back()->with('success', __('Social Image Successfully Deleted!'));
                }
            }else{
                return redirect()->back()->with('error', __('Something Went Wrong.'));
            }
        }else{
            return redirect()->back()->with('error', __('Hotel not found.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('holidayz::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('holidayz::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('holidayz::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
