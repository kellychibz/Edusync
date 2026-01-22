{{-- resources/views/teacher/attendance/show.blade.php --}}
@extends('layouts.app')

@section('title', 'View Attendance')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-eye"></i>
                        Attendance Details - {{ $classGroup->name }} - {{ $subject->name }}
                    </h3>
                    <div class="card-tools">
                        <span class="badge bg-primary">{{ $date }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Student Name</th>
                                    <th>Admission No.</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                    <th>Excused</th>
                                    <th>Submitted At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendanceRecords as $index => $record)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $record->student->user->name }}</td>
                                        <td>{{ $record->student->admission_number }}</td>
                                        <td>
                                            <span class="badge" style="background-color: {{ $record->attendanceType->color }};">
                                                {{ $record->attendanceType->name }}
                                            </span>
                                        </td>
                                        <td>{{ $record->notes ?? '-' }}</td>
                                        <td class="text-center">
                                            @if($record->is_excused)
                                                <i class="fas fa-check text-success"></i>
                                            @else
                                                <i class="fas fa-times text-secondary"></i>
                                            @endif
                                        </td>
                                        <td>{{ $record->submitted_at->format('M j, Y g:i A') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($attendanceRecords->isEmpty())
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i>
                            No attendance records found for this date.
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route('teacher.attendance.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection