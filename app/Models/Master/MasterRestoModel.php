<?php



namespace App\Models\Master;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class MasterRestoModel extends Model

{

    use HasFactory;



    protected $table = 'miegacoa_keluhan.master_resto';



    protected $primaryKey = 'id';



    protected $fillable = [

        'kode_resto',

        'resto',

        'kode_city',

        'city',

        'kom_resto',

        'rm',

        'email',

        'email_am',

        'eamil_rm',

        'email_mrt',

        'id_regional',

        'id_user_am'

    ];

}

