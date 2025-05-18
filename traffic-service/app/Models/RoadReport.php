<?php

namespace App\Models;

use Carbon\Carbon;
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

    protected $with = ['reporttype'];

    public $table = 'road_reports'; 
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user',
        'description',
        'image_path', // max 2MB
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
        'image' => 'sometimes|string', // Base64
        'latitude' => 'required|numeric|min:-200|max:200',
        'longitude' => 'required|numeric|min:-200|max:200',
    ];


    protected $fieldSearchable = [
        'description',
        'latitude',
        'longitude',
    ];


       /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user' => 'array',
        'report_type' => ReportType::class, // Transformation en objet ReportType
    ];

    // public function getCreatedAtAttribute($value)
    // {
    //     return Carbon::parse($value)->format('d/m/Y H:i:s');
    // }

    // public function getUpdatedAtAttribute($value)
    // {
    //     return Carbon::parse($value)->format('d/m/Y H:i:s');
    // }

    public function getUserAttribute($value)
    {
        $user = json_decode($value, true); // décodage en tableau

        if (isset($user['created_at']) ) {
            if (str_contains($user['created_at'], 'T') && str_ends_with($user['created_at'], 'Z')) {
                $user['created_at'] = Carbon::parse($user['created_at'])->format('d/m/Y H:i:s');
            }
        }

        if (isset($user['updated_at'])) {
            if (str_contains($user['updated_at'], 'T') && str_ends_with($user['updated_at'], 'Z')) {
                $user['updated_at'] = Carbon::parse($user['updated_at'])->format('d/m/Y H:i:s');
            }
        }

        return $user;
    }

    public function getImageUrlAttribute()
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }

    /**
     * Add Media to api results
     * @return bool
     */
    public function getHasMediaAttribute(): bool
    {
        return $this->hasMedia('image');
    }

    public function reporttype()
    {
        return $this->belongsTo(ReportType::class ,'report_type_id' , 'id' );
    }

    
}
