<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;

class SubjectsExamsController extends Controller
{
    public $subjectsExamsModel;
    public function __construct()
    {
        $this->subjectsExamsModel = $this->model('SubjectsExams');
    }

    public function index(Request $request)
    {
        $schooolId = $request->getUser()->school_id;
        $subjectsExams = $this->subjectsExamsModel->getAllBySchoolId($schooolId);
        if(empty($exams)){
            $this->sendResponse("error", "Exam data not found", null, 404);
        }
        $this->sendResponse("success", "All Exams fetched sucessfully", $exams);
    }

    public function show(Request $request, $id)
    {
        $subjectsExam = $this->subjectsExamsModel->findById($id);
        if(!empty($subjectsExam)){
            $this->sendResponse("success", "Subject_Exam data fetched successfully", $exam);
        }
        $this->sendResponse("error", "Subject_Exam data with ID $id not found", [], 404);
    }

    public function update(Request $request)
    {
        $data = $request->body;

        $allRules = [
            'subject_id' => 'required',
            'exam_id' => 'required|min:3|max:50',
            'subject_exam_time' => 'required',
            'pass_marks' => 'required|min:10|max:100',
            'full_marks' => 'required|min:10|max:100',
        ];

        $rules = array_intersect_key($allRules, $data);

        $errors = $this->subjectsExamsModel->validate($data, $rules);

        if(!empty($errors)){
            $this->sendResponse("error", "Invalid entry !", $this->subjectsExamsModel->getErrors(), 400);
        }

        try {
            if(!$this->subjectsExamsModel->updateById($id, $data)){
                $this->sendResponse("error", "No data provided to update", null, 404);
            }
            $this->sendResponse("success", "Subject_Exam with ID $id updated successfully", $data);
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
            } else {
                $this->sendResponse("error", "Database error occurred", ['details' => $e->getMessage()], 500);
            }
        } catch (\RuntimeException $e) {
            $this->sendResponse("error", $e->getMessage(), [], 500);
        }
    }

    public function create(Request $request)
    {
        $data = $request->body + [
            'subject_id' => '',
            'exam_id' => '',
            'subject_exam_time' => '',
            'pass_marks' => '',
            'full_marks' => '',
        ];


        // Define validation rules
        $rules = [
            'subject_id' => 'required',
            'exam_id' => 'required|min:3|max:50',
            'subject_exam_time' => 'required',
            'pass_marks' => 'required|min:10|max:100',
            'full_marks' => 'required|min:10|max:100',
        ];

        if (!$this->subjectsExamsModel->validate($data, $rules)) {
            $this->sendResponse("error", $this->subjectsExamsModel->getErrors(), [], 400);
        }

        // Create the new Exam
        $newSubjectsExamId = $this->subjectsExamsModel->create($data['subject_id'], $data['exam_id'], $data['subject_exam_time'], $data['pass_marks'], $data['full_marks']);
        if($newSubjectsExamId){
            $this->sendResponse("success", "Registration successful with Subjects_Exam ID $newSubjectsExamId", null);
        }

        $this->sendResponse("error", "Internal Server Error", [], 500);
    }

    public function destroy(Request $request, $id)
    {
        $deletedId = $this->subjectsExamsModel->deleteById($id);
        if(!$deletedId){
        $this->sendResponse("error", "Subject_Exam data with ID $id not found", [], 404);
        }
        $this->sendResponse("success", "Subject_Exam data deleted successfully", []);
    }
}