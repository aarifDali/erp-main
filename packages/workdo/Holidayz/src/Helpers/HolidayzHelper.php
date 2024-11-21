<?php

use Illuminate\Support\Facades\Storage;

if(! function_exists('themeOne')){
function themeOne()
{
    $arr = [];

    $arr = [
        'theme1' => [
            'theme1-v1' => [
                'img_path' => asset('packages/workdo/Holidayz/src/Resources/assets/store_theme/theme1/Home.png'),
                'color' => '92bd88',
            ],
            'theme1-v2' => [
                'img_path' => asset('packages/workdo/Holidayz/src/Resources/assets/store_theme/theme1/Home-1.png'),
                'color' => '276968',
            ],
            'theme1-v3' => [
                'img_path' => asset('packages/workdo/Holidayz/src/Resources/assets/store_theme/theme1/Home-2.png'),
                'color' => 'af8637',
            ],
            'theme1-v4' => [
                'img_path' => asset('packages/workdo/Holidayz/src/Resources/assets/store_theme/theme1/Home-3.png'),
                'color' => 'e7d7bd',
            ],
            'theme1-v5' => [
                'img_path' => asset('packages/workdo/Holidayz/src/Resources/assets/store_theme/theme1/Home-4.png'),
                'color' => 'b7786f',
            ],
        ],

        // 'theme2' => [
        //     'theme2-v1' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme2/Home.png')),
        //         'color' => 'f5ba20',
        //     ],
        //     'theme2-v2' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme2/Home-1.png')),
        //         'color' => 'fa747d',
        //     ],
        //     'theme2-v3' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme2/Home-2.png')),
        //         'color' => 'c8ae9d',
        //     ],
        //     'theme2-v4' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme2/Home-3.png')),
        //         'color' => 'd7e2dc',
        //     ],
        //     'theme2-v5' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme2/Home-4.png')),
        //         'color' => '5ea5ab',
        //     ],
        // ],

        // 'theme3' => [
        //     'theme3-v1' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme3/Home.png')),
        //         'color' => 'f6e32f',
        //     ],
        //     'theme3-v2' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme3/Home-1.png')),
        //         'color' => '7db802',
        //     ],
        //     'theme3-v3' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme3/Home-2.png')),
        //         'color' => '3e77ea',
        //     ],
        //     'theme3-v4' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme3/Home-3.png')),
        //         'color' => '2b2d2d',
        //     ],
        //     'theme3-v5' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme3/Home-4.png')),
        //         'color' => 'ffccb4',
        //     ],
        // ],

        // 'theme4' => [
        //     'theme4-v1' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme4/Home.png')),
        //         'color' => '5e7698',
        //     ],
        //     'theme4-v2' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme4/Home-1.png')),
        //         'color' => '88d297',
        //     ],
        //     'theme4-v3' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme4/Home-2.png')),
        //         'color' => 'c9aea7',
        //     ],
        //     'theme4-v4' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme4/Home-3.png')),
        //         'color' => '2f343a',
        //     ],
        //     'theme4-v5' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme4/Home-4.png')),
        //         'color' => 'f3ba51',
        //     ],
        // ],

        // 'theme5' => [
        //     'theme5-v1' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme5/Home.png')),
        //         'color' => '007aff',
        //     ],
        //     'theme5-v2' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme5/Home-1.png')),
        //         'color' => 'febd00',
        //     ],
        //     'theme5-v3' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme5/Home-2.png')),
        //         'color' => '05d79f',
        //     ],
        //     'theme5-v4' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme5/Home-3.png')),
        //         'color' => 'e91e63',
        //     ],
        //     'theme5-v5' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme5/Home-4.png')),
        //         'color' => '2b2d42',
        //     ],
        // ],

        // 'theme6' => [
        //     'theme6-v1' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme6/Home.png')),
        //         'color' => '94ce79',
        //     ],
        //     'theme6-v2' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme6/Home-1.png')),
        //         'color' => '79ceb4',
        //     ],
        //     'theme6-v3' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme6/Home-2.png')),
        //         'color' => 'f4b41a',
        //     ],
        //     'theme6-v4' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme6/Home-3.png')),
        //         'color' => '1877f2',
        //     ],
        //     'theme6-v5' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme6/Home-4.png')),
        //         'color' => 'e6007e',

        //     ],
        // ],

        // 'theme7' => [
        //     'theme7-v1' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme7/Home.png')),
        //         'color' => '2b2d42',
        //     ],
        //     'theme7-v2' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme7/Home-1.png')),
        //         'color' => '54a089',
        //     ],
        //     'theme7-v3' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme7/Home-2.png')),
        //         'color' => '615144',
        //     ],
        //     'theme7-v4' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme7/Home-3.png')),
        //         'color' => '1877f2',
        //     ],
        //     'theme7-v5' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme7/Home-4.png')),
        //         'color' => '92be35',

        //     ],
        // ],

        // 'theme8' => [
        //     'theme8-v1' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme8/Home.png')),
        //         'color' => '3E3E37',
        //     ],
        //     'theme8-v2' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme8/Home-1.png')),
        //         'color' => '615144',
        //     ],
        //     'theme8-v3' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme8/Home-2.png')),
        //         'color' => '54a085',
        //     ],
        //     'theme8-v4' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme8/Home-3.png')),
        //         'color' => '6e00ff',
        //     ],
        //     'theme8-v5' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme8/Home-4.png')),
        //         'color' => '7e7d7c',

        //     ],
        // ],

        // 'theme9' => [
        //     'theme9-v1' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme9/Home.png')),
        //         'color' => '000000',
        //     ],
        //     'theme9-v2' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme9/Home-1.png')),
        //         'color' => '793838',
        //     ],
        //     'theme9-v3' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme9/Home-2.png')),
        //         'color' => '4a7a6c',
        //     ],
        //     'theme9-v4' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme9/Home-3.png')),
        //         'color' => '0f3e7a',
        //     ],
        //     'theme9-v5' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme9/Home-4.png')),
        //         'color' => '848484',

        //     ],
        // ],

        // 'theme10' => [
        //     'theme10-v1' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme10/Home.png')),
        //         'color' => '256dff',
        //     ],
        //     'theme10-v2' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme10/Home-1.png')),
        //         'color' => 'e6007e',
        //     ],
        //     'theme10-v3' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme10/Home-2.png')),
        //         'color' => 'f25c05',
        //     ],
        //     'theme10-v4' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme10/Home-3.png')),
        //         'color' => '210070',
        //     ],
        //     'theme10-v5' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme10/Home-4.png')),
        //         'color' => 'f4b41a',

        //     ],
        //     'theme10-v6' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme10/Home-5.png')),
        //         'color' => '1f3767',

        //     ],
        //     'theme10-v7' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme10/Home-6.png')),
        //         'color' => '727272',

        //     ],
        //     'theme10-v8' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme10/Home-7.png')),
        //         'color' => 'ff1f00',

        //     ],
        //     'theme10-v9' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme10/Home-8.png')),
        //         'color' => '004870',

        //     ],
        //     'theme10-v10' => [
        //         'img_path' => asset(Storage::url('uploads/store_theme/theme10/Home-9.png')),
        //         'color' => '425b23',

        //     ],
        // ],
    ];

    return $arr;
}
}
