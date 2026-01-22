@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header Section -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Student Management</h2>
                        <p class="text-gray-600 mt-1">Manage students, classes, and assignments</p>
                    </div>
                    <a class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center" 
                       href="{{ route('admin.students.create') }}">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add New Student
                    </a>
                </div>

                <!-- Success Message -->
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
                @endif

                <!-- Tabs Navigation -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-8">
                        <!-- Students Tab -->
                        <a href="{{ route('admin.students.index') }}" 
                           class="@if(request()->routeIs('admin.students.index') && !request()->has('tab')) border-blue-500 text-blue-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Students List
                        </a>

                        <!-- Class Allocation Tab -->
                        <a href="{{ route('admin.students.index', ['tab' => 'allocation']) }}" 
                           class="@if(request('tab') == 'allocation') border-blue-500 text-blue-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Class Allocation
                        </a>

                        <!-- Bulk Assignment Tab -->
                        <a href="{{ route('admin.students.index', ['tab' => 'bulk-assign']) }}" 
                           class="@if(request('tab') == 'bulk-assign') border-blue-500 text-blue-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Bulk Assignment
                        </a>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- Students List Tab -->
                    @if(!request()->has('tab') || request('tab') == '')
                        @include('admin.students.partials.students-list')
                    
                    <!-- Class Allocation Dashboard Tab -->
                    @elseif(request('tab') == 'allocation')
                        @include('admin.students.partials.class-allocation-dashboard')
                    
                    <!-- Bulk Assignment Tab -->
                    @elseif(request('tab') == 'bulk-assign')
                        @include('admin.students.partials.bulk-assignment')
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


<script>
// Function to switch tabs without page reload
function switchTab(tabName) {
    const url = new URL(window.location);
    url.searchParams.set('tab', tabName);
    
    // Use AJAX to load tab content without full page reload
    if (tabName === 'allocation') {
        // For allocation tab, we'll handle it with the partial's AJAX
        window.location.href = url.toString();
    } else if (tabName === 'bulk-assign') {
        // For bulk assign, we'll handle it with the partial's AJAX  
        window.location.href = url.toString();
    } else {
        // For students list, just update URL
        window.location.href = url.toString();
    }
}

// Handle browser back/forward buttons
window.addEventListener('popstate', function(event) {
    // Reload the page to show correct tab
    window.location.reload();
});
</script>
