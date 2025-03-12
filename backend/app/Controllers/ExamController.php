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
        if(empty($exam)){
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


        try {
            // Create the new Exam
            $newExamId = $this->examModel->create($data['school_id'], $data['school_email'], $data['established_date'], $data['telephone_number'], $data['address']);
            if($newExamId){
                $userModel->setSchoolId($request->user['user_id'], $newExamId);
            }
            $this->sendResponse("success", "Registration successful with School ID $newExamId", null);
        } catch (Exception $e) {
            $this->sendResponse("error", "Internal Server Error", [], 500);
        }
    }

    public function update(Request $request):void 
    {

    }

    public function destroy(Request $request): void
    {

    }
}