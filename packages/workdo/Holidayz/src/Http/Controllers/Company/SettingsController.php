<?php
// This file use for handle company setting page

namespace Workdo\Holidayz\Http\Controllers\Company;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\Holidayz\Entities\Hotels;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($settings)
    {
        if (\Auth::check()) {
            $active_module = ActivatedModule();
            $dependency = explode(',', 'Holidayz');
            if (!empty(array_intersect($dependency, $active_module))) {
                $user = \Auth::user();
                $hotel_settings = Hotels::where('created_by',  \Auth::user()->id)->where('workspace', getActiveWorkSpace())->first();
                if($hotel_settings){
                    $serverName = str_replace(
                        [
                            'http://',
                            'https://',
                        ],
                        '',
                        env('APP_URL')
                    );

                    $serverIp = gethostbyname($serverName);
                    if ($serverIp == $_SERVER['SERVER_ADDR']) {
                        $serverIp;
                    } else {
                        $serverIp = request()->server('SERVER_ADDR');
                    }

                    $app_url = trim(env('APP_URL'), '/');
                    $hotel_settings['hotel_url'] = $app_url . '/hotel/' . $hotel_settings['slug'];

                    if (!empty($hotel_settings->enable_subdomain) && $hotel_settings->enable_subdomain == 'on') {
                        $input = env('APP_URL');
                        $input = trim($input, '/');
                        if (!preg_match('#^http(s)?://#', $input)) {
                            $input = 'http://' . $input;
                        }
                        $urlParts = parse_url($input);
                        $subdomain_name = preg_replace('/^www\./', '', $urlParts['host']);
                    } else {
                        $subdomain_name = str_replace(
                            [
                                'http://',
                                'https://',
                            ],
                            '',
                            env('APP_URL')
                        );
                    }
                }else{
                    $serverIp = ""  ;
                    $subdomain_name = ""  ;
                }

                return view('holidayz::company.settings.index',compact('settings','hotel_settings','serverIp','subdomain_name'));
            }
        }else{
            return view('holidayz::company.settings.index',compact('settings'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }
}
