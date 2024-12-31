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


    <div class="action-buttons">
        <button onclick="alert('View Data functionality coming soon!')">View Data</button>
        <button onclick="alert('Upload Data functionality coming soon!')">Upload Data</button>
    </div>

    <div class="upload-section">
        Upload Data
    </div>
    <div >
        <a style="float:right;" href="{{url('/download-student-data-template')}}" >Download Template</a>
    </div>
    <div class="upload-area">
    <img src="{{url('/images/bul.png')}}" alt="Upload Icon">
        <p>Upload Bulk Data</p>
        <input type="file" name="studentFile" id="studentFile" accept=".csv" onchange="checkFile()">
        <span class="text-danger" id="studentValidationId"></span>
    </div>

<script type="text/javascript">
    function checkFile() {
        var file = document.getElementById('studentFile').files[0];
        if (file) {
            var fileName = file.name;
            var fileExt = fileName.split('.').pop();
            if (fileExt != 'csv') {
                //$.notify("Please upload a CSV file", "warning");
                $("#studentValidationId").html('Please upload a CSV file');
                //alert('Please upload a CSV file');
                document.getElementById('studentFile').value = '';
            }
            else {
                $.ajax({
                    url: "/upload-student-data",
                    type: "GET",
                    success: function(data) {
                        if (data == 'File uploaded successfully') {
                            $.notify(data, "success");
                        }
                        else {
                            $.notify(data, "warning");
                        }
                    }
                });
            }
        }
    }
</script>

    @endsection

