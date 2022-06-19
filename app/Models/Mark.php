<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mark extends Model
{
    use HasFactory, SoftDeletes;

    public function getUser(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function getTerm(){
        return $this->belongsTo(Term::class, 'term_id', 'id');
    }
    public function getSubject(){
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }
}
