<?php

/**
 * Class BaseModel
 */
class BaseModel extends Eloquent {

    /**
     * Check for type
     *
     * @param int $type
     *
     * @return bool
     */
    public function is($type) {
        return ((bool) ($this->type & $type));
    }

    /**
     * Add type if not exists
     *
     * @param int $type
     *
     * @return $this
     */
    public function add($type) {

        if (!$this->is($type)) {
            $this->type |= $type;
        }

        return $this;
    }

    /**
     * Remove type if exists
     *
     * @param int $type
     *
     * @return $this
     */
    public function remove($type) {

        if ($this->is($type)) {
            $this->type -= $type;
        }

        return $this;
    }

//    public function scopeOfType($query, $type)
//    {
//        return $query->whereType($type);
//    }
} 