@extends('cro.cro-master')

@section('cro-content')

<style>
        .data-upload {
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            margin-bottom: 10px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .download-icon {
            text-align: end;
        }

        .download-icon i {
            font-size: 1.5rem;
            color: #28a745;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .download-icon i:hover {
            transform: scale(1.2);
        }

        .table-container {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .table th, .table td {
            white-space: nowrap;
            text-align: center;
        }

        .pagination-container {
            position: sticky;
            bottom: 0;
            background-color: #fff;
            z-index: 1000;
            padding: 10px 0;
            border-top: 1px solid #ddd;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .data-upload {
                position: static;
            }

            .download-icon {
                padding-right: 50px;
                text-align: Right;
                margin-top: 10px;
            }

            .table-container {
                max-height: 300px;
            }
        }
    </style>
    <div class="container mt-4">

<!-- Filters -->
<div class="data-upload row g-3 align-items-center">
    <form id="dataForm" class="col-md-10 row g-3">
        <div class="col-md-2">
            <select name="entity" class="form-select">
                <option value="">Select Entity</option>
                <option value="AAFT Online">AAFT Online</option>
                <option value="AAFT University">AAFT University</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="school" class="form-select">
                <option value="">School</option>
                <option value="Long Term">Long Term</option>
                <option value="Short Term">Short Term</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="text" name="program_code" class="form-control" placeholder="Program Code">
        </div>
        <div class="col-md-2">
            <input type="text" name="batch_code" class="form-control" placeholder="Batch Code">
        </div>
        <div class="col-md-2">
            <select name="opt_in" class="form-select">
                <option value="">Opt-In</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
        <div class="col-md-2 text-end">
            <button type="submit" class="submit-btn btn btn-danger">Submit</button>
        </div>
    </form>
    <div class="col-md-2 download-icon">
        <i class="fas fa-file-excel" title="Download Excel" onclick="downloadExcel()"></i>
    </div>
</div>

<!-- Scrollable Data Table -->
<div class="table-container">
    <table class="table table-bordered table-striped">
        <thead>
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
        <tbody id="dataRows">
            <?php for ($i = 1; $i <= 50; $i++): ?>
            <tr>
                <td>AAFT Online</td>
                <td>AOLTHSM122134599</td>
                <td>Vijender Singh</td>
                <td>vijender.s@aaftonline.com</td>
                <td>9599640953</td>
                <td>Long Term</td>
                <td>HSM</td>
                <td>1223</td>
                <td>03</td>
                <td>13/11/2024</td>
            </tr>
            <tr>
                <td>AAFT Online</td>
                <td>AOLTHSM122134599</td>
                <td>Vijender Singh</td>
                <td>vijender.s@aaftonline.com</td>
                <td>9599640953</td>
                <td>Long Term</td>
                <td>HSM</td>
                <td>1223</td>
                <td>03</td>
                <td>13/11/2024</td>
            </tr>
            <tr>
                <td>AAFT Online</td>
                <td>AOLTHSM122134599</td>
                <td>Vijender Singh</td>
                <td>vijender.s@aaftonline.com</td>
                <td>9599640953</td>
                <td>Long Term</td>
                <td>HSM</td>
                <td>1223</td>
                <td>03</td>
                <td>13/11/2024</td>
            </tr>
            <tr>
                <td>AAFT Online</td>
                <td>AOLTHSM122134599</td>
                <td>Vijender Singh</td>
                <td>vijender.s@aaftonline.com</td>
                <td>9599640953</td>
                <td>Long Term</td>
                <td>HSM</td>
                <td>1223</td>
                <td>03</td>
                <td>13/11/2024</td>
            </tr>
            <tr>
                <td>AAFT Online</td>
                <td>AOLTHSM122134599</td>
                <td>Vijender Singh</td>
                <td>vijender.s@aaftonline.com</td>
                <td>9599640953</td>
                <td>Long Term</td>
                <td>HSM</td>
                <td>1223</td>
                <td>03</td>
                <td>13/11/2024</td>
            </tr>
            <tr>
                <td>AAFT Online</td>
                <td>AOLTHSM122134599</td>
                <td>Vijender Singh</td>
                <td>vijender.s@aaftonline.com</td>
                <td>9599640953</td>
                <td>Long Term</td>
                <td>HSM</td>
                <td>1223</td>
                <td>03</td>
                <td>13/11/2024</td>
            </tr>
            <tr>
                <td>AAFT Online</td>
                <td>AOLTHSM122134599</td>
                <td>Vijender Singh</td>
                <td>vijender.s@aaftonline.com</td>
                <td>9599640953</td>
                <td>Long Term</td>
                <td>HSM</td>
                <td>1223</td>
                <td>03</td>
                <td>13/11/2024</td>
            </tr>
            <tr>
                <td>AAFT Online</td>
                <td>AOLTHSM122134599</td>
                <td>Vijender Singh</td>
                <td>vijender.s@aaftonline.com</td>
                <td>9599640953</td>
                <td>Long Term</td>
                <td>HSM</td>
                <td>1223</td>
                <td>03</td>
                <td>13/11/2024</td>
            </tr>
            <?php endfor; ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="pagination-container">
    <nav>
        <ul class="pagination">
            <li class="page-item disabled" id="prevPage"><a class="page-link" href="#">Previous</a></li>
            <li class="page-item active" id="page1"><a class="page-link" href="#">1</a></li>
            <li class="page-item" id="page2"><a class="page-link" href="#">2</a></li>
            <li class="page-item" id="page3"><a class="page-link" href="#">3</a></li>
            <li class="page-item" id="nextPage"><a class="page-link" href="#">Next</a></li>
        </ul>
    </nav>
</div>
</div>

<script>
function downloadExcel() {
    alert('Excel download functionality will be implemented!');
    // You can implement Excel export logic here using libraries like SheetJS or server-side PHP/Excel libraries.
}
</script>

@endsection
