@extends('cro.cro-master')

@section('cro-content')

<style>
        /* General Styles */
        body {
    font-family: Arial, sans-serif;
    margin: 0px;
    padding: 0px;
    box-sizing: border-box;
        }

        /* Action Buttons */
        .action-buttons {
            gap: 20px;
            margin: 20px 0;
        }

        .action-buttons button {
            width: auto;
            background-color: #f8d7da;
            color: #dc3545;
            border: 1px solid #dc3545;
            padding: 10px 20px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
        }

        .action-buttons button:hover {
            background-color: #dc3545;
            color: white;
        }

        /* Upload Section */
        .upload-section {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
        }

        .upload-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 2px dashed #ccc;
            margin: 20px auto;
            padding: 40px;
            max-width: 400px;
            border-radius: 10px;
            background-color: #f9f9f9;
            color: #333;
        }

        .upload-area img {
            width: 80px;
            margin-bottom: 10px;
        }

        .upload-area p {
            font-size: 16px;
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            .logo-row {
                flex-wrap: wrap;
                gap: 20px;
            }

            .progress-bar {
                flex-wrap: wrap;
                gap: 10px;
            }

            .progress-bar hr {
                width: 50px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 10px;
            }

            .upload-area {
                width: 90%;
            }
        }
    </style>
    <!-- <div class="action-buttons">
        <button onclick="alert('View Data functionality coming soon!')">View Data</button>
        <button onclick="alert('Upload Data functionality coming soon!')">Upload Data</button>
    </div> -->
    <div class="upload-section">
        Upload Data
    </div>
    <div >
        <a style="float:right;" href="{{url('/download-student-data-template')}}" >Download Template</a>
    </div>
    <div class="upload-area">
    <img src="{{url('/images/bul.png')}}" alt="Upload Icon">
        <p>Upload Bulk Data</p>
           
        <form id="uploadForm" enctype="multipart/form-data">
            @csrf
            <input type="file" name="studentFile" id="studentFile" accept=".csv" required>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>

<script type="text/javascript">
   
    $('#uploadForm').on('submit', function(e) {
    e.preventDefault();

    var file = document.getElementById('studentFile').files[0];
    if (!file) {
        $('#studentValidationId').html('Please select a file to upload.');
        return;
    }

    var fileExt = file.name.split('.').pop().toLowerCase();
    if (fileExt != 'csv') {
        $('#studentValidationId').html('Please upload a valid CSV file.');
        return;
    }

    var formData = new FormData();
    formData.append('studentFile', file);
    formData.append('_token', '{{ csrf_token() }}');

    $.ajax({
        url: "/upload.student.data",
        method: "POST",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            alert(response.message);
        },
        error: function(xhr) {
            alert(xhr.responseJSON.message);
        }
    });
    });
</script>

    @endsection

