@extends('home.home-master')

@section('content')

<form action="" id="entityFormId" width="100%">
        @csrf
        <input type="hidden" value="{{ $entityName }}" id="hdnEntityName">
        <input type="hidden" value="{{ $yes }}" id="hdnYes">
        <input type="hidden" value="{{ $no }}" id="hdnNo">
        <input type="hidden" value="{{ $school }}" id="hdnSchool">
        <input type="hidden" value="{{ $course }}" id="hdnCourse">

        @if($yes == 1)
            @if($entityName == "AAFT Noida" || $entityName == "AAFT University")
        <div class="row form-group">
            <div class="col-md-6">
                <label for="">Your last completed academic qualification.</label>
            </div> 
            <break>
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
            <break>
            <div class="col-md-6">
                <select name="jobProfileId" id="jobProfileId" class="form-control" multiple="multiple">
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
                <select name="jobTypeId" id="jobTypeId" class="form-control" multiple="multiple">
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
                <select name="jobLocationId" id="jobLocationId" class="form-control" multiple="multiple">
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
        @elseif($entityName == "AAFT Online")
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
                <label for="city">City</label>
            </div>
            <div class="col-md-6">
                <select name="cityId" id="cityId" class="form-control">
                    <option value="">--Select--</option>
                </select>
                <span class="text-danger" id="cityError"></span>
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
        @elseif($yes != 1)
            <div class="row form-group">
                <div class="col-md-6">
                    <label for="notPlacementId">Reason for not looking for placement.</label>
                </div>
                <div class="col-md-6">
                    <select name="notPlacementId" id="notPlacementId" class="form-control" onchange = "notPlacement()">
                            <option value="">--Select--</option>
                        @foreach($reasonNotPlacing as $reason)
                            <option value="{{$reason->not_placement_id}}">{{$reason->not_placement_name}}</option>    
                        @endforeach
                    </select>
                    <span class="text-danger" id="notPlacementError"></span>
                </div>
            </div>
            <div class="row form-group" id="intervalDiv" style="display:none">
                <div class="col-md-6">
                    <label for="intervalId">Duration <b class="text-danger">*</b></label>
                </div>
                <div class="col-md-6">
                    <input type="text" id="intervalId" name="intervalId" class="form-control" />  
                    <span id="intervalError" class="text-danger"></span>
                </div>
            </div>
        @endif
        <div class="row form-group">
            <div class="col-md-6"> 
                <div id="submitQuestionId"  class="btn btn-primary" onclick="submitQuestionarie()">Submit</div> 
            </div>
        </div>
    </form>

<script type="text/javascript">
    $(document).ready(function() {
        $('#jobProfileId').multiselect({
            numberDisplayed: 1,
            enableFiltering: true

        });
        $('#jobTypeId').multiselect({
            numberDisplayed: 1,
            enableFiltering: true
        });
        $('#jobLocationId').multiselect({
            numberDisplayed: 1,
            enableFiltering: true
        });
        $("#jobRoleId").multiselect({
            numberDisplayed: 1,
            enableFiltering: true
        });
    });

    function submitQuestionarie() {
        debugger;
        academicQual = $("#qualId").val();
        jobProfile = $("#jobProfileId").val();
        jobType = $("#jobTypeId").val();
        jobLocation = $("#jobLocationId").val();
        workExp = $("#workExp").val();
        state = $("#stateId").val();
        city = $("#city").val();
        empStatus = $("#empStatusId").val();
        careerSupport = $("#careerSupportId").val();
        technicalSkill = $("#technicalSkill").val();
        jobRole = $("#jobRoleId").val();
        preferedJob = $("input[name='intershipExp']").val();
        relocate = $("input[name='relocate']").val();
        jobPlace = $("#stateRelocateId").val();
        workType = $("#workTypeId").val();

        entityName = $("#hdnEntityName").val();
        yes = $("#hdnYes").val();
        no = $("#hdnNo").val();
        school = $("#hdnSchool").val();
        course = $("#hdnCourse").val();

        if(entityName == "AAFT Noida" || entityName == "AAFT University"){
            if(academicQual == "") {
                $("#qualError").empty().text('Please select academic qualification');
            }
            else {
                $("#qualError").empty().text('');
            }

            if(jobProfile == null) {
                $("#jobProfileError").empty().text('Please select job profile');
            }
            else if(jobProfile.length > 3) {
                $("#jobProfileError").empty().text('Please select maximum of 2 job profile');
            }
            else {
                $("#jobProfileError").empty().text('');
            }

            if(jobType == null) {
                $("#jobTypeError").empty().text('Please select job type');
            }
            else if(jobType.length > 3) {
                $("#jobTypeError").empty().text('Please select maximim of 2 job type');
            }
            else {
                $("#jobTypeError").empty().text('');
            }

            if(jobLocation == null) {
                $("#jobLocationError").empty().text('Please select job location');
            }
            else if (jobLocation.length > 3) {
                $("#jobLocationError").empty().text('Please select maximum of 2 job location.');
            }
            else {
                $("#jobLocationError").empty().text('');
            }

            if(workExp == "") {
                $("#workExpError").empty().text('Please enter the work experience');
            }
            else {
                $("#workExpError").empty().text('');
            }

            if($("#qualError").text() != "" && $("#jobProfileError").text() != "" && $("#jobTypeError").text() != "" && $("#workExpError").text() != "") { 
                document.getElementById("submitQuestionId").addEventListener("click", function (event) {
                    event.preventDefault();
                });
            }
            else {
                $.ajax({
                    type:'get',
                    url: "/submit-questionarie",
                    data: {'academicQual': academicQual, 'jobProfile': jobProfile, 'jobType':jobType, 'jobLocation':jobLocation, 'workExp':workExp, 'yes': yes, 'no':no, 'school': school, 'course': course, 'entity':entityName },
                    success:function(data){
                        debugger;
                        if(data['thankyou'] != ""){
                            window.location = data['thankyou'];
                        }
                    }
                });
            }
        }
        else if(entity == "AAFT Online"){
            if(state == "" && state != undefined) {
                $("#stateIdError").empty().text('Please select the state');
            }
            else {
                $("#stateIdError").empty().text('');
            }

            if(city == "" && city != undefined) {
                $("#cityError").empty().text('Please select the city');
            }
            else {
                $("#cityError").empty().text('');
            }

            if(academicQual == "") {
                $("#qualError").empty().text('Please select academic qualification');
            }
            else {
                $("#qualError").empty().text('');
            }

            if(empStatus == "" && empStatus != undefined) {
                $("#empStatusError").empty().text('Please select current employment status');
            }
            else {
                $("#empStatusError").empty().text('');
            }

            if(careerSupport == "" && careerSupport != undefined) {
                $("#careerSupportError").empty().text('Please select career support');
            }
            else {
                $("#careerSupportError").empty().text('');
            }

            if(technicalSkill == "" && technicalSkill != undefined) {
                $("#technicalSkillError").empty().text('Please enter your technical skills');
            }
            else {
                $("#technicalSkillError").empty().text('');
            }

            if(jobRole == "" && jobRole != undefined) {
                $("#jobRoleError").empty().text('Please select maximum of 2 job role');
            }
            else {
                $("#jobRoleError").empty().text('');
            }

            if(preferedJob == "" && preferedJob != undefined) {
                $("#intershipExpError").empty().text('Please select prefered job role');
            }
            else {
                $("#intershipExpError").empty().text('');
            }

            if(relocate == "" && relocate != undefined) {
                $("#relocateError").empty().text('Please select willingness to relocate');
            }
            else {
                $("#relocateError").empty().text('');
            }

            if(jobPlace == "" && jobPlace != undefined) {
                $("#stateRelocateIdError").empty().text('Please select preferred job location');
            }
            else {
                $("#stateRelocateIdError").empty().text('');
            }

            if(workType == "" && workType != undefined) {
                $("#workTypeError").empty().text('Please select preferred work type');
            }
            else {
                $("#workTypeError").empty().text('');
            }

            if($("#stateIdError").text() != "" && $("#cityError").text() != "" && $("#qualError").text() != "" && $("#empStatusError").text() != "" && $("#careerSupportError").text() != "" && $("#technicalSkillError").text() != "" && $("#jobRoleError").text() != ""
                && $("#intershipExpError").text() != "" && $("#relocateError").text() != "" && $("#stateRelocateIdError").text() != "" && $("#workTypeError").text() != "") { 
                document.getElementById("submitQuestionId").addEventListener("click", function (event) {
                    event.preventDefault();
                });
            }
            else {
                $.ajax({
                    type:'get',
                    url: "/submit-questionarie",
                    data: {'state': state, 'city': city, 'academicQual':academicQual, 'empStatus':empStatus, 'careerSupport':careerSupport, 'technicalSkill':technicalSkill, 'jobRole':jobRole, 'preferedJob': preferedJob, 'relocate':relocate, 'jobPlace':jobPlace, 'workType':workType, 'entity':entityName, 'school':school, 'course': course, 'yes':yes, 'no':no },
                    success:function(data){
                        if(data != ""){
                            window.location = data['thankyou'];
                        }
                    }
                });
            }
        }
        else if($hdnYes != 1) {
            notPlacement = $("#notPlacementId").val();
            interval = $("#intervalId").val();

            if($("#notPlacementId option:selected").text() == "Seeking after an Interval" && interval == ""){
                $("#intervalError").empty().text('Please enter the duration');
            }
            else {
                $("#intervalError").empty().text('');
            }

            if(notPlacement == "") {
                $("#notPlacementError").empty().text('Please select a reason for not looking for placement.');
            }
            else{
                $("#notPlacementError").empty().text('');
            }

            if($("#intervalError").text() != "" && $("#notPlacementError").text() != "") { 
                document.getElementById("submitQuestionId").addEventListener("click", function (event) {
                    event.preventDefault();
                });
            }
            else {
                $.ajax({
                    type:'get',
                    url: "/submit-questionarie",
                    data: {'notPlacement': notPlacement, 'interval': interval, 'entity': entityName, 'course': course, 'school': school, 'yes':yes, 'no': no },
                    success: function(data){
                        if(data != ""){
                            window.location = data['thankyou'];
                        }
                    }
                });
            }

        }
    }

    function notPlacement() {
        debugger;
        if($("#notPlacementId option:selected").text() == "Seeking after an Interval"){
            $("#intervalDiv").show();
        }
        else {
            $("#intervalDiv").hide();
        }
    }
</script>
@endsection