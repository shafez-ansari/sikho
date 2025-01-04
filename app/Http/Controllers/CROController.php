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

    public function ViewCompany()
    {
        return view('cro.add-company');
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
        $req->validate([
            'studentFile' => 'required|file|mimes:csv,txt|max:2048',
        ]);
        if ($req->hasFile('studentFile')) {
            $file = $req->file('studentFile');
            $path = $file->getRealPath();
            $mesg = '';
            $roleId = DB::table('role')->where('role_name', 'Student')->value('role_id');

            if (($handle = fopen($path, 'r')) !== false) {
                // Skip the header row
                $header = fgetcsv($handle, 1000, ',');

                // Prepare a batch insert array
                $usersData = [];
                $studentsData = [];

                while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                    // Fetch related IDs
                    $entityID = DB::table('entity')->whereRaw('LOWER(entity_name) = ?', [strtolower($row[3])])->value('entity_id');
                    $schoolId = DB::table('school')->whereRaw('LOWER(school_name) = ?', [strtolower($row[4])])->value('school_id');
                    $courseId = DB::table('courses')->whereRaw('LOWER(course_name) = ?', [strtolower($row[5])])->value('course_id');
                    $programId = DB::table('program')->whereRaw('LOWER(program_name) = ?', [strtolower($row[6])])->value('program_id');
                    $convertedDate = Carbon::createFromFormat('d-m-Y', $row[10])->format('Y-m-d');
                    // Check if the student already exists
                    $studentExists = DB::table('students')->whereRaw('LOWER(unique_id) = ?', [strtolower($row[7])])->exists();

                    if (!$studentExists) {
                        // Prepare user data
                        $userId = DB::table('users')->insertGetId([
                            'full_name' => $row[0],
                            'email' => $row[1],
                            'phone' => $row[2],
                            'fk_role_id' => $roleId,
                            'created_by' => $email,
                            'updated_by' => $email,
                            'created_date' => now(),
                            'updated_date' => now(),
                            'active' => 1,
                        ]);

                        // Prepare student data
                        $studentsData[] = [
                            'fk_entity_id' => $entityID,
                            'fk_school_id' => $schoolId,
                            'fk_course_id' => $courseId,
                            'fk_program_id' => $programId,
                            'fk_user_id' => $userId,
                            'unique_id' => $row[7],
                            'batch_code' => $row[8],
                            'semester_code' => $row[9],
                            'enrollment_date' => $convertedDate,
                            'created_by' => $email,
                            'updated_by' => $email,
                            'created_date' => now(),
                            'updated_date' => now(),
                            'active' => 1,
                        ];
                    }
                }

                // Batch insert student data
                if (!empty($studentsData)) {
                    DB::table('students')->insert($studentsData);
                }

                fclose($handle);
                $mesg = "File uploaded successfully";
                return response()->json(['message' => $mesg]);
            } else {
                $mesg = "Error in uploading file";
                return response()->json(['message' => 'Error reading the file.']);
            }            
        }
        return response()->json(['message' => 'No file uploaded.'], 400);
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
                'Semester' => $user->semester_code,
                'Enrollment Date' => $user->enrollment_date,
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

    public function AutoCompleteCompany(Request $req)
    {
        $companyName = $req->companyName;
        $companyNameList = DB::select("SELECT cd.comp_name FROM company_details cd
                                        WHERE cd.comp_name LIKE '%?%'", [$companyName]);
        return response()->json(['compList' => $companyNameList]);
    }

    public function CheckCompany(Request $req)
    {
        $compName = $req->compnayName;
        $compId = DB::table('company_details')->where('comp_name', '=', $compName)->value('comp_id');
        if($compId != 0)
        {
            $compLeadList = DB::select("SELECT cd.comp_name, e.entity_name, s.school_name, c.course_name, p.program_name, u.email, ind.sector_name, l.industry_name, 
                                                cld.resource_person, cld.designation, cld.primary_email, cld.primary_phone, cld.leadsource, cld.lead_stage, 
                                                cld.industry_engagement FROM company_lead_details cld
                                        LEFT JOIN company_details cd ON cld.fk_comp_id = cd.comp_id
                                        LEFT JOIN entity e ON cld.fk_entity_id = e.entity_id
                                        LEFT JOIN school s ON cld.fk_school_id = s.school_id
                                        LEFT JOIN courses c ON cld.fk_course_id = c.course_id
                                        LEFT JOIN program p ON cld.fk_program_id = p.program_id
                                        LEFT JOIN users u ON cld.fk_spoc_id = u.user_id
                                        LEFT JOIN industry_sector ind ON cld.fk_industry_sector_id = ind.industry_sector_id
                                        LEFT JOIN industry_location l ON cld.fk_location_id = l.industry_loc_id
                                        WHERE cd.comp_id = ?", [$compId]);

            return response()->json(['compLeadList' => $compLeadList]);
        }
        else 
        {
            return response()->json(['compLeadList' => 'Company does not exist']);
        }
    }

    public function AddCompany(Request $req)
    {
        $companyName = $req->compName;
        $numbers = "";
        $lastCompId = DB::table('company_details')->orderBy('comp_unique_id', 'desc')->value('comp_unique_id');
        if($lastCompId != "")
        {
            if (preg_match_all('/\d+/', $text, $matches)) {
                $numbers = $matches[0]; // Array of all numbers
            }
            $numbers = $numbers + 1;
        }
        else 
        {
            $numbers = "1001";
        }
        
        $uniqueId = "AAFT" + $numbers;
        DB::table('students')->insert([
            'comp_unique_id' => $uniqueId,
            'comp_name' => $companyName,
            'comp_category' => $req->compCategory,
            'comp_website' => $req->compWebsite,
            'comp_month' => $req->compMonth,
            'comp_year' => $req->compYear,
            'created_by' => session('username'),
            'updated_by' => session('username'),
            'created_date' => now(),
            'updated_date' => now(),
            'active' => 1
        ]); 

        return response()->json(['mesg' => 'Data inserted successfully']);
    }

    public function GetCompanyLead()
    {
        $entityList = DB::select("SELECT * FROM entity");
        $programList = DB::select("SELECT * FROM program");
        $industrySectorList = DB::select("SELECT * FROM industry_sector");
        $industryLocationList = DB::select("SELECT * FROM industry_location");
        return response()->json(['entityList' => $entityList, 'programList' => $programList, 'industrySectorList' => $industrySectorList, 'industryLocationList' => $industryLocationList]);
    }
    

    public function AddCompanyLead(Request $req)
    {
        
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
