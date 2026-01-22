<div class="mb-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-semibold text-gray-800">Class Allocation Dashboard</h3>
        <div class="space-x-2">
            <button onclick="loadBulkAssignmentTab()" 
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Bulk Assign Students
            </button>
            <button onclick="refreshAllocationDashboard()" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
        </div>
    </div>
    <p class="text-gray-600">Manage student class assignments and monitor class capacity</p>
</div>

<!-- Loading State -->
<div id="allocationLoading" class="text-center py-8">
    <div class="inline-flex items-center">
        <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="text-lg text-gray-600">Loading class allocation data...</span>
    </div>
</div>

<!-- Content Container -->
<div id="allocationContent" class="hidden">
    <!-- Content will be loaded here via AJAX -->
</div>

<script>
// Load class allocation dashboard on tab click
function loadAllocationDashboard() {
    const loadingEl = document.getElementById('allocationLoading');
    const contentEl = document.getElementById('allocationContent');
    
    // Show loading, hide content
    loadingEl.classList.remove('hidden');
    contentEl.classList.add('hidden');
    
    // Fetch the allocation data
    fetch('/admin/class-allocations/dashboard-data')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            // Hide loading, show content
            loadingEl.classList.add('hidden');
            contentEl.classList.remove('hidden');
            contentEl.innerHTML = html;
            
            // Update URL without page reload
            const url = new URL(window.location);
            url.searchParams.set('tab', 'allocation');
            window.history.pushState({}, '', url);
        })
        .catch(error => {
            console.error('Error loading allocation dashboard:', error);
            loadingEl.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <p class="text-red-800">Error loading class allocation data. Please try again.</p>
                    </div>
                    <button onclick="loadAllocationDashboard()" class="mt-2 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition text-sm">
                        Retry
                    </button>
                </div>
            `;
        });
}

// Refresh the dashboard
function refreshAllocationDashboard() {
    loadAllocationDashboard();
}

// Switch to bulk assignment tab
function loadBulkAssignmentTab() {
    const url = new URL(window.location);
    url.searchParams.set('tab', 'bulk-assign');
    window.location.href = url.toString();
}

// Load dashboard when this partial is loaded
document.addEventListener('DOMContentLoaded', function() {
    loadAllocationDashboard();
});
</script>