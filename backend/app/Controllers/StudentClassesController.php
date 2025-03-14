<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;

class StudentClassesController extends Controller
{
    public $studentClassesModel;
    public function __construct()
    {
        $this->studentClassesModel = $this->model('StudentsClasses');
    }

    public function index(Request $request)
    {
        $schoolId = $request->getUser()->school_id;
        $classId = $request->getQuery('class_id');
    
        if (!$schoolId) {
            $this->sendResponse("error", "You are not associated with any school", null, 403);
        }
    
        if ($classId) {
            $studentClasses = $this->studentClassesModel->getAllBySchoolIdAndClassId($schoolId, $classId);
        } else {
            $studentClasses = $this->studentClassesModel->getAllBySchoolId($schoolId);
        }
    
        if (empty($studentClasses)) {
            $this->sendResponse("error", "Student_Classes data not found", null, 404);
        }
    
        $this->sendResponse("success", "All Student_Classes data fetched successfully", $studentClasses);
    }
    

    public function show(Request $request, $id)
    {
        $studentClass = $this->studentClassesModel->findById($id);
        if(!empty($studentClass)){
            $this->sendResponse("success", "Student_Class data fetched successfully", $studentClass);
        }
        $this->sendResponse("error", "Student_Class data with ID $id not found", [], 404);
    }

    public function update(Request $request, $id)
    {
        $data = $request->body;

        $allRules = [
            'student_id' => 'required',
            'class_id' => 'required|min:3|max:50',
            'enrollment_date' => 'required',
        ];

        $rules = array_intersect_key($allRules, $data);

        $errors = $this->studentClassesModel->validate($data, $rules);
        if(!empty($errors)){
            $this->sendResponse("error", "Invalid entry !", $this->studentClassesModel->getErrors(), 400);
        }

        try {
            if(!$this->studentClassesModel->updateById($id, $data)){
                $this->sendResponse("error", "No data provided to update", null, 404);
            }
            $this->sendResponse("success", "Student_Class with ID $id updated successfully", $data);
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
            'student_id' => '',
            'class_id' => '',
            'enrollment_date' => '',
        ];

        // Define validation rules
        $rules = [
            'student_id' => 'required',
            'class_id' => 'required',
            'enrollment_date' => 'required',
        ];

        if (!$this->studentClassesModel->validate($data, $rules)) {
            $this->sendResponse("error", $this->studentClassesModel->getErrors(), [], 400);
        }

        // Create the new Exam
        $newSubjectsExamId = $this->studentClassesModel->create($data['student_id'], $data['class_id'], $data['enrollment_date']);
        if($newSubjectsExamId){
            $this->sendResponse("success", "Registration successful with Subjects_Exam ID $newSubjectsExamId", null);
        }

        $this->sendResponse("error", "Internal Server Error", [], 500);
    }

    public function destroy(Request $request, $id)
    {
        $deletedId = $this->studentClassesModel->deleteById($id);
        if(!$deletedId){
        $this->sendResponse("error", "Student_Classes data with ID $id not found", [], 404);
        }
        $this->sendResponse("success", "Student_Classes data deleted successfully", []);
    }
}