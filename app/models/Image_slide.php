
<?php

class Image_slide extends BaseModel
{
    protected $table = 'image_slide';

    public function images(){
        return $this->belongsToMany('Images', 'image_in_slide', 'slide_id','image_id')->withTimestamps();
    }
} 