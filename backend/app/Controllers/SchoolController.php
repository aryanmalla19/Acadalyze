<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Core\Request;
use App\Middleware\SchoolAccessMiddleware;

class SchoolController extends Controller
{
    private $schoolModel;

    public function __construct()
    {
        $this->schoolModel = $this->model('School');
    }

    public function getSchool(Request $request, $id)
    {
        $school = $this->schoolModel->getSchoolById($id);
        if($school){
            $this->sendResponse("success", "School data fetched successfully", $school);
            return;
        }
        $this->sendResponse("error", "School with ID $id does not exists", null, 404);
        return;
    }


    public function createSchool(Request $request)
    {
        $data = $request->body + [
            'school_email' => '',
            'school_name' => '',
            'established_date' => '',
            'telephone_number' => '',
            'address' => '',
        ];

        // Define validation rules
        $rules = [
            'school_email' => 'required|email',
            'school_name' => 'required|min:3|max:50',
            'established_date' => 'required|min:6|max:20',
            'telephone_number' => 'required|min:2|max:30',
            'address' => 'required|min:2|max:30',
        ];
        if (!$this->schoolModel->validate($data, $rules)) {
            $this->sendResponse("error", $this->schoolModel->getErrors(), [], 400);
        }

        try {
            $userModel = $this->model('User');
            if($userModel->getSchoolByUserId($request->user['user_id'])){
                $this->sendResponse("error", "You are already associated with one school", [], 409);
            }

            // Check if email is already registered
            if ($this->schoolModel->getSchoolByEmail($data['school_email'])) {
                $this->sendResponse("error", "School Email (". $data['school_email'] .") is already registered", [], 409);
            }

            // Create the new School
            $newSchoolID = $this->schoolModel->createSchool($data['school_name'], $data['school_email'], $data['established_date'], $data['telephone_number'], $data['address']);
            if($newSchoolID){
                $userModel->setSchoolId($request->user['user_id'], $newSchoolID);
            }
            $this->sendResponse("success", "Registration successful with School ID $newSchoolID", null);
        } catch (Exception $e) {
            $this->sendResponse("error", "Internal Server Error", [], 500);
        }
    }

    

}