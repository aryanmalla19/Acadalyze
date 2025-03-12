<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;

class ExamController extends Controller
{
    public $examModel;
    public function __construct()
    {
        $this->examModel = $this->model('Exam');
    }

    public function index(Request $request): void
    {
        $schooolId = $request->getUser()->school_id;
        $exams = $this->examModel->getAllBySchoolId($schooolId);
        if(empty($exams)){
            $this->sendResponse("error", "Exam data not found", null, 404);
        }
        $this->sendResponse("success", "All Exams fetched sucessfully", $exams);
    }

    public function show(Request $request, $id): void
    {
        $exam = $this->examModel->findById($id);
        if(!empty($exam)){
            $this->sendResponse("success", "Exam data fetched successfully", $exam);
        }
        $this->sendResponse("error", "Exam data not found", null, 404);
    }

    public function create(Request $request): void
    {
        
        $data = $request->body + [
            'school_id' => '',
            'exam_name' => '',
            'exam_date' => '',
        ];


        // Define validation rules
        $rules = [
            'school_id' => 'required',
            'exam_name' => 'required|min:3|max:50',
            'exam_date' => 'required',
        ];

        if (!$this->examModel->validate($data, $rules)) {
            $this->sendResponse("error", $this->examModel->getErrors(), [], 400);
        }

        // Create the new Exam
        $newExamId = $this->examModel->create($data['school_id'], $data['exam_name'], $data['exam_date']);
        if($newExamId){
            $this->sendResponse("success", "Registration successful with School ID $newExamId", null);
        }

        $this->sendResponse("error", "Internal Server Error", [], 500);
    }

    public function update(Request $request, $id):void 
    {
        $data = $request->body;

        $allRules = [
            'school_id' => 'required',
            'exam_name' => 'required|min:2|max:30',
            'exam_date' => 'required',
        ];

        $rules = array_intersect_key($allRules, $data);

        $errors = $this->examModel->validate($data, $rules);

        if(!empty($errors)){
            $this->sendResponse("error", "Invalid entry !", $this->examModel->getErrors(), 400);
        }

        try {
            if(!$this->examModel->updateById($id, $data)){
                $this->sendResponse("error", "No data provided to update", null, 404);
            }
            $this->sendResponse("success", "Exam with ID $id updated successfully", $data);
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

    public function destroy(Request $request, $id): void
    {
        $deletedId = $this->examModel->deleteById($id);
        if(!$deletedId){
        $this->sendResponse("error", "Could not found Exam with ID $id", [], 404);
        }
        $this->sendResponse("success", "Successfully deleted Exam with ID $id", null);
    }
}