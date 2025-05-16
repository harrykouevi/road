<?php
/*
 * File name: UserRepository.php
 * Last modified: 2025.04.25 at 00:22:50
 * Author: 
 * Copyright (c) 2024
 */

namespace App\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use App\Models\Salon;
use App\Models\User;

/**
 * Class UserRepository
 * @package App\Repositories
 *
 * @method  findWithoutFail($id, $columns = ['*'])
 * @method  find($id, $columns = ['*'])
 * @method  first($columns = ['*'])
 */
class UserRepository extends BaseRepository
{
    /**
     * Configure the Model
     **/
    public function model(): string
    {
        return User::class;
    }
}
