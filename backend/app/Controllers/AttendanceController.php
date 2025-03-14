<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;

class AttendanceController extends Controller
{
    public $attendanceModel;
    public function __construct()
    {
        $this->attendanceModel = $this->model('Attendance');
    }
    
    public function index(Request $request)
    {
        $schoolId = $request->getUser()->school_id;
        $studentId = $request->getQuery('student_id');
        $classId = $request->getQuery('class_id');
        $startDate = $request->getQuery('start_date'); // User-provided start date
        $endDate = $request->getQuery('end_date');     // User-provided end date

        if (!$schoolId) {
            $this->sendResponse("error", "You are not associated with any school", null, 403);
        }

        // Validate date inputs if provided
        if ($startDate && !strtotime($startDate)) {
            $this->sendResponse("error", "Invalid start_date format. Use YYYY-MM-DD.", null, 400);
        }
        if ($endDate && !strtotime($endDate)) {
            $this->sendResponse("error", "Invalid end_date format. Use YYYY-MM-DD.", null, 400);
        }

        // Use default date range (last 1 month) if both start_date and end_date are not provided
        if (!$startDate && !$endDate) {
            $startDate = date('Y-m-d', strtotime('-1 month'));
            $endDate = date('Y-m-d');
        }

        // Fetch attendance data based on available filters
        if ($studentId && $classId) {
            $attendances = $this->attendanceModel->getByStudentClassAndDateRange($schoolId, $studentId, $classId, $startDate, $endDate);
        } elseif ($studentId) {
            $attendances = $this->attendanceModel->getByStudentAndDateRange($schoolId, $studentId, $startDate, $endDate);
        } elseif ($classId) {
            $attendances = $this->attendanceModel->getByClassAndDateRange($schoolId, $classId, $startDate, $endDate);
        } else {
            $attendances = $this->attendanceModel->getAllBySchoolIdAndDateRange($schoolId, $startDate, $endDate);
        }

        if (empty($attendances)) {
            $this->sendResponse("error", "Attendance data not found.", [], 404);
        }

        $this->sendResponse("success", "Attendance data fetched successfully from $startDate to $endDate", $attendances);
    }

    

    public function show(Request $request, $id)
    {
        $attendance = $this->attendanceModel->findById($id);
        if(empty($attendance)){
            $this->sendResponse("error", "Attendance data not found.", [], 404);
        }
        $this->sendResponse("success", "Attendance data with ID $id fetched successfully", $attendance);
    }

    public function create(Request $request)
    {
        $data = $request->body + [
            'student_id' => '',
            'class_id' => '',
            'attendance_date' => '',
            'status' => '',
        ];

        // Define validation rules
        $rules = [
            'student_id' => 'required',
            'class_id' => 'required',
            'attendance_date' => 'required',
            'status' => 'required',
        ];

        if (!$this->attendanceModel->validate($data, $rules)) {
            $this->sendResponse("error", $this->attendanceModel->getErrors(), [], 400);
        }

        $newMarksID = $this->attendanceModel->create($data['student_id'], $data['class_id'], $data['attendance_date'], $data['status']);
        $this->sendResponse("success", "Successfully with Attendance ID $newMarksID", null);
    }

    public function update(Request $request, $id)
    {
        $data = $request->body;

        $allRules = [
           'student_id' => 'required',
            'class_id' => 'required',
            'attendance_date' => '',
            'status' => 'required',
        ];

        $rules = array_intersect_key($allRules, $data);

        $errors = $this->attendanceModel->validate($data, $rules);

        if(!empty($errors)){
            $this->sendResponse("error", "Invalid entry !", $this->attendanceModel->getErrors(), 400);
        }

        try {
            if(!$this->attendanceModel->updateById($id, $data)){
                $this->sendResponse("error", "No data provided to update", null, 404);
            }
            $this->sendResponse("success", "Attendance data with ID $id updated successfully", $data);
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
        $deletedId = $this->attendanceModel->deleteById($id);
        if(!$deletedId){
        $this->sendResponse("error", "Could not fetch Attendance data with ID $id", [], 404);
        }
        $this->sendResponse("success", "Successfully deleted Attendance with ID $id", null);
    }
}