<?php

namespace Workdo\Holidayz\Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;

class PermissionTableSeeder extends Seeder
{
     public function run()
    {
        Model::unguard();
        Artisan::call('cache:clear');
        $module = 'Holidayz';

        $permissions  = [
            'holidayz dashboard manage',
            'holidayz manage',
            'themes manage',
            'themes edit',
            'hotel management manage',
            'services manage',
            'services create',
            'services edit',
            'services delete',
            'rooms manage',
            'rooms create',
            'rooms edit',
            'rooms update',
            'rooms delete',
            'type manage',
            'feature manage',
            'feature create',
            'feature edit',
            'feature delete',
            'setup manage',
            'custom pages manage',
            'custom pages create',
            'custom pages edit',
            'custom pages delete',
            'facilities manage',
            'facilities create',
            'facilities edit',
            'facilities delete',
            'rooms booking manage',
            'rooms booking create',
            'rooms booking edit',
            'rooms booking show',
            'rooms booking delete',
            'customer coupon manage',
            'customer coupon show',
            'customer coupon create',
            'customer coupon edit',
            'customer coupon delete',
            'hotel customer manage',
            'hotel customer create',
            'hotel customer edit',
            'hotel customer delete',
        ];

        $company_role = Role::where('name','company')->first();
        foreach ($permissions as $key => $value)
        {
            $check = Permission::where('name',$value)->where('module',$module)->exists();
            if($check == false)
            {
                $permission = Permission::create(
                    [
                        'name' => $value,
                        'guard_name' => 'web',
                        'module' => $module,
                        'created_by' => 0,
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s')
                    ]
                );
                if(!$company_role->hasPermission($value))
                {
                    $company_role->givePermission($permission);
                }
            }
        }
    }
}
