<x-layouts.app :title="__('My Tasks')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Tasks</h1>
                <p class="text-gray-600 dark:text-gray-400">View and manage your assigned tasks</p>
            </div>
            <div class="flex gap-3">
                <button onclick="refreshTasks()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                    Refresh
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="flex flex-wrap gap-4">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow flex-1 min-w-[200px]">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Tasks</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" id="totalTasks">-</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow flex-1 min-w-[200px]">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" id="pendingTasks">-</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow flex-1 min-w-[200px]">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">In Progress</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" id="inProgressTasks">-</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow flex-1 min-w-[200px]">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 dark:bg-red-900 rounded-lg">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Overdue</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" id="overdueTasks">-</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow flex-1 min-w-[200px]">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Completed</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" id="completedTasks">-</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Task Filters -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="flex flex-wrap gap-4 items-center">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status Filter</label>
                    <select id="statusFilter" onchange="filterTasks()" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Tasks</option>
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="overdue">Overdue</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                    <input type="text" id="taskSearch" onkeyup="filterTasks()" placeholder="Search tasks..." class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

            </div>
        </div>

        <!-- Tasks List -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">My Tasks</h3>
            </div>
            <div id="tasksContainer" class="p-6 space-y-4">
                <!-- Tasks will be loaded here as cards -->
                <div class="text-center text-gray-500 dark:text-gray-400">
                    Loading tasks...
                </div>
            </div>
        </div>
    </div>

    <!-- Task Detail Modal -->
    <div id="taskModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4" onclick="closeTaskModal()">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md mx-4" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Task Details</h3>
                    <button onclick="closeTaskModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="taskModalContent" class="p-4 max-h-[70vh] overflow-y-auto">
                    <!-- Task details will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Enhanced dark mode styling for form elements */
        
        /* Select options styling */
        select option {
            background-color: white;
            color: #111827;
        }
        
        .dark select option {
            background-color: #1f2937;
            color: #f9fafb;
        }
        
        /* Selected options */
        select option:checked {
            background-color: #3b82f6;
            color: white;
        }
        
        .dark select option:checked {
            background-color: #1d4ed8;
            color: white;
        }
        
        /* Select elements */
        select {
            color: #111827 !important;
            background-color: white !important;
        }
        
        .dark select {
            color: #f9fafb !important;
            background-color: #1f2937 !important;
        }
        
        /* Input elements */
        input[type="text"], input[type="email"], input[type="password"], input[type="datetime-local"], textarea {
            color: #111827 !important;
            background-color: white !important;
        }
        
        .dark input[type="text"], .dark input[type="email"], .dark input[type="password"], .dark input[type="datetime-local"], .dark textarea {
            color: #f9fafb !important;
            background-color: #1f2937 !important;
        }
        
        /* Placeholder text */
        input::placeholder, textarea::placeholder {
            color: #6b7280 !important;
        }
        
        .dark input::placeholder, .dark textarea::placeholder {
            color: #9ca3af !important;
        }
        
        /* Focus states */
        select:focus, input:focus, textarea:focus {
            color: #111827 !important;
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1) !important;
        }
        
        .dark select:focus, .dark input:focus, .dark textarea:focus {
            color: #f9fafb !important;
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2) !important;
        }
        
        /* Hover states */
        select:hover, input:hover, textarea:hover {
            border-color: #d1d5db !important;
        }
        
        .dark select:hover, .dark input:hover, .dark textarea:hover {
            border-color: #4b5563 !important;
        }
    </style>

    <script>
        let allTasks = [];

        // Load data when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadTaskStats();
            loadMyTasks();
        });

        // Load task statistics
        function loadTaskStats() {
            fetch('/api/tasks/my-stats')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('totalTasks').textContent = data.stats.total_assigned;
                    document.getElementById('pendingTasks').textContent = data.stats.pending;
                    document.getElementById('inProgressTasks').textContent = data.stats.in_progress;
                    document.getElementById('overdueTasks').textContent = data.stats.overdue;
                    document.getElementById('completedTasks').textContent = data.stats.completed;
                })
                .catch(error => console.error('Error loading stats:', error));
        }

        // Load user's tasks
        function loadMyTasks() {
            fetch('/api/tasks/my-tasks')
                .then(response => response.json())
                .then(data => {
                    allTasks = data.tasks;
                    displayTasks(allTasks);
                })
                .catch(error => console.error('Error loading tasks:', error));
        }

        // Display tasks
        function displayTasks(tasks) {
            const container = document.getElementById('tasksContainer');
            const currentFilter = document.getElementById('statusFilter').value;
            
            if (tasks.length === 0) {
                container.innerHTML = '<div class="text-center text-gray-500 dark:text-gray-400 py-8">No tasks found</div>';
                return;
            }

            container.innerHTML = '';
            
            tasks.forEach(task => {
                const taskCard = createTaskCard(task, currentFilter);
                container.appendChild(taskCard);
            });
        }

        // Create task card
        function createTaskCard(task, currentFilter = '') {
            const card = document.createElement('div');
            card.className = 'bg-white dark:bg-gray-800 rounded-lg shadow border-l-4 ' + getStatusBorderClass(task.status) + ' p-6';
            
            const isOverdue = new Date(task.deadline) < new Date() && task.status !== 'completed';
            const isDueSoon = new Date(task.deadline) <= new Date(Date.now() + 3 * 24 * 60 * 60 * 1000) && new Date(task.deadline) >= new Date() && task.status !== 'completed';
            
            // Status badge logic - respect current filter
            let statusBadge = '';
            
            // filter by a specific status
            if (currentFilter && currentFilter !== 'overdue') {
                statusBadge = `<span class="px-2 py-1 text-xs font-semibold rounded-full ${getStatusClass(task.status)}">${task.status.replace('_', ' ')}</span>`;
            } else {
                // priority system for status badges: OVERDUE > DUE SOON > Status (only when not filtering by specific status)
                if (isOverdue) {
                    statusBadge = '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">OVERDUE</span>';
                } else if (isDueSoon) {
                    statusBadge = '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">DUE SOON</span>';
                } else {
                    statusBadge = `<span class="px-2 py-1 text-xs font-semibold rounded-full ${getStatusClass(task.status)}">${task.status.replace('_', ' ')}</span>`;
                }
            }
            
            card.innerHTML = `
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white">${task.title}</h4>
                            ${statusBadge}
                        </div>
                        ${task.description ? `<p class="text-gray-600 dark:text-gray-400 mb-3">${task.description}</p>` : ''}
                        <div class="grid grid-cols-2 gap-4 text-sm text-gray-500 dark:text-gray-400">
                            <div>
                                <span class="font-medium">Assigned by:</span> ${task.assigned_by.name}
                            </div>
                            <div>
                                <span class="font-medium">Deadline:</span> ${new Date(task.deadline).toLocaleDateString()}
                            </div>
                            <div>
                                <span class="font-medium">Created:</span> ${new Date(task.created_at).toLocaleDateString()}
                            </div>
                            <div>
                                <span class="font-medium">Last updated:</span> ${new Date(task.updated_at).toLocaleDateString()}
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2 ml-4">
                        <button onclick="viewTaskDetails(${task.id})" class="px-3 py-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm border border-blue-300 dark:border-blue-600 rounded hover:bg-blue-50 dark:hover:bg-blue-900">
                            View Details
                        </button>
                        ${task.status !== 'completed' ? `
                            <select onchange="updateTaskStatus(${task.id}, this.value)" class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="pending" ${task.status === 'pending' ? 'selected' : ''}>Pending</option>
                                <option value="in_progress" ${task.status === 'in_progress' ? 'selected' : ''}>In Progress</option>
                                <option value="completed" ${task.status === 'completed' ? 'selected' : ''}>Completed</option>
                            </select>
                        ` : ''}
                    </div>
                </div>
            `;
            
            return card;
        }

        // Filter tasks
        function filterTasks() {
            const statusFilter = document.getElementById('statusFilter').value;
            const searchTerm = document.getElementById('taskSearch').value.toLowerCase();
            
            let filteredTasks = allTasks;
            
            if (statusFilter) {
                if (statusFilter === 'overdue') {
                    // filter for tasks that are overdue (past deadline) and not completed
                    filteredTasks = filteredTasks.filter(task => {
                        const deadline = new Date(task.deadline);
                        const now = new Date();
                        return deadline < now && task.status !== 'completed';
                    });
                } else {
                    filteredTasks = filteredTasks.filter(task => task.status === statusFilter);
                }
            }
            
            if (searchTerm) {
                filteredTasks = filteredTasks.filter(task => 
                    task.title.toLowerCase().includes(searchTerm) || 
                    (task.description && task.description.toLowerCase().includes(searchTerm))
                );
            }
            
            displayTasks(filteredTasks);
        }

        // Show overdue tasks
        function showOverdueTasks() {
            fetch('/api/tasks/my-tasks/overdue')
                .then(response => response.json())
                .then(data => {
                    displayTasks(data.tasks);
                })
                .catch(error => console.error('Error loading overdue tasks:', error));
        }



        // Update task status
        function updateTaskStatus(taskId, newStatus) {
            fetch(`/api/tasks/my-tasks/${taskId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    // update the task in our local array
                    const taskIndex = allTasks.findIndex(task => task.id === taskId);
                    if (taskIndex !== -1) {
                        allTasks[taskIndex].status = newStatus;
                    }
                    
                    // refresh display and stats
                    displayTasks(allTasks);
                    loadTaskStats();
                    
                    // show success message
                    showNotification(data.message, 'success');
                }
            })
            .catch(error => {
                console.error('Error updating task status:', error);
                showNotification('Error updating task status', 'error');
            });
        }

        // View task details
        function viewTaskDetails(taskId) {
            fetch(`/api/tasks/my-tasks/${taskId}`)
                .then(response => response.json())
                .then(data => {
                    const task = data.task;
                    const modalContent = document.getElementById('taskModalContent');
                    
                    modalContent.innerHTML = `
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-xl font-medium text-gray-900 dark:text-white mb-2">${task.title}</h4>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full ${getStatusClass(task.status)}">${task.status.replace('_', ' ')}</span>
                            </div>
                            ${task.description ? `<p class="text-gray-600 dark:text-gray-400">${task.description}</p>` : '<p class="text-gray-500 dark:text-gray-400 italic">No description provided</p>'}
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Assigned by:</span>
                                    <p class="text-gray-600 dark:text-gray-400">${task.assigned_by.name}</p>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Deadline:</span>
                                    <p class="text-gray-600 dark:text-gray-400">${new Date(task.deadline).toLocaleString()}</p>
                                </div
                                <div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Created:</span>
                                    <p class="text-gray-600 dark:text-gray-400">${new Date(task.created_at).toLocaleString()}</p>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Last Updated:</span>
                                    <p class="text-gray-600 dark:text-gray-400">${new Date(task.updated_at).toLocaleString()}</p>
                                </div>
                            </div>
                            ${task.status !== 'completed' ? `
                                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Update Status</label>
                                    <select onchange="updateTaskStatus(${task.id}, this.value)" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="pending" ${task.status === 'pending' ? 'selected' : ''}>Pending</option>
                                        <option value="in_progress" ${task.status === 'in_progress' ? 'selected' : ''}>In Progress</option>
                                        <option value="completed" ${task.status === 'completed' ? 'selected' : ''}>Completed</option>
                                    </select>
                                </div>
                            ` : ''}
                        </div>
                    `;
                    
                    document.getElementById('taskModal').classList.remove('hidden');
                })
                .catch(error => console.error('Error loading task details:', error));
        }

        // Close task modal
        function closeTaskModal() {
            document.getElementById('taskModal').classList.add('hidden');
        }

        // Refresh tasks
        function refreshTasks() {
            loadTaskStats();
            loadMyTasks();
            showNotification('Tasks refreshed', 'success');
        }

        // Helper functions
        function getStatusClass(status) {
            switch(status) {
                case 'pending': return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
                case 'in_progress': return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
                case 'completed': return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                default: return 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
            }
        }

        function getStatusBorderClass(status) {
            switch(status) {
                case 'pending': return 'border-yellow-500';
                case 'in_progress': return 'border-blue-500';
                case 'completed': return 'border-green-500';
                default: return 'border-gray-500';
            }
        }

        function showNotification(message, type) {
            // simple notification - you can enhance this with a proper notification library
            alert(message);
        }



        // close modal when pressing Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('taskModal').classList.contains('hidden')) {
                closeTaskModal();
            }
        });
    </script>
</x-layouts.app> 