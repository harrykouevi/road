<?php
/*
 * File name: RoadReportTypeRepository.php
 * Last modified: 2025.04.27 at 11:15:50
 * Author: 
 * Copyright (c) 2025
 */

namespace App\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use App\Models\ReportType;

/**
 * Class RoadReportTypeRepository
 * @package App\Repositories
 *
 * @method Salon findWithoutFail($id, $columns = ['*'])
 * @method Salon find($id, $columns = ['*'])
 * @method Salon first($columns = ['*'])
 */
class RoadReportTypeRepository extends BaseRepository
{
    /**
     * Configure the Model
     **/
    public function model(): string
    {
        return ReportType::class;
    }
}
