@extends('home.home-master')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.1.0/dist/css/multi-select-tag.css">
<style>
    .mult-select-tag ul li {
        text-align: left;
    }
</style>


    <form action="" id="entityFormId" method="post" width="100%">
        @csrf
        @if($yes == true && ($entityName == "AAFT Noida" || $entityName == "AAFT University"))
        <div class="row form-group">
            <div class="col-md-6">
                <label for="">Your last completed academic qualification.</label>
            </div> 
            <div class="col-md-6">
                <select name="qualId" id="qualId" class="form-control">
                        <option value="">--Select--</option>
                    @foreach($academicQual as $qual)
                        <option value="{{$qual->qualification_id}}">{{$qual->qualification_name}}</option>
                    @endforeach
                </select>
                <span class="text-danger" id="qualError"></span>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-6">
                <label for="jobProfile">I want to work for the job profile.</label>
            </div>
            <div class="col-md-6">
                <select name="jobProfileId" id="jobProfileId" class="form-control" multiple>
                @foreach($jobProfile as $job)
                    <option value="{{$job->profile_name}}">{{$job->profile_name}}</option>
                @endforeach
                </select>
                <span class="text-danger" id="jobProfileError"></span>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-6">
                <label for="jobType">What job type are you interested in?</label>
            </div>
            <div class="col-md-6">
                <select name="jobTypeId" id="jobTypeId" class="form-control">
                    <option value="">--Select--</option>
                @foreach($jobType as $job)
                    <option value="{{$job->job_type_id}}">{{$job->job_type_name}}</option>
                @endforeach
                </select>
                <span class="text-danger" id="jobTypeError"></span>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-6">
                <label for="jobLocation">Preferred Location of Job/Employment?</label>
            </div>
            <div class="col-md-6">
                <select name="jobLocationId" id="jobLocationId" class="form-control" multiple>
                @foreach($empLocation as $loc)
                    <option value="{{$loc->emp_loc_id}}">{{$loc->emp_loc_name}}</option>
                @endforeach
                </select>
                <span class="text-danger" id="jobLocationError"></span>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-6">
                <label for="jobLocation">Relevant Work Experience</label>
            </div>
            <div class="col-md-6">
                <input type="number" name="workExp" id="workExp" class="form-control">
                <span class="text-danger" id="workExpError"></span>
            </div>
        </div>
        @endif
        @if($yes == true && ($entityName == "AAFT Online"))
        <div class="row form-group">
            <div class="col-md-6">
                <label for="state">State</label>
            </div>
            <div class="col-md-6">
                <select name="stateId" id="stateId" class="form-control">
                    <option value="">--Select--</option>
                @foreach($state as $state)
                    <option value="{{$state->state_id}}">{{$state->state_name}}</option>
                @endforeach
                </select>
                <span class="text-danger" id="stateIdError"></span>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-6">
                <label for="jobType">City</label>
            </div>
            <div class="col-md-6">
                <select name="jobTypeId" id="jobTypeId" class="form-control">
                    <option value="">--Select--</option>
                </select>
                <span class="text-danger" id="jobTypeError"></span>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-6">
                <label for="qual">Your last completed academic qualification.</label>
            </div> 
            <div class="col-md-6">
                <select name="qualId" id="qualId" class="form-control">
                        <option value="">--Select--</option>
                    @foreach($academicQual as $qual)
                        <option value="{{$qual->qualification_id}}">{{$qual->qualification_name}}</option>
                    @endforeach
                </select>
                <span class="text-danger" id="qualError"></span>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-6">
                <label for="empStatus">Current Employment Status</label>
            </div> 
            <div class="col-md-6">
                <select name="empStatusId" id="empStatusId" class="form-control">
                        <option value="">--Select--</option>
                    @foreach($empStatus as $status)
                        <option value="{{$status->emp_status_id}}">{{$status->emp_status_name}}</option>
                    @endforeach
                </select>
                <span class="text-danger" id="empStatusError"></span>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-6">
                <label for="careerSupport">What motivates your need for career support?</label>
            </div> 
            <div class="col-md-6">
                <select name="careerSupportId" id="careerSupportId" class="form-control">
                        <option value="">--Select--</option>
                    @foreach($careerSupport as $support)
                        <option value="{{$support->career_id}}">{{$support->career_name}}</option>
                    @endforeach
                </select>
                <span class="text-danger" id="careerSupportError"></span>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-6">
                <label for="technicalSkill">Technical Skills (Software Specific Keywords as per your domain)</label>
            </div> 
            <div class="col-md-6">
                <input type="text" name="technicalSkill" id="technicalSkill" class="form-control">
                <span class="text-danger" id="technicalSkillError"></span>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-6">
                <label for="jobRole">Preferred Job Roles</label>
            </div>
            <div class="col-md-6">
                <select name="jobRoleId" id="jobRoleId" class="form-control" multiple>
                @foreach($jobRoles as $role)
                    <option value="{{$role->jobName}}">{{$role->jobName}}</option>
                @endforeach
                </select>
                <span class="text-danger" id="jobRoleError"></span>
            </div>
        </div>  
        <div class="row form-group">
            <div class="col-md-6">
                <label for="intershipExp">Are any internship or volunteer experiences relevant to your preferred job roles?</label>
            </div>
            <div class="col-md-6">
                <div class="col-md-6">
                    <input type="radio" id="yesIntershipExp" name="intershipExp" value="HTML">
                    <label for="Yes">Yes</label>
                </div>
                <div class="col-md-6">
                    <input type="radio" id="noIntershipExp" name="intershipExp" value="HTML">
                    <label for="No">No</label>
                </div>
                <span class="text-danger" id="intershipExpError"></span>
            </div>
        </div>  
        <div class="row form-group">
            <div class="col-md-6">
                <label for="relocate">Willingness to Relocate?</label>
            </div>
            <div class="col-md-6">
                <div class="col-md-6">
                    <input type="radio" id="yesRelocate" name="relocate" value="HTML">
                    <label for="Yes">Yes</label>
                </div>
                <div class="col-md-6">
                    <input type="radio" id="noRelocate" name="relocate" value="HTML">
                    <label for="No">No</label>
                </div>
                <span class="text-danger" id="relocateError"></span>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-6">
                <label for="stateRelocate">Preferred Job Location</label>
            </div>
            <div class="col-md-6">
                <select name="stateRelocateId" id="stateRelocateId" class="form-control">
                    <option value="">--Select--</option>
                @foreach($state as $state)
                    <option value="{{$state->state_id}}">{{$state->state_name}}</option>
                @endforeach
                </select>
                <span class="text-danger" id="stateRelocateIdError"></span>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-6">
                <label for="workType">Preferred Work Type</label>
            </div>
            <div class="col-md-6">
                <select name="workTypeId" id="workTypeId" class="form-control">
                    <option value="">--Select--</option>
                @foreach($workType as $work)
                    <option value="{{$work->work_type_id}}">{{$work->work_type_name}}</option>
                @endforeach
                </select>
                <span class="text-danger" id="workTypeError"></span>
            </div>
        </div>   
        @endif
        <div class="row form-group">
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary" onclick="submitQuestionarie()">Submit</button>
            </div>
        </div>
    </form>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.1.0/dist/js/multi-select-tag.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        new MultiSelectTag('jobProfileId')
        new MultiSelectTag('jobLocationId')
    });

</script>
@endsection
