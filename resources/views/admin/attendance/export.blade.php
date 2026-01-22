{{-- resources/views/admin/attendance/export.blade.php --}}
@extends('layouts.app')

@section('title', 'Export Attendance')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-download"></i>
                        Attendance Export - {{ \Carbon\Carbon::parse($month)->format('F Y') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        In a production environment, this would generate a downloadable CSV or Excel file.
                        For now, here's a preview of the data that would be exported.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Student</th>
                                    <th>Admission No.</th>
                                    <th>Class</th>
                                    <th>Subject</th>
                                    <th>Teacher</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                    <th>Excused</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendanceRecords as $record)
                                    <tr>
                                        <td>{{ $record->attendance_date->format('Y-m-d') }}</td>
                                        <td>{{ $record->student->user->name }}</td>
                                        <td>{{ $record->student->admission_number }}</td>
                                        <td>{{ $record->classGroup->name }}</td>
                                        <td>{{ $record->subject->name }}</td>
                                        <td>{{ $record->teacher->user->name }}</td>
                                        <td>{{ $record->attendanceType->name }}</td>
                                        <td>{{ $record->notes ?? '' }}</td>
                                        <td>{{ $record->is_excused ? 'Yes' : 'No' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <h6>Export Options:</h6>
                        <div class="btn-group">
                            <button class="btn btn-success" disabled>
                                <i class="fas fa-file-excel"></i> Export to Excel
                            </button>
                            <button class="btn btn-primary" disabled>
                                <i class="fas fa-file-csv"></i> Export to CSV
                            </button>
                            <button class="btn btn-danger" disabled>
                                <i class="fas fa-file-pdf"></i> Export to PDF
                            </button>
                        </div>
                        <small class="form-text text-muted">
                            Export functionality will be implemented in the next phase.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
