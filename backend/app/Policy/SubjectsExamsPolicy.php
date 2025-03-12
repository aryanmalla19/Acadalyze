<?php
namespace App\Policy;

use App\Core\Policy;
use App\Models\Classes;
use App\Models\Exam;
use App\Models\Subject;

class SubjectsExamsPolicy implements Policy
{
    public function view($user, $model): bool
    {
        $exam = Exam::find($model->exam_id);
        return $user->school_id === $exam->school_id;
    }
    
    public function update($user, $model): bool
    {
        $exam = Exam::find($model->exam_id);
        if($exam->school_id != $user->school_id){
            return false;
        }
        $subject = Subject::find($model->subject_id);
        $class = Classes::find($subject->class_id);
        if($class->school_id != $user->school_id){
            return false;
        }
        return true;
    }
}