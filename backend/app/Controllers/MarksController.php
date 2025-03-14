<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;

class MarksController extends Controller
{
    public $marksModel;
    public function __construct()
    {
        $this->marksModel = $this->model('Marks');
    }

    public function index(Request $request)
    {
        $schoolId = $request->getUser()->school_id;
    
        if (!$schoolId) {
            $this->sendResponse("error", "You are not associated with any school", null, 403);
        }
    
        // Get query parameters
        $studentId = $request->getQuery('student_id');
        $classId = $request->getQuery('class_id');
        $subjectsExamsId = $request->getQuery('subjects_exams_id');
        $examId = $request->getQuery('exam_id');  // New exam_id query param
    
        // Fetch data based on provided filters
        if ($studentId && $classId && $subjectsExamsId && $examId) {
            $marks = $this->marksModel->getAllByStudentClassSubjectsExamsAndExamId($schoolId, $studentId, $classId, $subjectsExamsId, $examId);
        } elseif ($studentId && $classId && $examId) {
            $marks = $this->marksModel->getAllByStudentClassAndExamId($schoolId, $studentId, $classId, $examId);
        } elseif ($studentId && $subjectsExamsId && $examId) {
            $marks = $this->marksModel->getAllByStudentSubjectsExamsAndExamId($schoolId, $studentId, $subjectsExamsId, $examId);
        } elseif ($studentId && $examId) {
            $marks = $this->marksModel->getAllByStudentAndExamId($schoolId, $studentId, $examId);
        } elseif ($classId && $examId) {
            $marks = $this->marksModel->getAllByClassIdAndExamId($schoolId, $classId, $examId);
        } elseif ($studentId && $classId) {
            $marks = $this->marksModel->getAllByStudentAndClass($schoolId, $studentId, $classId);
        } elseif ($studentId && $subjectsExamsId) {
            $marks = $this->marksModel->getAllByStudentAndSubjectsExams($schoolId, $studentId, $subjectsExamsId);
        } elseif ($examId) {
            $marks = $this->marksModel->getAllByExamId($schoolId, $examId);
        }elseif ($subjectsExamsId) {
            $marks = $this->marksModel->getAllBySubjectsExamId($schoolId, $subjectsExamsId);
        } elseif ($classId) {
            $marks = $this->marksModel->getAllByClassId($schoolId, $classId);
        } elseif ($studentId) {
            $marks = $this->marksModel->getAllByStudentId($schoolId, $studentId);
        } else {
            $marks = $this->marksModel->getAllBySchoolId($schoolId);
        }
    
        if (empty($marks)) {
            $this->sendResponse("error", "Marks data not found.", [], 404);
        }
    
        $this->sendResponse("success", "Marks data fetched successfully", $marks);
    }
    

    public function show(Request $request, $id)
    {
        $marks = $this->marksModel->findById($id);
        if(empty($marks)){
            $this->sendResponse("error", "Marks data not found.", [], 404);
        }
        $this->sendResponse("success", "Marks data with ID $id fetched successfully", $marks);
    }

    public function create(Request $request)
    {
        $data = $request->body + [
            'student_id' => '',
            'subjects_exams_id' => '',
            'marks_obtained' => '',
        ];

        // Define validation rules
        $rules = [
            'student_id' => 'required',
            'subjects_exams_id' => 'required',
            'marks_obtained' => 'required',
        ];

        if (!$this->marksModel->validate($data, $rules)) {
            $this->sendResponse("error", $this->marksModel->getErrors(), [], 400);
        }

        $newMarksID = $this->marksModel->create($data['student_id'], $data['subjects_exams_id'], $data['marks_obtained']);
        $this->sendResponse("success", "Successfully with Marks ID $newMarksID", null);
    }

    public function update(Request $request, $id)
    {
        $data = $request->body;

        $allRules = [
            'student_id' => 'required',
            'subjects_exams_id' => 'required',
            'marks_obtained' => 'required',
        ];

        $rules = array_intersect_key($allRules, $data);

        $errors = $this->marksModel->validate($data, $rules);

        if(!empty($errors)){
            $this->sendResponse("error", "Invalid entry !", $this->marksModel->getErrors(), 400);
        }

        try {
            if(!$this->marksModel->updateById($id, $data)){
                $this->sendResponse("error", "No data provided to update", null, 404);
            }
            $this->sendResponse("success", "Marks data with ID $id updated successfully", $data);
        } catch (\InvalidArgumentException $e) {
            $this->sendResponse("error", $e->getMessage(), [], 400);
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000' && strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $field = $this->extractDuplicateField($e->getMessage());
                $this->sendResponse(
                    "error",
                    "Duplicate value detected",
                    ['field' => $field, 'value' => $data[$field] ?? 'unknown'],
                    409
                );
            }
        } catch (\RuntimeException $e) {
            $this->sendResponse("error", $e->getMessage(), [], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        $deletedId = $this->marksModel->deleteById($id);
        if(!$deletedId){
        $this->sendResponse("error", "Could not fetch Marks data with ID $id", [], 404);
        }
        $this->sendResponse("success", "Successfully deleted Marks with ID $id", null);
    }

}