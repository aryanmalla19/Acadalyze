<?php
namespace App\Policy;

use App\Core\Policy;
use App\Models\User;
use App\Models\Exam;
use App\Models\SubjectsExams;

class MarksPolicy implements Policy
{
    public function view($user, $model): bool
    {
        $student = User::find($model->student_id);
        return $user->school_id === $student->school_id;
    }
    
    public function update($user, $model): bool
    {
        if(!empty($model->subjects_exams_id)){
            $subject_exam = SubjectsExams::find($model->subjects_exams_id);
            $exam = Exam::find($subject_exam->exam_id);
            return $user->school_id === $student->school_id && $user->school_id === $exam->school_id;
        }

        $student = User::find($model->student_id);
        return $user->school_id === $student->school_id;
    }
}