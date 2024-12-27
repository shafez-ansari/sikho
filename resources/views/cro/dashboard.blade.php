@extends('cro.cro-master')

@section('cro-content')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            max-width: 1600px;
            margin: auto;
            font-size: 14px; /* Set global font size */
        }

        .btn {
            margin: 0px;
            font-size: 14px; /* Match font size for buttons */
        }

        .form-select, .form-control {
            font-size: 14px; /* Match font size for form elements */
        }

        .table {
            font-size: 14px; /* Match font size for table content */
        }

        @media (max-width: 768px) {
            .btn-spacing {
                margin-bottom: 0.5rem;
            }

            .justify-content-between {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                justify-content: center;
            }
        }

        .g-3, .gy-3 {
            display: inherit;
            --bs-gutter-y: 1rem;
        }

        .btn-spacing {
            margin-right: 10px;
        }

        /* Sticky Header */
        .table-responsive {
            max-height: 500px; /* Set height for scrollable table */
            overflow-y: auto;
        }

        .table thead th {
            position: sticky;
            top: 0;
            z-index: 1020;
            background-color: #343a40; /* Matches table-dark */
            color: white;
        }
    </style>
</head>
<body>
    <div class="container mt-3">
        <!-- Top Buttons -->
        <div class="d-flex justify-content-between mb-3">
            <button class="btn btn-danger btn-spacing">View Data</button>
            <button class="btn btn-danger btn-spacing">Upload Data</button>
            <button class="btn btn-danger btn-spacing">Recruiter</button>
        </div>

        <!-- Form Section -->
        <div class="card mb-3">
            <div class="card-header bg-dark text-white">
                View Student Data
            </div>
            <div class="card-body">
                <form class="row g-3">
                    <div class="col-md-2">
                        <select class="form-select" aria-label="Select Entity">
                            <option selected>Select Entity</option>
                            <option value="1">Entity 1</option>
                            <option value="2">Entity 2</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" aria-label="Select School">
                            <option selected>School</option>
                            <option value="1">School 1</option>
                            <option value="2">School 2</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" aria-label="Program Code">
                            <option selected>Program Code</option>
                            <option value="HSM">HSM</option>
                            <option value="LTM">LTM</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" aria-label="Batch Code">
                            <option selected>Batch Code</option>
                            <option value="1223">1223</option>
                            <option value="1224">1224</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" aria-label="Opt-In">
                            <option selected>Opt-In</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-center">
                        <button type="submit" class="btn btn-success w-100 me-2">Submit</button>
                        <button class="btn btn-danger">
                            <i class="bi bi-file-earmark-excel"></i> <!-- Excel Icon -->
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Section -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Entity</th>
                        <th>Unique ID</th>
                        <th>Name</th>
                        <th>Email ID</th>
                        <th>Contact No</th>
                        <th>School</th>
                        <th>Program Code</th>
                        <th>Batch Code</th>
                        <th>Semester</th>
                        <th>Enrollment Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Example Data Array
                    $data = [
                        ["AAFT Online", "AOLTSHM122314599", "Vijender", "vjender.s@aaftonline.com", "9599640953", "Long Term", "HSM", "1223", "03", "13/11/2024"],
                    ];

                    for ($i = 0; $i < 10; $i++) { // Replicating rows for demonstration
                        foreach ($data as $row) {
                            echo "<tr>";
                            foreach ($row as $cell) {
                                echo "<td>$cell</td>";
                            }
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation" class="mt-3">
            <ul class="pagination justify-content-center">
                <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
        </nav>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

@endsection
