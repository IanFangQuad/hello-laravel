<?php

namespace App\Repositories;

use App\Models\Member;

class MemberRepository{

    public function create(array $params)
    {
        $result = Member::create($params);

        return $result;
    }
}
