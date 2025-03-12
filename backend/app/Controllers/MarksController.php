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