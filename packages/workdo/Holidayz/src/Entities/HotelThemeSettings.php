<?php

namespace Workdo\Holidayz\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HotelThemeSettings extends Model
{
    use HasFactory;

    use HasFactory;

    protected $table = "hotel_theme_settings";

    protected $fillable = [
        'name',
        'value',
        'type',
        'theme_name',
        'workspace',
        'created_by'
    ];


    public static function demoStoreThemeSetting($workspace = null, $theme_name = null)
    {
        $data = HotelThemeSettings::where('workspace', $workspace)->where('theme_name', $theme_name)->get();

        $settings = [
            "enable_top_bar" => "on",
            "top_bar_title" => "FREE SHIPPING world wide for all orders over $199",
            "top_bar_number" => "(212) 308-1220",
            "top_bar_whatsapp" => "https://web.whatsapp.com/",
            "top_bar_instagram" => "https://instagram.com/",
            "top_bar_twitter" => "https://twitter.com/",
            "top_bar_messenger" => "https://messenger.com/",

            "enable_header_img" => "on",
            "header_title" => "Home Accessories",
            "header_desc" => "There is only that moment and the incredible certainty that everything under the sun has been written by one hand only.",
            "button_text" => "Start shopping",
            "header_img" => "header_img_1.png",

            "enable_features" => "on",
            "enable_features1" => "on",
            "enable_features2" => "on",
            "enable_features3" => "on",

            "features_icon1" => '<i class="fa fa-tags"></i>',
            "features_title1" => 'Many promotions',
            "features_description1" => 'From pixel-perfect icons and scalable vector graphics, to full user flows',

            "features_icon2" => '<i class="fas fa-store"></i>',
            "features_title2" => 'Many promotions',
            "features_description2" => 'From pixel-perfect icons and scalable vector graphics, to full user flows',

            "features_icon3" => '<i class="fa fa-percentage"></i>',
            "features_title3" => 'Many promotions',
            "features_description3" => 'From pixel-perfect icons and scalable vector graphics, to full user flows',

            "enable_email_subscriber" => "on",
            "subscriber_title" => "Always on time",
            "subscriber_sub_title" => "There is only that moment and the incredible certainty that everything under the sun has been written by one hand only.",
            "subscriber_img" => "email_subscriber_1.png",

            "enable_categories" => "on",
            "categories" => "Categories",
            "categories_title" => "There is only that moment and the incredible certainty that everything under the sun has been written by one hand only.",

            "enable_testimonial" => "on",

            "enable_testimonial1" => "on",
            "testimonial_main_heading_title" => 'There is only that moment and the incredible certainty that everything under the sun has been written by one hand only.',
            "testimonial_main_heading" => 'Testimonial',
            "testimonial_img1" => 'avatar.png',

            "testimonial_name1" => 'Rajodiya Infotech',
            "testimonial_about_us1" => 'CEO Rajodiya Infotech',
            "testimonial_description1" => '‘Nowadays, it isnt great uncommon to see lenders rapidly adopting a new digital lending strategy to make most popular streamline the web process',

            "enable_testimonial2" => "on",
            "testimonial_img2" => 'avatar.png',
            "testimonial_name2" => 'Rajodiya Infotech',
            "testimonial_about_us2" => 'CEO Rajodiya Infotech',
            "testimonial_description2" => '‘Nowadays, it isnt great uncommon to see lenders rapidly adopting a new digital lending strategy to make most popular streamline the web process',

            "enable_testimonial3" => "on",
            "testimonial_img3" => 'avatar.png',
            "testimonial_name3" => 'Rajodiya Infotech',
            "testimonial_about_us3" => 'CEO Rajodiya Infotech',
            "testimonial_description3" => '‘Nowadays, it isnt great uncommon to see lenders rapidly adopting a new digital lending strategy to make most popular streamline the web process',

            "enable_brand_logo" => "on",
            "brand_logo" => implode(
                ',', [
                    'brand_logo.png',
                    'brand_logo.png',
                    'brand_logo.png',
                    'brand_logo.png',
                    'brand_logo.png',
                    'brand_logo.png',
                ]
            ),

            "quick_link_header_name21" => "About",
            "quick_link_header_name41" => "Company",

            "quick_link_name1" => __('Home Pages'),
            "quick_link_url1" => '#Home Pages',

            "enable_footer_note" => "on",
            "enable_quick_link1" => "on",
            "enable_quick_link2" => "on",
            "enable_quick_link3" => "on",
            "enable_quick_link4" => "on",

            "quick_link_header_name1" => __("Theme Pages"),
            "quick_link_header_name2" => __("About"),
            "quick_link_header_name3" => __("Company"),
            "quick_link_header_name4" => __("Company"),

            "quick_link_name11" => __('Home Pages'),
            "quick_link_name12" => __('Pricing'),
            "quick_link_name13" => __('Contact Us'),
            "quick_link_name14" => __('Team'),

            "quick_link_name21" => __('Blog'),
            "quick_link_name22" => __('Help Center'),
            "quick_link_name23" => __('Sales Tools Catalog'),
            "quick_link_name24" => __('Academy'),

            "quick_link_name31" => __('Terms and Policy'),
            "quick_link_name32" => __('About us'),
            "quick_link_name33" => __('Support'),
            "quick_link_name34" => __('About us'),

            "quick_link_name41" => __('Terms and Policy'),
            "quick_link_name42" => __('About us'),
            "quick_link_name43" => __('Support'),
            "quick_link_name44" => __('About us'),

            "quick_link_url11" => '#Home Pages',
            "quick_link_url12" => '#Home Pages',
            "quick_link_url13" => '#Home Pages',
            "quick_link_url14" => '#Home Pages',

            "quick_link_url21" => '#Blog',
            "quick_link_url22" => '#Blog',
            "quick_link_url23" => '#Blog',
            "quick_link_url24" => '#Blog',

            "quick_link_url31" => '#Terms and Policy',
            "quick_link_url32" => '#Terms and Policy',
            "quick_link_url33" => '#Terms and Policy',
            "quick_link_url34" => '#Terms and Policy',

            "quick_link_url41" => '#About us',
            "quick_link_url42" => '#About us',
            "quick_link_url43" => '#About us',
            "quick_link_url44" => '#About us',

            "footer_logo" => "footer_logo.png",
            "footer_desc" => "Nowadays, it isnt great uncommon to see lenders rapidly adopting a new digital",
            "footer_number" => "(987)654321",

            "enable_footer" => "on",
            "email" => "test@test.com",
            "whatsapp" => "https://api.whatsapp.com/",
            "facebook" => "https://www.facebook.com/",
            "instagram" => "https://www.instagram.com/",
            "twitter" => "https://twitter.com/",
            "youtube" => "https://www.youtube.com/",
            "footer_note" => "© 2021 My Store. All rights reserved",
            "storejs" => "<script>console.log('hello');</script>",

            /*THEME 3*/

        ];

        if ($theme_name == 'theme2') {
            $settings['header_img'] = 'header_img_2.png';
            $settings['subscriber_img'] = "email_subscriber_2.png";
            $settings['footer_logo2'] = "footer_logo2.png";
            $settings['brand_logo'] = implode(
                ',', [
                    'brand_logo2.png',
                    'brand_logo2.png',
                    'brand_logo2.png',
                    'brand_logo2.png',
                    'brand_logo2.png',
                    'brand_logo2.png',
                ]
            );
        }

        if ($theme_name == 'theme3') {
            $settings['header_img'] = 'header_img_3.png';
            $settings['testimonial_img1'] = 'testimonail-img_3.png';
            $settings['testimonial_img2'] = 'testimonail-img_3.png';
            $settings['testimonial_img3'] = 'testimonail-img_3.png';
            $settings['banner_img'] = 'header_img_3.png';
            $settings['enable_banner_img'] = 'on';
            $settings['testimonial_main_heading_title'] = 'StoreGo';
            $settings['footer_logo3'] = "footer_logo3.png";

        }

        if ($theme_name == 'theme4') {
            $settings['header_img'] = 'header_img_4.png';
            $settings['banner_img'] = 'image-big-4.jpg';
            $settings['enable_banner_img'] = 'on';
            $settings['subscriber_img'] = "email_subscriber_2.png";
            $settings['brand_logo'] = implode(
                ',', [
                    'brand_logo4.png',
                    'brand_logo4.png',
                    'brand_logo4.png',
                    'brand_logo4.png',
                    'brand_logo4.png',
                    'brand_logo4.png',
                ]
            );
            $settings['footer_logo4'] = "footer_logo4.png";
        }

        if ($theme_name == 'theme5') {
            $settings['header_img'] = 'header_img_5.png';
            $settings['brand_logo'] = implode(
                ',', [
                    'brand_logo5.png',
                    'brand_logo5.png',
                    'brand_logo5.png',
                    'brand_logo5.png',
                    'brand_logo5.png',
                    'brand_logo5.png',
                ]
            );
            $settings['footer_logo5'] = "footer_logo5.png";
        }

        if ($data->count() > 0) {
            foreach ($data as $row) {
                $settings[$row->name] = $row->value;
            }
        }

        $store = Hotels::where('workspace', $workspace)->first();


        return $settings;
    }

}
