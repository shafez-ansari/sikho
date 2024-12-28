@extends('home.home-master')

@section('content')
<style>
    .form-container {
      
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .form-header {
        background: #333;
        color: white;
        padding: 10px;
        border-radius: 8px 8px 0 0;
        font-size: 18px;
        font-weight: bold;
        text-align: center;
    }

    .form-section {
        display : grid;
        margin-bottom: 15px;
        Width : 100%;
    }

    .form-section label {
        margin-top: 20px;
        display: block;
        font-weight: bold;
        margin-bottom: 8px;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
    }
    .dropdown-toggle::after {
    display: none;
    margin-left: .255em;
    vertical-align: .255em;
    content: "";
    border-top: .3em solid;
    border-right: .3em solid transparent;
    border-bottom: 0;
    border-left: .3em solid transparent;
}
    .form-submit {
        text-align: center;
    }

    .btn-primary {
        background:rgb(221, 28, 28);
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    .btn-primary:hover {
        background:rgb(196, 18, 18);
    }

    @media (max-width: 768px) {
        .form-container {
            padding: 15px;
        }

        .btn-primary {
            width: 100%;
        }
    }
</style>

<form action="" id="entityFormId">
    @csrf
    <input type="hidden" value="{{ $entityName }}" id="hdnEntityName">
    <input type="hidden" value="{{ $yes }}" id="hdnYes">
    <input type="hidden" value="{{ $no }}" id="hdnNo">
    <input type="hidden" value="{{ $school }}" id="hdnSchool">
    <input type="hidden" value="{{ $course }}" id="hdnCourse">

    <div class="form-container">
        <div class="form-header">Student Details</div>

        @if($yes == 1)
            @if($entityName == "AAFT Noida" || $entityName == "AAFT University")
                <div class="form-section">
                    <label for="qualId">Your last completed academic qualification</label>
                    <select name="qualId" id="qualId" class="form-control">
                        <option value="">--Select--</option>
                        @foreach($academicQual as $qual)
                            <option value="{{$qual->qualification_id}}">{{$qual->qualification_name}}</option>
                        @endforeach
                    </select>
                    <span class="text-danger" id="qualError"></span>
                </div>

                <div class="form-section">
                    <label for="jobProfileId">I want to work for the job profile</label>
                    <select name="jobProfileId" id="jobProfileId" class="form-control" multiple="multiple">
                        @foreach($jobProfile as $job)
                            <option value="{{$job->profile_name}}">{{$job->profile_name}}</option>
                        @endforeach
                    </select>
                    <span class="text-danger" id="jobProfileError"></span>
                </div>

                <div class="form-section">
                    <label for="jobTypeId">What job type are you interested in?</label>
                    <select name="jobTypeId" id="jobTypeId" class="form-control" multiple="multiple">
                        @foreach($jobType as $job)
                            <option value="{{$job->job_type_id}}">{{$job->job_type_name}}</option>
                        @endforeach
                    </select>
                    <span class="text-danger" id="jobTypeError"></span>
                </div>

                <div class="form-section">
                    <label for="jobLocationId">Preferred Location of Job/Employment</label>
                    <select name="jobLocationId" id="jobLocationId" class="form-control" multiple="multiple">
                        @foreach($empLocation as $loc)
                            <option value="{{$loc->emp_loc_id}}">{{$loc->emp_loc_name}}</option>
                        @endforeach
                    </select>
                    <span class="text-danger" id="jobLocationError"></span>
                </div>

                <div class="form-section">
                    <label for="workExp">Relevant Work Experience</label>
                    <input type="number" name="workExp" id="workExp" class="form-control">
                    <span class="text-danger" id="workExpError"></span>
                </div>
            @elseif($entityName == "AAFT Online")
                <div class="form-section">
                    <label for="stateId">State</label>
                    <select name="stateId" id="stateId" class="form-control">
                        <option value="">--Select--</option>
                        @foreach($state as $state)
                            <option value="{{$state->state_id}}">{{$state->state_name}}</option>
                        @endforeach
                    </select>
                    <span class="text-danger" id="stateIdError"></span>
                </div>

                <div class="form-section">
                    <label for="cityId">City</label>
                    <select name="cityId" id="cityId" class="form-control">
                        <option value="">--Select--</option>
                    </select>
                    <span class="text-danger" id="cityError"></span>
                </div>

                <!-- Additional fields as per requirements -->
            @endif
        @else
            <div class="form-section">
                <label for="notPlacementId">Reason for not looking for placement</label>
                <select name="notPlacementId" id="notPlacementId" class="form-control" onchange="notPlacement()">
                    <option value="">--Select--</option>
                    @foreach($reasonNotPlacing as $reason)
                        <option value="{{$reason->not_placement_id}}">{{$reason->not_placement_name}}</option>
                    @endforeach
                </select>
                <span class="text-danger" id="notPlacementError"></span>
            </div>

            <div class="form-section" id="intervalDiv" style="display:none">
                <label for="intervalId">Duration</label>
                <input type="text" id="intervalId" name="intervalId" class="form-control">
                <span class="text-danger" id="intervalError"></span>
            </div>
        @endif

        <div class="form-submit">
            <button type="button" id="submitQuestionId" class="btn btn-primary" onclick="submitQuestionarie()">Submit</button>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $('#jobProfileId, #jobTypeId, #jobLocationId').multiselect({
            numberDisplayed: 1,
            enableFiltering: true
        });
    });

    function submitQuestionarie() {
        // Add form submission logic here
    }

    function notPlacement() {
        if ($("#notPlacementId option:selected").text() == "Seeking after an Interval") {
            $("#intervalDiv").show();
        } else {
            $("#intervalDiv").hide();
        }
    }
</script>

@endsection
