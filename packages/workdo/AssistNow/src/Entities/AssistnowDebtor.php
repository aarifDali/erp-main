<?php

namespace Workdo\AssistNow\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssistnowDebtor extends Model
{
    use HasFactory;

    protected $table = 'assistnow_debtors';

    protected $fillable = ['name'];

    public function clients()
    {
        return $this->hasMany(AssistnowClient::class, 'debtor_id');
    }

    public function delete()
    {
        if ($this->clients()->exists()) {
            throw new \Exception("Cannot delete debtor: associated clients exist. Delete them first.");
        }

        return parent::delete();
    }
}
