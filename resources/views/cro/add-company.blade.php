@extends('cro.cro-master')

@section('cro-content')

<style>
        /* General Reset */
 
        nav {
            justify-content: space-around;
            background-color: #f5f5f5;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
        }
        nav button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }
        nav button:hover {
            background-color: #c0392b;
        }
        .main-content {
            margin-top: 20px;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .heading-section {
            background-color: #000;
            color: #fff;
            padding: 10px;
            font-size: 18px;
            font-weight: bold;
            text-align: left;
            margin-bottom: 20px;
        }
        .form-table-wrapper {
            display: flex;
            align-items: flex-start;
            gap: 20px;
        }
        .form-section {
            flex: 1;
        }
        .form-section input {
            width: 300px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-section button {
            margin-top: 10px;
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            margin-left: 0px;
        }
        .form-section button:hover {
            background-color: #2980b9;
        }
        table {
            flex: 2;
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #f4f4f4;
        }
        .add-record {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #f10000;
            font-weight: bold;
        }
        .add-record:hover {
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            header, nav, .main-content {
                padding: 10px;
            }
            .form-table-wrapper {
                flex-direction: column;
            }
            .form-section input {
                width: 100%;
            }
            nav {
                flex-wrap: wrap;
            }
        }
    </style>
    <main class="main-content">
        <div class="heading-section">Adding New Company/If Already Existing Company</div>
        <div class="form-table-wrapper">
            <div class="form-section">
                <form>
                    <input type="text" placeholder="Enter New Company Name">
                    <button type="button">Show Details</button>
                </form>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Spoke Persons</th>
                        <th>Entity</th>
                        <th>School</th>
                        <th>Course</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Githin</td>
                        <td>Online</td>
                        <td>Music</td>
                        <td>BA in Music</td>
                    </tr>
                    <tr>
                        <td>Githin</td>
                        <td>Online</td>
                        <td>Music</td>
                        <td>BA in Music</td>
                    </tr>
                    <tr>
                        <td>Githin</td>
                        <td>Online</td>
                        <td>Music</td>
                        <td>BA in Music</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <a href="#" class="add-record">Add New Record</a>
    </main>

@endsection