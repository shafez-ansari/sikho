<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Mail\OtpMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Response;

class CROController extends Controller
{
    public function CRODetails()
    {
        $entityList = DB::select("SELECT * FROM entity");
        // $school = DB::select("SELECT * FROM school");
        // $course = DB::select("SELECT * FROM courses");
        $optin = ['Yes', 'No', 'Did Not Fill Form'];
        $userList = DB::select("SELECT *
            FROM (
                SELECT 
                    u.user_id, 
                    e.entity_name, 
                    s.school_name, 
                    c.course_code, 
                    u.full_name, 
                    u.email, 
                    u.phone, 
                    sch.unique_id, 
                    sch.batch_code, 
                    sch.semester_code, 
                    sch.enrollment_date, 
                    u.`active`,
                    sch.fk_entity_id,
                    sch.fk_school_id,
                    sch.fk_course_id,
                    r.role_name, 
                    CASE 
                        WHEN EXISTS (
                            SELECT 1 FROM offline_questionarie oq WHERE oq.fk_user_id = u.user_id
                        ) OR EXISTS (
                            SELECT 1 FROM online_questionarie_yes oqy WHERE oqy.fk_user_id = u.user_id
                        ) THEN 'YES'
                        WHEN EXISTS (
                            SELECT 1 FROM questionarie_no qn WHERE qn.fk_user_id = u.user_id
                        ) THEN 'NO'
                        ELSE 'DID NOT FILL FORM'
                    END AS OPTIN
                FROM users u
                LEFT JOIN students sch ON u.user_id = sch.fk_user_id
                LEFT JOIN entity e ON e.entity_id = sch.fk_entity_id
                LEFT JOIN school s ON s.school_id = sch.fk_school_id
                LEFT JOIN courses c ON c.course_id = sch.fk_course_id
                LEFT JOIN program p ON p.program_id = sch.fk_program_id
                LEFT JOIN role r ON r.role_id = u.fk_role_id
                WHERE r.role_name = 'Student' AND u.`active` = 1
            ) subquery
        ");

        return view('cro.dashboard', compact(['userList', 'entityList', 'optin']));
    }

    public function BulkUpload()
    {
        return view('cro.bulk');
    }

    public function GetSchool(Request $request)
    {
        $entityId = $request->entity_id;
        $schoolList = DB::select("SELECT * FROM school WHERE fk_entity_id = ?", [$entityId]);
        return response()->json(['schoolList'=>$schoolList]);
    }

    public function GetCourse(Request $request)
    {
        $schoolId = $request->school_id;
        $courseList = DB::select("SELECT * FROM courses WHERE fk_school_id = ?", [$schoolId]);
        return response()->json(['courseList'=>$courseList]);
    }

    public function UploadStudent(Request $req)
    {
        $email = session('username');
        $file = $request->file('studentFile');
        $path = $file->getRealPath();
        $mesg = '';
        $roleId = DB::table('role')->where('role_name', '=', 'Student')->value('role_id');
        if (($handle = fopen($path, 'r')) !== false) {
            // Skip the first row if it contains headers
            $header = fgetcsv($handle, 1000, ',');

            // Insert data into the database
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $entityID = DB::table('entity')->where('entity_name', '=', $row[3])->value('entity_id');
                $schoolId = DB::table('school')->where('school_name', '=', $row[4])->value('school_id');
                $courseId = DB::table('courses')->where('course_name', '=', $row[5])->value('course_id');
                $programId = DB::table('program')->where('program_name', '=', $row[6])->value('program_id');
                
                $userId = DB::table('users')->insertGetId([
                    'full_name' => $row[0], // Map the columns to your table
                    'email' => $row[1],
                    'phone' => $row[2],
                    'fk_role_id' => $roleId,
                    'created_by' => $email,
                    'updated_by' => $email,
                    'created_date' => now(),
                    'updated_date' => now(),
                    'active' => 1
                    // Add more columns as needed
                ]);

                DB::table('students')->insert([
                    'fk_entity_id' => $entityID,
                    'fk_school_id' => $schoolId,
                    'fk_course_id' => $courseId,
                    'fk_program_id' => $programId,
                    'fk_user_id' => $userId,
                    'unique_id' => $row[7],
                    'batch_code' => $row[8],
                    'semester_code' => $row[9],
                    'enrollment_date' => $row[10],
                    'created_by' => $email,
                    'updated_by' => $email,
                    'created_date' => now(),
                    'updated_date' => now(),
                    'active' => 1
                ]);
            }
            fclose($handle);
            $mesg = "File uploaded successfully";
        }
        else
        {
            $mesg = "Error in uploading file";
        }

        return response()->json(['message' => $mesg]);

    }

    public function DownloadStudentTemplate()
    {
        $filename = "StudentTemplate.csv";
        $campaignArray[] = array(
            'Full Name', 
            'Email ID', 
            'Contact No',
            'Entity',
            'School',
            'Course',
            'Program Type', 
            'Unique ID',
            'Batch Code', 
            'Semester', 
            'Enrollment Date');
        $campaignArray[] = array(
            'Full Name' => 'Arun Verma', 
            'Email ID' => 'arun.verma@test.com', 
            'Contact No' => '9876543210',
            'Entity' => 'AAFT Noida',
            'School' => 'School of Animation',
            'Course' => 'B.Sc. in Animation',
            'Program Type' => 'Degree', 
            'Unique ID' => 'AN_BAIS_1001',
            'Batch Code' => '234', 
            'Semester' => 3, 
            'Enrollment Date' => '2024-12-31');

        $csvContent = '';
        foreach ($campaignArray as $row)
        {
            $csvContent .= implode(',', $row) . "\n";
        }

        return Response::make($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
        
    }

    public function ViewStudentDetails(Request $req)
    {
        $entityId = $req->entity_id;
        $schoolId = $req->school_id;
        $courseId = $req->course_id;
        $optin = $req->optin;

        $userList = DB::select("
            SELECT *
            FROM (
                SELECT 
                    u.user_id, 
                    e.entity_name, 
                    s.school_name, 
                    c.course_code, 
                    u.full_name, 
                    u.email, 
                    u.phone, 
                    sch.unique_id, 
                    sch.batch_code, 
                    sch.semester_code, 
                    sch.enrollment_date, 
                    u.`active`,
                    sch.fk_entity_id,
                    sch.fk_school_id,
                    sch.fk_course_id,
                    r.role_name, 
                    CASE 
                        WHEN EXISTS (
                            SELECT 1 FROM offline_questionarie oq WHERE oq.fk_user_id = u.user_id
                        ) OR EXISTS (
                            SELECT 1 FROM online_questionarie_yes oqy WHERE oqy.fk_user_id = u.user_id
                        ) THEN 'YES'
                        WHEN EXISTS (
                            SELECT 1 FROM questionarie_no qn WHERE qn.fk_user_id = u.user_id
                        ) THEN 'NO'
                        ELSE 'DID NOT FILL FORM'
                    END AS OPTIN
                FROM users u
                LEFT JOIN students sch ON u.user_id = sch.fk_user_id
                LEFT JOIN entity e ON e.entity_id = sch.fk_entity_id
                LEFT JOIN school s ON s.school_id = sch.fk_school_id
                LEFT JOIN courses c ON c.course_id = sch.fk_course_id
                LEFT JOIN program p ON p.program_id = sch.fk_program_id
                LEFT JOIN role r ON r.role_id = u.fk_role_id
                WHERE r.role_name = 'Student' AND u.`active` = 1
            ) subquery
            WHERE 
                (? IS NULL OR OPTIN = ?)
                AND (? IS NULL OR fk_entity_id = ?)
                AND (? IS NULL OR fk_school_id = ?)
                AND (? IS NULL OR fk_course_id = ?)
        ", [
            $optin, $optin,
            $entityId, $entityId,
            $schoolId, $schoolId,
            $courseId, $courseId
        ]);

        return response()->json(['userList' => $userList]);
    }

    public function DownloadStudentDetails(Request $req)
    {
        $entityId = $req->entity_id;
        $schoolId = $req->school_id;
        $courseId = $req->course_id;
        $optin = $req->optin;

        $userList = DB::select("
            SELECT *
            FROM (
                SELECT 
                    u.user_id, 
                    e.entity_name, 
                    s.school_name, 
                    c.course_code, 
                    u.full_name, 
                    u.email, 
                    u.phone, 
                    sch.unique_id, 
                    sch.batch_code, 
                    sch.semester_code, 
                    sch.enrollment_date, 
                    u.`active`,
                    sch.fk_entity_id,
                    sch.fk_school_id,
                    sch.fk_course_id,
                    r.role_name, 
                    CASE 
                        WHEN EXISTS (
                            SELECT 1 FROM offline_questionarie oq WHERE oq.fk_user_id = u.user_id
                        ) OR EXISTS (
                            SELECT 1 FROM online_questionarie_yes oqy WHERE oqy.fk_user_id = u.user_id
                        ) THEN 'YES'
                        WHEN EXISTS (
                            SELECT 1 FROM questionarie_no qn WHERE qn.fk_user_id = u.user_id
                        ) THEN 'NO'
                        ELSE 'DID NOT FILL FORM'
                    END AS OPTIN
                FROM users u
                LEFT JOIN students sch ON u.user_id = sch.fk_user_id
                LEFT JOIN entity e ON e.entity_id = sch.fk_entity_id
                LEFT JOIN school s ON s.school_id = sch.fk_school_id
                LEFT JOIN courses c ON c.course_id = sch.fk_course_id
                LEFT JOIN program p ON p.program_id = sch.fk_program_id
                LEFT JOIN role r ON r.role_id = u.fk_role_id
                WHERE r.role_name = 'Student' AND u.`active` = 1
            ) subquery
            WHERE 
                (? IS NULL OR OPTIN = ?)
                AND (? IS NULL OR fk_entity_id = ?)
                AND (? IS NULL OR fk_school_id = ?)
                AND (? IS NULL OR fk_course_id = ?)
        ", [
            $optin, $optin,
            $entityId, $entityId,
            $schoolId, $schoolId,
            $courseId, $courseId
        ]);

        $filename = "StudentDetails.csv";
        $campaignArray[] = array('Entity', 'Unique ID','Name', 'Email ID', 'Contact No', 'School', 'Program Code', 'Batch Code', 'Semester', 'Enrollment Date', 'Optin');
        foreach($userList as $user)
        {
            $campaignArray[] = array(
                'Entity' => $user->entity_name,
                'Unique ID' => $user->unique_id,
                'Name' => $user->full_name,
                'Email ID' => $user->email,
                'Contact No' => $user->phone,
                'School' => $user->school_name,
                'Program Code' => $user->course_code,
                'Batch Code' => $user->batch_code,
                'Semester' => $user->semester,
                'Enrollment Date' => $user->enrollment_datr,
                'Optin' => $user->OPTIN
            );
        }
        $csvContent = '';
        foreach ($campaignArray as $row)
        {
            $csvContent .= implode(',', $row) . "\n";
        }

        return Response::make($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

    public function OnlineQuestionarie()
    {
        $onlineQuestionarieList = DB::select("SELECT s.state_name, c.city_name, a.qualification_name, cs.career_name, oqy.technical_skill, oqy.job_role, oqy.relevant_job, oqy.relevant_job, el.emp_loc_name,
                                                wt.work_type_name, u.full_name, sch.school_name
                                                FROM online_questionarie_yes oqy
                                                LEFT JOIN state s ON s.state_id = oqy.fk_state_id
                                                LEFT JOIN city c ON c.city_id = oqy.fk_city_id
                                                LEFT JOIN academic_qualification a ON a.qualification_id = oqy.fk_qualification_id
                                                LEFT JOIN employment_status emp ON emp.emp_status_id = oqy.fk_employment_status_id
                                                LEFT JOIN career_support cs ON cs.career_id = oqy.fk_career_id 
                                                LEFT JOIN employment_location el ON el.emp_loc_id = oqy.fk_emp_loc_id
                                                LEFT JOIN work_type wt ON wt.work_type_id = oqy.fk_work_type_id
                                                LEFT JOIN users u ON u.user_id = oqy.fk_user_id
                                                LEFT JOIN school sch ON u.fk_school_id = sch.school_id");

        return view('cro.dashboard', compact(['onlineQuestionarieList']));
    }

    public function OfflineQuestionarire()
    {
        $offlineQuestionarireList = DB::select("SELECT s.state_name, c.city_name, a.qualification_name, cs.career_name, oqy.technical_skill, oqy.job_role, oqy.relevant_job, oqy.relevant_job, el.emp_loc_name,
                                                wt.work_type_name, u.full_name, sch.school_name
                                                FROM online_questionarie_yes oqy
                                                LEFT JOIN state s ON s.state_id = oqy.fk_state_id
                                                LEFT JOIN city c ON c.city_id = oqy.fk_city_id
                                                LEFT JOIN academic_qualification a ON a.qualification_id = oqy.fk_qualification_id
                                                LEFT JOIN employment_status emp ON emp.emp_status_id = oqy.fk_employment_status_id
                                                LEFT JOIN career_support cs ON cs.career_id = oqy.fk_career_id 
                                                LEFT JOIN employment_location el ON el.emp_loc_id = oqy.fk_emp_loc_id
                                                LEFT JOIN work_type wt ON wt.work_type_id = oqy.fk_work_type_id
                                                LEFT JOIN users u ON u.user_id = oqy.fk_user_id
                                                LEFT JOIN school sch ON u.fk_school_id = sch.school_id");

        return view('cro.dashboard', compact(['offlineQuestionarireList']));
    }
}
