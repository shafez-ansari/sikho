<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Mail\OtpMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

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
                session()->put('email', $user->email);
                
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
                return redirect()->action([HomeController::class, 'CRODetails']);
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
        $userList = DB::select("SELECT u.user_id, u.fk_role_id, u.full_name, u.email, u.phone, u.unique_id, e.entity_name, e.entity_code, s.school_name, s.school_code, c.course_name, c.course_code, u.batch_code, u.semester, u.enrollment_datr 
                                FROM users u
                                LEFT JOIN entity e ON e.entity_id = u.fk_entity_id
                                LEFT JOIN school s ON s.school_id = u.fk_school_id
                                LEFT JOIN courses c ON c.course_id = u.fk_course_id
                                LEFT JOIN program p ON p.program_id = u.fk_program_id
                                WHERE u.email = ?", [session('email')]);

        return view('home.student-details', compact(['userList']));
    }

    public function SubmitPlacement(Request $req)
    {
        $entityName = $req->entity;
        $school = $req->school;
        $course = $req->course;
        $yes = $req->yes;
        $no = $req->no;
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

        if($yes == true )
        {
            $academicQual = DB::select("SELECT qualification_id, qualification_name FROM academic_qualification");
            if($entityName == "AAFT Noida" || $entityName == "AAFT University")
            {                  
                if($school == "School of Animation")
                {
                    $jobProfile = DB::select("SELECT soa_profile_id, soa_profile_name FROM job_profile_soa");
                }
                else if($school == "School of Cinema")
                {
                    $jobProfile = DB::select("SELECT soc_profile_id, soc_profile_name FROM job_profile_soc");
                }
                else if($school == "School of Advertising, PR and Events")
                {
                    $jobProfile = DB::select("SELECT soapre_profile_id, soapre_profile_name FROM job_profile_soapre");
                }
                else if($school == "School of Data Science")
                {
                    $jobProfile = DB::select("SELECT sods_profile_id, sods_profile_name FROM job_profile_sods");
                }
                else if($school == "School of Digital Marketing")
                {
                    $jobProfile = DB::select("SELECT sodm_profile_id, sodm_profile_name FROM job_profile_sodm");
                }
                else if($school == "School of Fashion Design")
                {
                    $jobProfile = DB::select("SELECT sofd_profile_id, sofd_profile_name FROM job_profile_sofd");
                }
                else if($school == "School Of Health and Wellness")
                {
                    $jobProfile = DB::select("SELECT sow_profile_id, sow_profile_name FROM job_profile_sow");
                }
                else if($school == "School of Hospitality and Tourism")
                {
                    $jobProfile = DB::select("SELECT soht_profile_id, soht_profile_name FROM job_profile_soht");
                }
                else if($school == "School of Interior Design")
                {
                    $jobProfile = DB::select("SELECT soid_profile_id, soid_profile_name FROM job_profile_soid");
                }
                else if($school == "School of Journalism and Mass Communication")
                {
                    $jobProfile = DB::select("SELECT sojmc_profile_id, sojmc_profile_name FROM job_profile_sojmc");
                }
                else if($school == "School of Music")
                {
                    $jobProfile = DB::select("SELECT som_profile_id, som_profile_name FROM job_profile_som");
                }
                else if($school == "School of Still Photography")
                {
                    $jobProfile = DB::select("SELECT sosp_profile_id, sosp_profile_name FROM job_profile_sosp");
                }
                else if($school == "School of Still Photography")
                {
                    $jobProfile = DB::select("SELECT sosp_profile_id, sosp_profile_name FROM job_profile_sosp");
                }
                else if($school == "School of Fine Arts")
                {
                    $jobProfile = DB::select("SELECT sofa_profile_id, sofa_profile_name FROM job_profile_sofa");
                }

                $jobType = DB::select("SELECT job_type_id, job_type_name FROM job_type");
                $empLocation = DB::select("SELECT emp_loc_id, emp_loc_name FROM employment_location");
            }
            else if($entityName == "AAFT Online")
            {
                $state = DB::select("SELECT state_id, state_name FROM state");
                $city = DB::select("SELECT city_id, city_name FROM city");
                $empStatus = DB::select("SELECT emp_status_id, emp_status_name FROM employment_status");
                $careerSupport = DB::select("SELECT career_id, career_name FROM career_support");
                if($course == "Diploma in Fashion Design")
                {
                    $jobProfile = DB::select("SELECT job_fashion_id, job_fashion_name FROM job_fashion");
                }
                else if($course == "Diploma in Jewellery Design")
                {
                    $jobProfile = DB::select("SELECT job_jewellery_id, job_jewellery_name FROM job_jewellery");
                }
                else if($course == "Diploma in Interior Design")
                {
                    $jobProfile = DB::select("SELECT job_interior_id, job_interior_name FROM job_interior");
                }
                else if($course == "Diploma in Event Management")
                {
                    $jobProfile = DB::select("SELECT job_event_id, job_event_name FROM job_event");
                }
                else if($course == "Diploma in Music Production")
                {
                    $jobProfile = DB::select("SELECT job_music_id, job_music_name FROM job_music");
                }
                else if($course == "Diploma in 3D Animation & Visual Effects")
                {
                    $jobProfile = DB::select("SELECT job_animation_id, job_animation_name FROM job_animation");
                }
                else if($course == "Diploma in Nutrition & Dietetics")
                {
                    $jobProfile = DB::select("SELECT job_nutrition_id, job_nutrition_name FROM job_nutrition");
                }
                else if($course == "Diploma in Hospital Management")
                {
                    $jobProfile = DB::select("SELECT job_hospital_id, job_hospital_name FROM job_hospital");
                }
                else if($course == "Diploma in Travel & Tourism")
                {
                    $jobProfile = DB::select("SELECT job_travel_id, job_travel_name FROM job_travel");
                }
                else if($course == "Certificate in Advertising PR & Corporate Communication")
                {
                    $jobProfile = DB::select("SELECT job_advertising_id, job_advertising_name FROM job_advertising");
                }
                else if($course == "The Makeup Artist Certification Course")
                {
                    $jobProfile = DB::select("SELECT job_makeup_id, job_makeup_name FROM job_makeup");
                }
                else if($course == "The Ultimate Journalism Certificate Program")
                {
                    $jobProfile = DB::select("SELECT job_journalism_id, job_journalism_name FROM job_journalism");
                }
                $jobRoles = DB::select("SELECT job_role_id, job_role_name FROM job_role");
                $jobRelocate = DB::select("SELECT job_relocate_id, job_relocate_name FROM job_relocate");
                $workType = DB::select("SELECT work_type_id, work_type_name FROM work_type");
            }   
        }
        else if($no == true)
        {
            $reasonNotPlacing = DB::select("SELECT questionarie_no_id, fk_placement_no_id FROM questionarie_no");
        }
        return view('home.submit-placement', compact(['entityName', 'school', 'course', 'jobProfile', 'academicQual', 'jobType', 'empLocation', 'state', 'city',
        'empStatus', 'careerSupport', 'reasonNotPlacing', 'jobRoles', 'jobRelocate', 'workType', 'reasonNotPlacing', 'yes', 'no']));
    }
}
