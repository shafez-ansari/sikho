<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Mail\OtpMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Http\Controllers\CROController;

class HomeController extends Controller
{
    public function Login(Request $req)
    {
        $email = $req->email;
        $userList = DB::select("SELECT * FROM users  WHERE email = ? OR unique_id = ?", [$email, $email]);
        
        if(count($userList) == 0)
        {
            return response()->json(['loginMsg' => "Invalid email or unique Id"]);
        }
        else 
        {
            $userId = 0;
            foreach($userList as $user)
            {
                $userId = $user->user_id;
            }

            $randomNumber = random_int(1000, 9999);
            DB::table('email_otp')->insert([
                'otp' => $randomNumber,
                'fk_user_id' => $userId,
                'created_by' => $email,
                'updated_by' => $email,
                'created_date' => now(),
                'updated_date' => now(),
                'active' => 1
            ]);

            Mail::send('otp-mail', ['otp' => $randomNumber], function($message) use ($req) {
                $message->to($req->email);
                $message->subject('Your OTP Code');
            });

            return response()->json(['loginMsg' => 'OTP generated']);
        }
    }

    public function SubmitOtp(Request $req)
    {
        $email = $req->email;
        $otp = $req->otp;
        $userList = DB::select("SELECT u.user_id, u.fk_role_id, u.full_name, u.email, u.phone, u.unique_id, e.entity_name, e.entity_code, s.school_name, s.school_code, c.course_name, c.course_code, u.batch_code, u.semester, u.enrollment_datr 
                                FROM users u
                                LEFT JOIN entity e ON e.entity_id = u.fk_entity_id
                                LEFT JOIN school s ON s.school_id = u.fk_school_id
                                LEFT JOIN courses c ON c.course_id = u.fk_course_id
                                LEFT JOIN program p ON p.program_id = u.fk_program_id
                                WHERE u.email = ? OR u.unique_id = ?", [$email, $email]);

        $userId = 0;
        foreach($userList as $user)
        {
            $userId = $user->user_id;
        }

        $otpList = DB::select("SELECT * FROM email_otp WHERE fk_user_id = ? AND otp = ? AND created_date >= NOW() - INTERVAL 10 MINUTE", [$userId, $otp]);

        if(count($otpList) == 0)
        {            
            return response()->json(['loginMsg' => 'Invalid OTP']);
        }
        else 
        {
            $roleId = 0;
            foreach($userList as $user)
            {
                $roleId = $user->fk_role_id;
                session()->put('username', $user->email);
                
            }

            $roleName = DB::table('role')->where('role_id', '=', $roleId)->value('role_name');
            
            if($roleName == 'Student')
            {
                return response()->json(["loginMsg" => url("student-details")]);
                //return redirect()->action([HomeController::class, 'StudentDetails']);
                // $userList = DB::select("SELECT u.user_id, u.fk_role_id, u.full_name, u.email, u.phone, u.unique_id, e.entity_name, e.entity_code, s.school_name, s.school_code, c.course_name, c.course_code, u.batch_code, u.semester, u.enrollment_datr 
                //                 FROM users u
                //                 LEFT JOIN entity e ON e.entity_id = u.fk_entity_id
                //                 LEFT JOIN school s ON s.school_id = u.fk_school_id
                //                 LEFT JOIN courses c ON c.course_id = u.fk_course_id
                //                 LEFT JOIN program p ON p.program_id = u.fk_program_id
                //                 WHERE u.email = ?", [session('email')]);

                // return view('home.student-details', compact(['userList']));
            }
            else if($roleName == "CRO")
            {
                return response()->json(["loginMsg" => url("cro-details")]);
                //return redirect()->action([HomeController::class, 'CRODetails']);
            }
            else if($roleName == "Admin" )
            {
                return redirect()->action([HomeController::class, 'AdminDetails']);
            }
            else
            {
                return redirect()->action([HomeController::class, 'ITDetails']);
            }
        }
    }

    public function ResendOtp(Request $req)
    {
        $email = $req->email;
        $userList = DB::select("SELECT * FROM users  WHERE email = ? OR unique_id = ?", [$email, $email]);
        $userId = 0;
        foreach($userList as $user)
        {
            $userId = $user->user_id;
        }

        $randomNumber = random_int(1000, 9999);

        DB::table('email_otp')->where('fk_user_id', $userId)->update([
            'active' => 0
        ]);
        
        DB::table('email_otp')->insert([
            'otp' => $randomNumber,
            'fk_user_id' => $userId,
            'created_by' => $email,
            'updated_by' => $email,
            'created_date' => now(),
            'updated_date' => now(),
            'active' => 1
        ]);

        Mail::send('otp-mail', ['otp' => $randomNumber], function($message) use ($req) {
            $message->to($req->email);
            $message->subject('Your OTP Code');
        });

        return response()->json(['loginMsg' => 'OTP resend generated']);
    }

    public function StudentDetails()
    {
        $userList = DB::select("SELECT u.user_id, u.fk_role_id, u.full_name, u.email, u.phone, u.unique_id, e.entity_name, e.entity_code, s.school_name, s.school_code, c.course_name, c.course_code, u.batch_code, u.semester, u.enrollment_datr, ui.img_name, ui.img_path 
                                FROM users u
                                LEFT JOIN entity e ON e.entity_id = u.fk_entity_id
                                LEFT JOIN school s ON s.school_id = u.fk_school_id
                                LEFT JOIN courses c ON c.course_id = u.fk_course_id
                                LEFT JOIN program p ON p.program_id = u.fk_program_id
                                LEFT JOIN user_image ui on ui.fk_user_id = u.user_id
                                WHERE u.email = ?", [session('username')]);

        return view('home.student-details', compact(['userList']));
    }

    public function SubmitPlacement($entityName, $school, $yes, $no, $course)
    {
        // $entityName = $req->entity;
        // $school = $req->school;
        // $course = $req->course;
        // $yes = $req->yes;
        // $no = $req->no;
        $jobProfile = "";
        $academicQual = "";
        $jobType = "";
        $empLocation = "";
        $state = "";
        $city = "";
        $empStatus = "";
        $careerSupport = "";
        $reasonNotPlacing = "";
        $jobRoles = "";
        $jobRelocate = "";
        $workType = "";
        $reasonNotPlacing = "";

        $userId = DB::table('users')->where('email', '=', session('username'))->value('user_id');

        if($userId != 0)
        {
            if($yes == 1 )
            {
                $academicQual = DB::select("SELECT qualification_id, qualification_name FROM academic_qualification WHERE active = 1");
                if($entityName == "AAFT Noida" || $entityName == "AAFT University")
                {                  
                    if($school == "School of Animation")
                    {
                        $jobProfile = DB::select("SELECT soa_profile_id, profile_name FROM job_profile_soa WHERE active = 1");
                    }
                    else if($school == "School of Cinema")
                    {
                        $jobProfile = DB::select("SELECT soc_profile_id, profile_name FROM job_profile_soc WHERE active = 1");
                    }
                    else if($school == "School of Advertising, PR and Events")
                    {
                        $jobProfile = DB::select("SELECT soapre_profile_id, profile_name FROM job_profile_soapre WHERE active = 1");
                    }
                    else if($school == "School of Data Science")
                    {
                        $jobProfile = DB::select("SELECT sods_profile_id, profile_name FROM job_profile_sods WHERE active = 1");
                    }
                    else if($school == "School of Digital Marketing")
                    {
                        $jobProfile = DB::select("SELECT sodm_profile_id, profile_name FROM job_profile_sodm WHERE active = 1");
                    }
                    else if($school == "School of Fashion Design")
                    {
                        $jobProfile = DB::select("SELECT sofd_profile_id, profile_name FROM job_profile_sofd WHERE active = 1");
                    }
                    else if($school == "School Of Health and Wellness")
                    {
                        $jobProfile = DB::select("SELECT sow_profile_id, profile_name FROM job_profile_sow WHERE active = 1");
                    }
                    else if($school == "School of Hospitality and Tourism")
                    {
                        $jobProfile = DB::select("SELECT soht_profile_id, profile_name FROM job_profile_soht WHERE active = 1");
                    }
                    else if($school == "School of Interior Design")
                    {
                        $jobProfile = DB::select("SELECT soid_profile_id, profile_name FROM job_profile_soid WHERE active = 1");
                    }
                    else if($school == "School of Journalism and Mass Communication")
                    {
                        $jobProfile = DB::select("SELECT sojmc_profile_id, profile_name FROM job_profile_sojmc WHERE active = 1");
                    }
                    else if($school == "School of Music")
                    {
                        $jobProfile = DB::select("SELECT som_profile_id, profile_name FROM job_profile_som WHERE active = 1");
                    }
                    else if($school == "School of Still Photography")
                    {
                        $jobProfile = DB::select("SELECT sosp_profile_id, profile_name FROM job_profile_sosp WHERE active = 1");
                    }
                    else if($school == "School of Still Photography")
                    {
                        $jobProfile = DB::select("SELECT sosp_profile_id, profile_name FROM job_profile_sosp WHERE active = 1");
                    }
                    else if($school == "School of Fine Arts")
                    {
                        $jobProfile = DB::select("SELECT sofa_profile_id, profile_name FROM job_profile_sofa WHERE active = 1");
                    }

                    $jobType = DB::select("SELECT job_type_id, job_type_name FROM job_type WHERE active = 1");
                    $empLocation = DB::select("SELECT emp_loc_id, emp_loc_name FROM employment_location WHERE active = 1");
                }
                else if($entityName == "AAFT Online")
                {
                    $state = DB::select("SELECT state_id, state_name FROM state");
                    $city = DB::select("SELECT city_id, city_name FROM city");
                    $empStatus = DB::select("SELECT emp_status_id, emp_status_name FROM employment_status");
                    $careerSupport = DB::select("SELECT career_id, career_name FROM career_support");
                    if($course == "Diploma in Fashion Design")
                    {
                        $jobProfile = DB::select("SELECT job_fashion_id, jobName FROM job_fashion");
                    }
                    else if($course == "Diploma in Jewellery Design")
                    {
                        $jobProfile = DB::select("SELECT job_jewellery_id, jobName FROM job_jewellery");
                    }
                    else if($course == "Diploma in Interior Design")
                    {
                        $jobProfile = DB::select("SELECT job_interior_id, jobName FROM job_interior");
                    }
                    else if($course == "Diploma in Event Management")
                    {
                        $jobProfile = DB::select("SELECT job_event_id, jobName FROM job_event");
                    }
                    else if($course == "Diploma in Music Production")
                    {
                        $jobProfile = DB::select("SELECT job_music_id, jobName FROM job_music");
                    }
                    else if($course == "Diploma in 3D Animation & Visual Effects")
                    {
                        $jobProfile = DB::select("SELECT job_animation_id, jobName FROM job_animation");
                    }
                    else if($course == "Diploma in Nutrition & Dietetics")
                    {
                        $jobProfile = DB::select("SELECT job_nutrition_id, jobName FROM job_nutrition");
                    }
                    else if($course == "Diploma in Hospital Management")
                    {
                        $jobProfile = DB::select("SELECT job_hospital_id, jobName FROM job_hospital");
                    }
                    else if($course == "Diploma in Travel & Tourism")
                    {
                        $jobProfile = DB::select("SELECT job_travel_id, jobName FROM job_travel");
                    }
                    else if($course == "Certificate in Advertising PR & Corporate Communication")
                    {
                        $jobProfile = DB::select("SELECT job_advertising_id, jobName FROM job_advertising");
                    }
                    else if($course == "The Makeup Artist Certification Course")
                    {
                        $jobProfile = DB::select("SELECT job_makeup_id, jobName FROM job_makeup");
                    }
                    else if($course == "The Ultimate Journalism Certificate Program")
                    {
                        $jobProfile = DB::select("SELECT job_journalism_id, jobName FROM job_journalism");
                    }
                    $jobRoles = DB::select("SELECT job_role_id, job_role_name FROM job_role");
                    $jobRelocate = DB::select("SELECT job_relocate_id, job_relocate_name FROM job_relocate");
                    $workType = DB::select("SELECT work_type_id, work_type_name FROM work_type");
                }   
            }
            else if($no == 1)
            {
                $reasonNotPlacing = DB::select("SELECT not_placement_id, not_placement_name FROM not_placement WHERE active = 1");
            }
        }
        else 
        {
            return redirect()->action([HomeController::class, 'ThankYou']);
        }

        return view('home.submit-placement', compact(['entityName', 'school', 'course', 'jobProfile', 'academicQual', 'jobType', 'empLocation', 'state', 'city',
        'empStatus', 'careerSupport', 'jobRoles', 'jobRelocate', 'workType', 'reasonNotPlacing', 'yes', 'no']));
    }

    public function SubmitQuestionarie(Request $req)
    {
        $userId = DB::table('users')->where('email', '=', session('username'))->value('user_id');
        
        if($req->yes == 1)
        {
            $academicQual = $req->academicQual;
            $entity = $req->entity;
            $course = $req->course;
            $school = $req->school;                
            $entityId = DB::table('entity')->where('entity_name', '=', $entity)->value('entity_id');
            $schoolId = DB::table('school')->where('school_name', '=', $entity)->value('school_code');
    
            if($req->entity == "AAFT Noida" || $req->entity == "AAFT University")
            {
                $jobProfile = $req->jobProfile;
                $jobType = $req->jobType;
                $jobLocation = $req->jobLocation;
                $workExp = $req->workExp;
                //$empLocId = $req->
                $entity = $req->entity;
                $course = $req->course;
                $school = $req->school;                
                $entityId = DB::table('entity')->where('entity_name', '=', $entity)->value('entity_id');
                $schoolId = DB::table('school')->where('school_name', '=', $entity)->value('school_code');
                
                DB::table('offline_questionarie')->insert([
                    'fk_qualifcation_id' => $academicQual,
                    'fk_user_id' => $userId,
                    'fk_job_type_id' => $jobType,
                    'job_profile' => $jobProfile,
                    'fk_entity_id' => $entityId,
                    'fk_school_id' => $schoolId,
                    'fk_emp_loc_id' => $jobLocation,
                    'work_exp' => $workExp,
                    'created_by' => session('username'),
                    'updated_by' => session('username'),
                    'created_date' => now(),
                    'updated_date' => now(),
                    'active' => 1   
                ]);
            }
            else if($entityName == "AAFT Online")
            {
                $state = $req->state;
                $city = $req->city;
                $empStatus = $req->empStatus;
                $careerSupport = $req->careerSupport;
                $technicalSkill = $req->technicalSkill;
                $jobRole = $req->jobRole;
                $preferedJob = $req->preferedJob;
                $relocate = $req->relocate;
                $jobPlace = $req->jobPlace;
                $workType = $req->workType;
                DB::table('online_questionarie_yes')->insert([
                    'fk_state_id' => $state,
                    'fk_city_id' => $city,
                    'fk_qualification_id' => $academicQual,
                    'fk_employment_status_id' => $empStatus,
                    'fk_career_id' => $careerSupport,
                    'technical_skill' => $technicalSkill,
                    'job_role' => $jobRole,
                    'relevant_job' => $preferedJob,
                    'relocate' => $relocate,
                    'fk_emp_loc_id' => $jobPlace,
                    'fk_work_type_id' => $workType,
                    'fk_user_id' => $userId,
                    'created_by' => session('username'),
                    'updated_by' => session('username'),
                    'created_date' => now(),
                    'updated_date' => now(),
                    'active' => 1   
                ]);
            }
        }
        else 
        {
            $interval = $req->interval;
            $notPlacement = $req->notPlacement;
            DB::table('questionarie_no')->insert([
                'fk_placement_no_id' => $notPlacement,
                'fk_user_id' => $userId,
                'interval' => $interval,
                'created_by' => session('username'),
                'updated_by' => session('username'),
                'created_date' => now(),
                'updated_date' => now(),
                'active' => 1   
            ]);
        }

        return response()->json(["thankyou" => url("thankYou")]);
    }

    public function ThankYou()
    {
        $fullName = DB::table('users')->where('email', '=', session('username'))->value('full_name');
        $uniqueId = DB::table('users')->where('email', '=', session('username'))->value('unique_id');
        return view('home.thank-you', compact(['fullName', 'uniqueId']));
    }
    
}
