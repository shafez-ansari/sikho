@extends('home.home-master')

@section('content')

<style>
/* Custom Checkbox Styling */
.custom-checkbox {
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    background-color: #f0f0f0;
    border: 2px solid #ccc;
    border-radius: 5px;
    width: 80px;
    height: 40px;
    text-align: center;
    transition: all 0.3s ease;
}



.custom-checkbox input[type="checkbox"] {
    display: none;
}

.custom-checkbox span {
    font-weight: bold;
    color: #555;
}

.custom-checkbox:hover {
    border-color: #999;
}

.custom-checkbox input[type="checkbox"]:checked + span {
    background-color: #ff0a0a;
    color: #fff;
    padding: 5px 15px;
    border-radius: 5px;
}
</style>

<!-- Student Details Card -->
<div style="box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); border-radius: 10px; padding: 20px; max-width: 800px;margin: 30px auto; font-family: Arial, sans-serif;">
    <h5 style="text-transform: uppercase; text-align: Left; background-color: #333; color: #fff; padding: 10px; border-radius: 5px; font-size: 14px;">Student Details</h5>
    <div style="display: flex; align-items: center; margin-top: 20px;">
        <div style="text-align: center; flex: 1; position: relative;">
            
            <!-- Student Image -->
            @foreach($userList as $user)
                @if($user->img_name != null)
                <img id="studentImage" src="{{ asset('storage/'.$user->img_name) }}" alt="Student Avatar" style="border-radius: 50%; width: 120px; height: 120px; object-fit: cover;">
                @else
                <img id="studentImage" src="{{url('/images/user.jpg')}}" alt="Student Avatar" style="border-radius: 50%; width: 120px; height: 120px; object-fit: cover;">
                @endif
            @endforeach
            <!-- Edit Image Icon -->
            <label for="uploadImage" style="cursor: pointer; position: unset; bottom: -60px; right: 80px; background-color: #fff; border-radius: 50%; padding: 6px; box-shadow: 0px 2px 4px rgba(0,0,0,0.2);">
                <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 0 24 24" width="20px" fill="#555">
                    <path d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1.003 1.003 0 0 0 0-1.42l-2.34-2.34c-.39-.39-1.02-.39-1.42 0L15.13 4.5l3.75 3.75 1.83-1.83z"></path>
                </svg>
            </label>
            <input type="file" id="uploadImage" accept="image/*" style="display: none;" onchange="previewImage(event)">
        </div>
        <div style="flex: auto; text-align: left; margin-left: 20px;">
            <table style="width: 100%; border-collapse: collapse;">
                @foreach($userList as $user)
                <tr>
                    <input type="hidden" value="{{ $user->entity_name }}" id="hdnEntity" />
                    <input type="hidden" value="{{ $user->school_name }}" id="hdnSchool" />
                    <input type="hidden" value="{{ $user->course_name }}" id="hdnCourse" />
                    <td style="font-weight: bold; padding: 8px;">Entity Name:</td>
                    <td style="padding: 8px;">{{ $user->entity_name }}</td>
                    <td style="font-weight: bold; padding: 8px;">School:</td>
                    <td style="padding: 8px;">{{ $user->school_name }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; padding: 8px;">Unique ID:</td>
                    <td style="padding: 8px;">{{ $user->unique_id }}</td>
                    <td style="font-weight: bold; padding: 8px;">Program Code:</td>
                    <td style="padding: 8px;">{{ $user->course_code }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; padding: 8px;">Name:</td>
                    <td style="padding: 8px;">{{ $user->full_name }}</td>
                    <td style="font-weight: bold; padding: 8px;">Batch Code:</td>
                    <td style="padding: 8px;">{{ $user->batch_code }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; padding: 8px;">Email:</td>
                    <td style="padding: 8px;">{{ $user->email }}</td>
                    <td style="font-weight: bold; padding: 8px;">Semester:</td>
                    <td style="padding: 8px;">{{ $user->batch_code }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; padding: 8px;">Contact:</td>
                    <td style="padding: 8px;">{{ $user->phone }}</td>
                    <td style="font-weight: bold; padding: 8px;">Enrollment Date:</td>
                    <td style="padding: 8px;">{{ $user->enrollment_datr }}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>

<!-- Placement Section -->
<div style="box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); border-radius: 10px; padding: 20px; max-width: 800px; text-align: left; margin: 30px auto;margin-top: 40px;">
    <h5 style="text-transform: uppercase; text-align: Left; background-color: #333; color: #fff; padding: 10px; border-radius: 5px;">Need Placement?</h5>
    <div style="text-align: Left; margin-top: 20px; display: inline-flex; justify-content: center; gap: 20px;">
        <!-- Yes Checkbox -->
        <label class="custom-checkbox">
            <input type="checkbox" id="yes" onchange="toggleCheckbox('yes')">
            <span>Yes</span>
        </label>
        <!-- No Checkbox -->
        <label class="custom-checkbox">
            <input type="checkbox" id="no" onchange="toggleCheckbox('no')">
            <span>No</span>
        </label>
    </div>
    <div style="text-align: center; width:750; margin-top: 20px;">
        <span id="yesornoerror" style="color: red;"></span>
        <button type="button" onclick="submitPlacement()" style="background-color: #f80901; border: none; color: white; padding: 10px 40px; border-radius: 5px; margin-top: 10px; cursor: pointer;">Submit</button>
    </div>
</div>


<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>

<script type="text/javascript">
    function toggleCheckbox(selected) {
        // Ensure only one checkbox is selected at a time
        if (selected === 'yes') {
            document.getElementById('no').checked = false;
        } else if (selected === 'no') {
            document.getElementById('yes').checked = false;
        }
        document.getElementById('yesornoerror').textContent = ''; // Clear error
    }

    function submitPlacement() {
        
        const yesChecked = document.getElementById('yes').checked;
        const noChecked = document.getElementById('no').checked;
        const errorElement = document.getElementById('yesornoerror');
        entity = document.getElementById('hdnEntity').value;
        school = document.getElementById('hdnSchool').value;
        course = document.getElementById('hdnCourse').value;
        if ($('#yes').is(':checked')) {
            yes = 1;
        }
        else {
            yes = 0;
        }
        if ($('#no').is(':checked')) {
            no = 1;
        }
        else {
            no = 0;
        }
        // Reset error
        errorElement.textContent = '';

        if (yesChecked && noChecked) {
            errorElement.textContent = "Please select only one option.";
        } else if (!yesChecked && !noChecked) {
            errorElement.textContent = "Please select either Yes or No.";
        } else {
            debugger;
            let url = `/store-placement/${entity}/${school}/${yes}/${no}/${course}`;
            window.location.href = url;
            // You can redirect or send an AJAX request here
        }
    }

</script>

@endsection