<?php

namespace Workdo\Holidayz\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Workdo\LandingPage\Entities\MarketplacePageSetting;


class MarketPlaceSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $module = 'Holidayz';

        $data['product_main_banner'] = '';
        $data['product_main_status'] = 'on';
        $data['product_main_heading'] = 'Hotel&Room Management';
        $data['product_main_description'] = '<p>Hotel&Room Management provide Facilities people all over the world and make booking hotel rooms online easy and fast. Easily manage your hotel&room management, Room Types, Room Features, Facilities, and Room Booking from one intuitive dashboard.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = 'One Solution For All Your Hotel&Room Management Needs';
        $data['dedicated_theme_description'] = '<p>Simplify and automate your hotel operations with a highly flexible and feature-rich system.</p>';
        $data['dedicated_theme_sections'] = '[
                                                {
                                                    "dedicated_theme_section_image": "",
                                                    "dedicated_theme_section_heading": "The Smartest Way To Manage Your Hotel&Room Management",
                                                    "dedicated_theme_section_description": "<p>Holidayz monitors your hotel&room management performance like a coach and lets you know whether you are on track. Get real time reports about every hotel&room management activity you make, make smarter decisions, and manage your business’s general hotel&room management more efficiently.<\/p>",
                                                    "dedicated_theme_section_cards": {
                                                    "1": {
                                                        "title": "Manage All Your Hotel&Room Management From One Place",
                                                        "description": "Manage every aspect of your business hotel&room management from one place. Monitor your hotel&room management, Room Booking invoice and customer from a single comprehensive dashboard."
                                                    },
                                                    "2": {
                                                    "title": "Access Your Customers’ Information",
                                                        "description": "Easily view and control the details of all your customers. Create user accounts and edit existing user information, manage your contacts, and do a lot more from anywhere - with a single tool."
                                                    },
                                                    "3": {
                                                    "title": "Manage All Your Hotel&Room Management Management Tasks Quickly And Easily From One Place",
                                                        "description": "Control and keep track of your invoices with ease. Easily track current trends, room details, and more. manage hotel theme, manage hotel amenities, and carry out complex tasks in a few simple clicks."
                                                    }
                                                    }
                                                },
                                                {
                                                    "dedicated_theme_section_image": "",
                                                    "dedicated_theme_section_heading": "Manage Your Hotel&Room Management From One Place",
                                                    "dedicated_theme_section_description": "<p><\/p>",
                                                    "dedicated_theme_section_cards": {
                                                    "1": {
                                                        "title": "Oprate a Fast and Easy Holidaz process",
                                                        "description": "Make your Booking process fast and convenient for your customers. select date of when you want to stay in hotel room and select the room your customers want to stay and after click on Book Now."
                                                    },
                                                    "2": {
                                                    "title": "Manage Your Payments Easily",
                                                        "description": "Get paid for work done, fast. Integrate several payment options for diverse customers and make the payment process stress-free. Easily safeguard your customers’ payment by using Stripe, PayPal, Flutterwave, and more."
                                                    },
                                                    "3": {
                                                    "title": "Get Instant Notifications",
                                                        "description": "Integrate the Twilio app and never miss an important notification again. Get notified when tasks or room booking are completed and get notifications about meetings and new room booking sent to your mobile phone via text."
                                                    }
                                                    }
                                                },
                                                {
                                                    "dedicated_theme_section_image": "",
                                                    "dedicated_theme_section_heading": "Modify Vital Hotel&Room Management Info With Ease",
                                                    "dedicated_theme_section_description": "<p>Modify and update your generated hotel&room management room types with ease. Add new room booking and room details without stress. Holidayz allows you to create and maintain the data of each customer. You get access to all essential information through a well-maintained format.<\/p>",
                                                    "dedicated_theme_section_cards": {
                                                    "1": {
                                                        "title": null,
                                                        "description": null
                                                    }
                                                    }
                                                },
                                                {
                                                    "dedicated_theme_section_image": "",
                                                    "dedicated_theme_section_heading": "Essential Information At Your Fingertips",
                                                    "dedicated_theme_section_description": "<p>Holidays also provide filter facility to customers. Customer easily get him/her choice rooms by using filter option and search your room option.<\/p>",
                                                    "dedicated_theme_section_cards": {
                                                    "1": {
                                                        "title": null,
                                                        "description": null
                                                    }
                                                    }
                                                }
                                            ]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"Hotel&Room Management"},{"screenshots":"","screenshots_heading":"Hotel&Room Management"},{"screenshots":"","screenshots_heading":"Hotel&Room Management"},{"screenshots":"","screenshots_heading":"Hotel&Room Management"},{"screenshots":"","screenshots_heading":"Hotel&Room Management"}]';
        $data['addon_heading'] = 'Why choose dedicated modulesfor Your Business?';
        $data['addon_description'] = '<p>With Dash, you can conveniently manage all your business functions from a single location.</p>';
        $data['addon_section_status'] = 'on';
        $data['whychoose_heading'] = 'Why choose dedicated modulesfor Your Business?';
        $data['whychoose_description'] = '<p>With Dash, you can conveniently manage all your business functions from a single location.</p>';
        $data['pricing_plan_heading'] = 'Empower Your Workforce with DASH';
        $data['pricing_plan_description'] = '<p>Access over Premium Add-ons for Accounting, HR, Payments, Leads, Communication, Management, and more, all in one place!</p>';
        $data['pricing_plan_demo_link'] = '#';
        $data['pricing_plan_demo_button_text'] = 'View Live Demo';
        $data['pricing_plan_text'] = '{"1":{"title":"Pay-as-you-go"},"2":{"title":"Unlimited installation"},"3":{"title":"Secure cloud storage"}}';
        $data['whychoose_sections_status'] = 'on';
        $data['dedicated_theme_section_status'] = 'on';

        foreach($data as $key => $value){
            if(!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', $module)->exists()){
                MarketplacePageSetting::updateOrCreate(
                [
                    'name' => $key,
                    'module' => $module

                ],
                [
                    'value' => $value
                ]);
            }
        }
    }
}
