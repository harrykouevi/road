<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia ;
use Spatie\MediaLibrary\MediaCollections\Models\Media ;
use Laravel\Sanctum\HasApiTokens ;
use Spatie\MediaLibrary\HasMedia;

use Illuminate\Database\Eloquent\Model;

class RoadReport extends Model  implements HasMedia
{
    use HasFactory, InteractsWithMedia ;


    public $table = 'road_reports'; 
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'description',
        'image', // max 2MB
        'latitude', 
        'longitude',
        'report_type_id',
        'user_id'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static array $rules =[
        'description' => 'sometimes|string|max:255',
        'report_type_id' => 'required|exists:report_types,id',
        'image' => 'sometimes|file|image|max:2048', // max 2MB
        'latitude' => 'required|numeric|min:-200|max:200',
        'longitude' => 'required|numeric|min:-200|max:200',
    ];


    protected $fieldSearchable = [
        'description',
        'latitude',
        'longitude',
    ];


    //    /**
    //  * The attributes that should be casted to native types.
    //  *
    //  * @var array
    //  */
    // protected $casts = [
    //     'image' => 'string',
    //     'description'  => 'string',
    //     'report_type' => ReportType::class, // Transformation en objet ReportType
    //     'latitude' => 'double',
    //     'longitude' => 'double',
    // ];

    /**
     * Add Media to api results
     * @return bool
     */
    public function getHasMediaAttribute(): bool
    {
        return $this->hasMedia('image');
    }

    
}
