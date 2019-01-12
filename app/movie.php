<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class movie extends Model
{

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getUniqueTitle()
    {
        return $this->uniqueTitle;
    }
}
