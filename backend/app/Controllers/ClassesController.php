<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\User;

class ClassesController extends Controller
{
    public $classesModel;

    public function __construct()
    {
        $this->classesModel = $this->model('Classes');
    }

    public function show(Request $request, $id): void
    {
        $class = $this->classesModel->findById($id);
        if(!$class){
            $this->sendResponse("error", "Class with ID $id not found", null, 404); 
        }
        $this->sendResponse("success", "Class with ID $id fetched", $class); 
    }

    public function index(Request $request): void
    {
        $schoolId = User::find($request->user['user_id'])->school_id;
        $classes = $this->classesModel->getAllBySchoolId($schoolId);
        if(!$classes){
            $this->sendResponse("error", "No Classes founded. School ID $schoolId is not assoicted with any class", null, 404); 
        }
        $this->sendResponse("success", "All Classes successfully fetched", $classes); 
    }

    public function create(Request $request): void
    {
        $data = $request->body;
        $rules = [
            'class_teacher_id' => 'required',
            'school_id' => 'required',
            'class_name' => 'required|min:1|max:50',
        ];

        $teacherSchoolId = User::find($data['class_teacher_id'])->school_id;
        $adminSchoolId = User::find($request->user['user_id'])->school_id;

        if($adminSchoolId != $teacherSchoolId){
            $this->sendResponse("error", "Cannot assign teacher of another school", null, 400); 
        }
        
        if($adminSchoolId != $data['school_id']){
            $this->sendResponse("error", "Cannot create class of another school", null, 400); 
        }

        if(!empty($this->classesModel->getByTeacherId($data['class_teacher_id']))){
            $this->sendResponse("error", "Cannot assign teacher that is already associated with another class", null, 400); 
        }

        if(!$this->classesModel->validate($data, $rules)){
            $this->sendResponse("error", "Invalid Entry", $this->classesModel->getErrors(), 400); 
        }

        $newClass = $this->classesModel->create($data['class_teacher_id'], $data['school_id'], $data['class_name']);
        if($newClass){
            $this->sendResponse("success", "New Class Created with ID $newClass", null); 
        }

        $this->sendResponse("error", "Some error occured", null, 500); 
    }

    public function update(Request $request, $id):void 
    {
        $data = $request->body;

        if(!empty($data['class_teacher_id'])){
            $teacher = User::find($data['class_teacher_id']);
            $admin = User::find($request->user['user_id']);
            if($teacher->school_id != $admin->school_id){
            $this->sendResponse("error", "Cannot Assign Teacher of Another School", null, 403);
            }
        }

        if(!empty($data['school_id'])){
            $this->sendResponse("error", "Cannot Change School ID", null, 400);
        }

        try {
            if(!$this->classesModel->updateById($id, $data)){
                $this->sendResponse("error", "No data provided to be updated", null, 400);
            }
            $this->sendResponse("success", "Class updated successfully", $data);
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

    public function destroy(Request $request, $id): void
    {
        $deletedId = $this->classesModel->deleteById($id);
        if(!$deletedId){
            $this->sendResponse("error", "Class with ID $id not found", [], 404);
        }
        $this->sendResponse("sucess", "Successfully Deleted Class with ID $id", $deletedId);
    }
}