<?php
/*
 * File name: RoadReportRepository.php
 * Last modified: 2025.04.2 at 00:15:50
 * Author: 
 * Copyright (c) 2025
 */

namespace App\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use App\Models\RoadReport;

/**
 * Class RoadReportRepository
 * @package App\Repositories
 *
 * @method Salon findWithoutFail($id, $columns = ['*'])
 * @method Salon find($id, $columns = ['*'])
 * @method Salon first($columns = ['*'])
 */
class RoadReportRepository extends BaseRepository
{
    /**
     * Configure the Model
     **/
    public function model(): string
    {
        return RoadReport::class;
    }
}
