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

    public function getAllSchools()
    {
        $schools = $this->schoolModel->getAllSchools();
        $this->sendResponse("success", "All School data fetched successfully", $schools);
    }

    public function getSchoolById(Request $request)
    {
        $
        $school = $this->schoolModel->getSchoolById();
        if($school){
            $this->sendResponse("success", "School data fetched successfully", $school);
            return;
        }
        $this->sendResponse("error", "School with ID $id does not exists", null, 404);
        return;
    }
    

}