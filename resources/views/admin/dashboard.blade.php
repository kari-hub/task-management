<x-layouts.app :title="__('Admin Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Admin Dashboard</h1>
                <p class="text-gray-600 dark:text-gray-400">Manage users and tasks</p>
            </div>
            <div class="flex gap-3">
                <button onclick="refreshData()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                    Refresh
                </button>
                <button onclick="openUserModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Add User
                </button>
                <button onclick="openTaskModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                    New Task
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="flex flex-wrap gap-4">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow flex-1 min-w-[200px]">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" id="totalUsers">-</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow flex-1 min-w-[200px]">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <div class="p-2 bg-red-100 dark:bg-red-900 rounded-lg">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Overdue Tasks</p>
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
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Completed Tasks</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white" id="completedTasks">-</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Tabs -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex space-x-8 px-6" aria-label="Tabs">
                    <button onclick="showTab('users')" class="tab-button active py-4 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600 dark:text-blue-400">
                        Users
                    </button>
                    <button onclick="showTab('tasks')" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                        Tasks
                    </button>
                </nav>
            </div>

            <!-- Users Tab -->
            <div id="users-tab" class="tab-content p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">User Management</h3>
                    <div class="flex gap-2">
                        <input type="text" id="userSearch" onkeyup="filterAdminUsers()" placeholder="Search users..." class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Assigned Tasks</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <!-- Users will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tasks Tab -->
            <div id="tasks-tab" class="tab-content hidden p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Task Management</h3>
                    <div class="flex gap-2">
                        <select id="taskStatusFilter" onchange="filterAdminTasks()" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="overdue">Overdue</option>
                        </select>
                        <input type="text" id="taskSearch" onkeyup="filterAdminTasks()" placeholder="Search tasks..." class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <div id="tasksContainer" class="space-y-4">
                    <!-- Tasks will be loaded here as cards -->
                </div>
            </div>
        </div>
    </div>

    <!-- User Modal -->
    <div id="userModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4" onclick="closeUserModal()">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl mx-4" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white" id="userModalTitle">Add New User</h3>
                    <button onclick="closeUserModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-4">
                    <!-- User Details Form -->
                    <form id="userForm" class="mb-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                                <input type="text" id="userName" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                <input type="email" id="userEmail" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    </div>
                        <div class="mb-3" id="passwordField">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                            <input type="password" id="userPassword" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Leave blank to keep current password when editing</p>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeUserModal()" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                            Cancel
                        </button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg" id="userSubmitBtn">
                            Add User
                        </button>
                    </div>
                </form>

                    <!-- User's Assigned Tasks Section (only shown when editing) -->
                    <div id="userTasksSection" class="hidden">
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-3">
                            <h4 class="text-md font-medium text-gray-900 dark:text-white mb-3">Assigned Tasks</h4>
                            <div id="userTasksList" class="space-y-2 max-h-48 overflow-y-auto">
                                <!-- Tasks will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Task Modal -->
    <div id="taskModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4" onclick="closeTaskModal()">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md mx-4" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Create New Task</h3>
                    <button onclick="closeTaskModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="taskForm" class="p-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title</label>
                        <input type="text" id="taskTitle" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                        <textarea id="taskDescription" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Assign To</label>
                        <select id="taskAssignedTo" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select User</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deadline</label>
                        <input type="datetime-local" id="taskDeadline" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeTaskModal()" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                            Create Task
                        </button>
                    </div>
                </form>
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
        // load data when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardStats();
            loadUsers();
            loadTasks();
        });

        // tab functionality
        function showTab(tabName) {
            // hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // remove active class from all tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active', 'border-blue-500', 'text-blue-600', 'dark:text-blue-400');
                button.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
            });
            
            // show selected tab content
            document.getElementById(tabName + '-tab').classList.remove('hidden');
            
            // add active class to selected tab button
            event.target.classList.add('active', 'border-blue-500', 'text-blue-600', 'dark:text-blue-400');
            event.target.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
        }

        // load dashboard statistics
        function loadDashboardStats() {
            fetch('/api/admin/stats')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('totalUsers').textContent = data.stats.total_users;
                    document.getElementById('totalTasks').textContent = data.stats.total_tasks;
                    document.getElementById('overdueTasks').textContent = data.stats.overdue_tasks;
                    document.getElementById('completedTasks').textContent = data.stats.completed_tasks;
                })
                .catch(error => console.error('Error loading stats:', error));
        }

        // load users
        function loadUsers() {
            fetch('/api/admin/users')
                .then(response => response.json())
                .then(data => {
                    allAdminUsers = data.users;
                    displayAdminUsers(allAdminUsers);
                })
                .catch(error => console.error('Error loading users:', error));
        }

        // load tasks
        function loadTasks() {
            fetch('/api/admin/tasks')
                .then(response => response.json())
                .then(data => {
                    allAdminTasks = data.tasks;
                    displayAdminTasks(allAdminTasks, '');
                })
                .catch(error => console.error('Error loading tasks:', error));
        }

        // modal functions
        let currentEditingUserId = null;

        function openUserModal() {
            currentEditingUserId = null;
            document.getElementById('userModalTitle').textContent = 'Add New User';
            document.getElementById('userSubmitBtn').textContent = 'Add User';
            document.getElementById('userPassword').required = true;
            document.getElementById('userTasksSection').classList.add('hidden');
            document.getElementById('userForm').reset();
            document.getElementById('userModal').classList.remove('hidden');
        }

        function closeUserModal() {
            document.getElementById('userModal').classList.add('hidden');
            document.getElementById('userForm').reset();
            currentEditingUserId = null;
        }

        function openTaskModal() {
            document.getElementById('taskModal').classList.remove('hidden');
            loadUsersForTaskAssignment();
        }

        function closeTaskModal() {
            document.getElementById('taskModal').classList.add('hidden');
            document.getElementById('taskForm').reset();
        }

        // load users for task assignment dropdown
        function loadUsersForTaskAssignment() {
            fetch('/api/admin/users')
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById('taskAssignedTo');
                    select.innerHTML = '<option value="">Select User</option>';
                    
                    data.users.forEach(user => {
                        select.innerHTML += `<option value="${user.id}">${user.name}</option>`;
                    });
                })
                .catch(error => console.error('Error loading users for assignment:', error));
        }

        // form submissions
        document.getElementById('userForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                name: document.getElementById('userName').value,
                email: document.getElementById('userEmail').value
            };

            // add password only if provided (for updates) or required (for new users)
            const password = document.getElementById('userPassword').value;
            if (password || currentEditingUserId === null) {
                formData.password = password;
            }

            const url = currentEditingUserId 
                ? `/api/admin/users/${currentEditingUserId}`
                : '/api/admin/users';
            
            const method = currentEditingUserId ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                    closeUserModal();
                    loadUsers();
                    loadDashboardStats();
                }
            })
            .catch(error => {
                console.error('Error saving user:', error);
                alert('Error saving user data');
            });
        });

        document.getElementById('taskForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // get form values
            const title = document.getElementById('taskTitle').value.trim();
            const description = document.getElementById('taskDescription').value.trim();
            const assignedTo = document.getElementById('taskAssignedTo').value;
            const deadline = document.getElementById('taskDeadline').value;
            
            // validate form
            if (!title) {
                alert('Please enter a task title');
                return;
            }
            
            if (!assignedTo) {
                alert('Please select a user to assign the task to');
                return;
            }
            
            if (!deadline) {
                alert('Please select a deadline');
                return;
            }
            
            const formData = {
                title: title,
                description: description,
                assigned_to: assignedTo,
                deadline: deadline
            };

            console.log('Submitting task form:', formData);

            fetch('/api/admin/tasks', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.message) {
                    alert(data.message);
                    closeTaskModal();
                    loadTasks();
                    loadDashboardStats();
                }
            })
            .catch(error => {
                console.error('Error creating task:', error);
                alert('Error creating task: ' + error.message);
            });
        });

        // filter functions
        let allAdminTasks = [];
        let allAdminUsers = [];

        // filter admin tasks
        function filterAdminTasks() {
            const statusFilter = document.getElementById('taskStatusFilter').value;
            const searchTerm = document.getElementById('taskSearch').value.toLowerCase();
            
            let filteredTasks = allAdminTasks;
            
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
            
            displayAdminTasks(filteredTasks, statusFilter);
        }

        // filter admin users
        function filterAdminUsers() {
            const searchTerm = document.getElementById('userSearch').value.toLowerCase();
            
            let filteredUsers = allAdminUsers;
            
            if (searchTerm) {
                filteredUsers = filteredUsers.filter(user => 
                    user.name.toLowerCase().includes(searchTerm) || 
                    user.email.toLowerCase().includes(searchTerm)
                );
            }
            
            displayAdminUsers(filteredUsers);
        }

        // display admin tasks
        function displayAdminTasks(tasks, currentFilter = '') {
            const container = document.getElementById('tasksContainer');
            container.innerHTML = '';
            
            if (tasks.length === 0) {
                container.innerHTML = '<div class="text-center text-gray-500 dark:text-gray-400 py-8">No tasks found</div>';
                return;
            }
            
            tasks.forEach(task => {
                const statusClass = getStatusClass(task.status);
                const isOverdue = new Date(task.deadline) < new Date() && task.status !== 'completed';
                const isDueSoon = new Date(task.deadline) <= new Date(Date.now() + 3 * 24 * 60 * 60 * 1000) && new Date(task.deadline) >= new Date() && task.status !== 'completed';
                
                // status badge logic - respect current filter
                let statusBadge = '';
                
                // filter by a specific status
                if (currentFilter && currentFilter !== 'overdue') {
                    statusBadge = `<span class="px-2 py-1 text-xs font-semibold rounded-full ${statusClass}">${task.status.replace('_', ' ')}</span>`;
                } else {
                    // priority system for status badges: OVERDUE > DUE SOON > Status (only when not filtering by specific status)
                    if (isOverdue) {
                        statusBadge = '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">OVERDUE</span>';
                    } else if (isDueSoon) {
                        statusBadge = '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">DUE SOON</span>';
                    } else {
                        statusBadge = `<span class="px-2 py-1 text-xs font-semibold rounded-full ${statusClass}">${task.status.replace('_', ' ')}</span>`;
                    }
                }
                
                const card = `
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow border-l-4 ${getStatusBorderClass(task.status)} p-6">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    <h4 class="text-lg font-medium text-gray-900 dark:text-white">${task.title}</h4>
                                    ${statusBadge}
                                </div>
                                ${task.description ? `<p class="text-gray-600 dark:text-gray-400 mb-3">${task.description}</p>` : ''}
                                <div class="grid grid-cols-2 gap-4 text-sm text-gray-500 dark:text-gray-400">
                                    <div>
                                        <span class="font-medium">Assigned to:</span> ${task.assigned_to.name}
                                    </div>
                                    <div>
                                        <span class="font-medium">Deadline:</span> ${new Date(task.deadline).toLocaleDateString()}
                                    </div>
                                    <div>
                                        <span class="font-medium">Created:</span> ${new Date(task.created_at).toLocaleDateString()}
                                    </div>
                                    <div>
                                        <span class="font-medium">Assigned by:</span> ${task.assigned_by.name}
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-2 ml-4">
                                <button onclick="editTask(${task.id})" class="px-3 py-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm border border-blue-300 dark:border-blue-600 rounded hover:bg-blue-50 dark:hover:bg-blue-900">
                                    Edit
                                </button>
                                <button onclick="deleteTask(${task.id})" class="px-3 py-1 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm border border-red-300 dark:border-red-600 rounded hover:bg-red-50 dark:hover:bg-red-900">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                container.innerHTML += card;
            });
        }

        // display admin users
        function displayAdminUsers(users) {
            const tbody = document.getElementById('usersTableBody');
            tbody.innerHTML = '';
            
            if (users.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No users found</td></tr>';
                return;
            }
            
            users.forEach(user => {
                const row = `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">${user.name}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">${user.email}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">${user.assigned_tasks_count || 0}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="editUser(${user.id})" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">Edit</button>
                            <button onclick="deleteUser(${user.id})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
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

        // edit user function
        function editUser(userId) {
            currentEditingUserId = userId;
            document.getElementById('userModalTitle').textContent = 'Edit User';
            document.getElementById('userSubmitBtn').textContent = 'Update User';
            document.getElementById('userPassword').required = false;
            document.getElementById('userTasksSection').classList.remove('hidden');
            
            // Load user data
            fetch(`/api/admin/users/${userId}`)
                .then(response => response.json())
                .then(data => {
                    const user = data.user;
                    document.getElementById('userName').value = user.name;
                    document.getElementById('userEmail').value = user.email;
                    document.getElementById('userPassword').value = '';
                    
                    // Load user's tasks
                    displayUserTasks(user.assigned_tasks);
                    
                    document.getElementById('userModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error loading user:', error);
                    alert('Error loading user data');
                });
        }

        // display user's tasks
        function displayUserTasks(tasks) {
            const container = document.getElementById('userTasksList');
            
            if (tasks.length === 0) {
                container.innerHTML = '<p class="text-gray-500 dark:text-gray-400 text-center py-2">No tasks assigned to this user</p>';
                return;
            }
            
            container.innerHTML = '';
            tasks.forEach(task => {
                const isOverdue = new Date(task.deadline) < new Date() && task.status !== 'completed';
                const statusClass = getStatusClass(task.status);
                
                const taskCard = `
                    <div class="bg-gray-50 dark:bg-gray-700 rounded p-2 border-l-4 ${getStatusBorderClass(task.status)}">
                        <div class="flex justify-between items-start">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-1 mb-1">
                                    <h5 class="font-medium text-gray-900 dark:text-white text-sm truncate">${task.title}</h5>
                                    <span class="px-1.5 py-0.5 text-xs font-semibold rounded-full ${statusClass} flex-shrink-0">${task.status.replace('_', ' ')}</span>
                                    ${isOverdue ? '<span class="px-1.5 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 flex-shrink-0">OVERDUE</span>' : ''}
                                </div>
                                ${task.description ? `<p class="text-xs text-gray-600 dark:text-gray-400 mb-1 line-clamp-2">${task.description}</p>` : ''}
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    <span class="font-medium">Deadline:</span> ${new Date(task.deadline).toLocaleDateString()}
                                </div>
                            </div>
                            <div class="flex gap-1 ml-2 flex-shrink-0">
                                <button onclick="reassignTask(${task.id})" class="px-1.5 py-0.5 text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 text-xs border border-green-300 dark:border-green-600 rounded hover:bg-green-50 dark:hover:bg-green-900">
                                    Reassign
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                container.innerHTML += taskCard;
            });
        }



        // reassign task
        function reassignTask(taskId) {
            // get all users for reassignment dropdown
            fetch('/api/admin/users')
                .then(response => response.json())
                .then(data => {
                    const users = data.users;
                    
                    // create reassignment modal
                    const reassignModal = `
                        <div id="reassignModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50" onclick="closeReassignModal()">
                            <div class="flex items-center justify-center min-h-screen p-4" onclick="event.stopPropagation()">
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md mx-4">
                                    <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Reassign Task</h3>
                                        <button onclick="closeReassignModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="p-4">
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select New User</label>
                                            <select id="reassignUserSelect" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                <option value="">Choose a user...</option>
                                                ${users.map(user => `<option value="${user.id}">${user.name} (${user.email})</option>`).join('')}
                                            </select>
                                        </div>
                                        <div class="flex justify-end gap-3">
                                            <button onclick="closeReassignModal()" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                                                Cancel
                                            </button>
                                            <button onclick="confirmReassignTask(${taskId})" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                                                Reassign
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // add modal to page
                    document.body.insertAdjacentHTML('beforeend', reassignModal);
                })
                .catch(error => {
                    console.error('Error loading users for reassignment:', error);
                    alert('Error loading users for reassignment');
                });
        }

        // close reassign modal
        function closeReassignModal() {
            const modal = document.getElementById('reassignModal');
            if (modal) {
                modal.remove();
            }
        }

        // confirm task reassignment
        function confirmReassignTask(taskId) {
            const newUserId = document.getElementById('reassignUserSelect').value;
            
            if (!newUserId) {
                alert('Please select a user to reassign the task to');
                return;
            }

            fetch(`/api/admin/tasks/${taskId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    assigned_to: newUserId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                    closeReassignModal();
                    
                    // refresh the user's tasks if in edit mode
                    if (currentEditingUserId) {
                        fetch(`/api/admin/users/${currentEditingUserId}`)
                            .then(response => response.json())
                            .then(data => {
                                displayUserTasks(data.user.assigned_tasks);
                            })
                            .catch(error => console.error('Error refreshing user tasks:', error));
                    }
                    
                    // also refresh the main tasks list
                    loadTasks();
                    loadDashboardStats();
                }
            })
            .catch(error => {
                console.error('Error reassigning task:', error);
                alert('Error reassigning task');
            });
        }

        function deleteUser(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                fetch(`/api/admin/users/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    loadUsers();
                    loadDashboardStats();
                })
                .catch(error => console.error('Error deleting user:', error));
            }
        }

        function editTask(taskId) {
            alert('Edit task functionality will be implemented');
        }

        function deleteTask(taskId) {
            if (confirm('Are you sure you want to delete this task?')) {
                fetch(`/api/admin/tasks/${taskId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    loadTasks();
                    loadDashboardStats();
                })
                .catch(error => console.error('Error deleting task:', error));
            }
        }

        // refresh data function
        function refreshData() {
            loadDashboardStats();
            loadUsers();
            loadTasks();
            showNotification('Data refreshed', 'success');
        }



        // close modals when pressing Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (!document.getElementById('userModal').classList.contains('hidden')) {
                    closeUserModal();
                }
                if (!document.getElementById('taskModal').classList.contains('hidden')) {
                    closeTaskModal();
                }
                if (document.getElementById('reassignModal')) {
                    closeReassignModal();
                }
            }
        });

        function showNotification(message, type) {
            // simple notification
            alert(message);
        }

        // test function to debug task creation
        function testTaskCreation() {
            const testData = {
                title: 'Test Task',
                description: 'This is a test task',
                assigned_to: '2', // Assuming user ID 2 exists
                deadline: new Date(Date.now() + 24 * 60 * 60 * 1000).toISOString().slice(0, 16)
            };

            console.log('Testing task creation with data:', testData);

            fetch('/api/admin/tasks', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(testData)
            })
            .then(response => {
                console.log('Test response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Test response data:', data);
                alert('Test completed. Check console for details.');
            })
            .catch(error => {
                console.error('Test error:', error);
                alert('Test failed: ' + error.message);
            });
        }
    </script>
</x-layouts.app> 