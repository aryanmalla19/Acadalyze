<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\User;

class SubjectController extends Controller
{
    public $subjectModel;

    public function __construct()
    {
        $this->subjectModel = $this->model('Subject');
    }

    public function show(Request $request, $id)
    {
        $subject = $this->subjectModel->findById($id);
        if(empty($subject)){
            $this->sendResponse('error', "Not found Subject with ID $id", null, 404);
        }
        $this->sendResponse('success', "Subject with ID $id fetched sucessfully", $subject);
    }

    public function index(Request $request)
    {
        $userId = $request->user['user_id'];
        $schoolId = User::find($userId)->school_id;

        $subjects = $this->subjectModel->getAllBySchoolId($schoolId);
        if(empty($subjects)){
            $this->sendResponse('error', "Subjects not found. Your school does not have any subjects.", null, 404);
        }
        $this->sendResponse('success', "All Subjects fetched sucessfully", $subjects);
    }

    public function create(Request $request)
    {
        $data = $request->body;

        // Define validation rules
        $rules = [
            'class_id' => 'required',
            'teacher_id' => 'required',
            'subject_name' => 'required|min:2|max:20',
        ];

        if (!$this->subjectModel->validate($data, $rules)) {
            $this->sendResponse("error", $this->subjectModel->getErrors(), [], 400);
        }

        try {
            // Create the new user
            $newSubject = $this->subjectModel->create($data['class_id'], $data['teacher_id'], $data['subject_name']);

            $this->sendResponse("success", "New Subject registration successful", $newSubject);
        } catch (Exception $e) {
            $this->sendResponse("error", "Internal Server Error", [], 500);
        }

        
    }

    public function update(Request $request, $id)
    {
        $data = $request->body;

        // Define validation rules for updatable fields
        $allRules = [
            'class_id' => 'required',
            'teacher_id' => 'required',
            'subject_name' => 'required|min:2|max:30',
        ];

        // Filter rules to only include those for fields present in $data
        $rules = array_intersect_key($allRules, $data);

        // Validate the data (only for provided fields)
        $errors = $this->subjectModel->validate($data, $rules);

        try {
            
            if(!$this->subjectModel->updateSubject($id, $data)){
                $this->sendResponse("error", "No data provided to update", null, 404);
            }
            $this->sendResponse("success", "Subject with ID $id updated successfully", $data);
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

    public function destroy(Request $request, $id)
    {
        $deletedId = $this->subjectModel->delete($id);
        if(!$deletedId){
            $this->sendResponse("error", "Couldn't delete. Subject with ID $id does not exists", null, 404);
        }
        $this->sendResponse("success", "Successfully deleted Subject with ID $id", null);
    }
}