@extends('home.home-master')

@section('content')

<h1>Student Details</h1>
<table class="table">
    @foreach($userList as $user)
    <tr>
        <input type="hidden" value="{{ $user->entity_name }}" id="hdnEntity" />
        <input type="hidden" value="{{ $user->school_name }}" id="hdnSchool" />
        <td>Entity Name:</td>
        <td>{{ $user->entity_name }}</td>
        <td>School:</td>
        <td>{{ $user->school_name }}</td>
    </tr>
    <tr>
        <td>Unique ID:</td>
        <td>{{ $user->unique_id }}</td>
        <td>Program Code:</td>
        <td>{{ $user->course_code }}</td>
    </tr>
    <tr>
        <td>Name:</td>
        <td>{{ $user->full_name }}</td>
        <td>Batch Code:</td>
        <td>{{ $user->batch_code }}</td>
    </tr>
    <tr>
        <td>Email Id:</td>
        <td>{{ $user->email }}</td>
        <td>Semester:</td>
        <td>{{ $user->semester }}</td>
    </tr>
    <tr>
        <td>Contact No:</td>
        <td>{{ $user->phone }}</td>
        <td>Enrollment Date:</td>
        <td>{{ $user->enrollment_datr }}</td>
    </tr>
    @endforeach
</table>
<h1>Need Placement?</h1>
<div class="row">
    <div class="col-md-2">
        <input type="checkbox" id="yes" name="response" value="yes">
        <label for="yes">Yes</label>
    </div>
    <div class="col-md-2">
        <input type="checkbox" id="no" name="response" value="no">
        <label for="no">No</label>
    </div>
    <div class="row">
        <span class="text-danger" id="yesornoerror"></span>
    </div>
</div>
<div class="row">
    <button type="submit" class="btn btn-danger" id="btnPlacement" onclick="submitPlacement()">Submit</button>
</div>

<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>

<script type="text/javascript">
    function submitPlacement() {
        debugger;
        entity = $("#hdnEntity").val();
        school = $("#hdnSchool").val();
        if ($('#yes').is(':checked')) {
            yes = true;
        }
        else {
            yes = false;
        }
        if ($('#no').is(':checked')) {
            no = true;
        }
        else {
            no = false;
        }

        if(yes && no){
            $("#yesornoerror").empty().html("Please select yes or no");
        } else if(!yes && !no){
            $("#yesornoerror").empty().html("Please select yes or no");
        } else {
            
            $.ajax({
                type:'get',
                url: "/submit-placement",
                data: {'entity' : entity, 'school' : school, 'yes' : yes, 'no' : no},
                success:function(data){
                    debugger;
                    window.location = data;
                }
            });
        }

    }
</script>

@endsection