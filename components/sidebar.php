<!-- components/sidebar.php -->
<div id="sidebar" class="bg-black text-white min-h-screen flex flex-col transition-all duration-300 ease-in-out w-64">
    <!-- Sidebar Header with Logo -->
    <div class="p-4 flex items-center justify-between border-b border-gray-700">
        <img src="../assets/crimeless_logo.png" alt="CrimeLess Logo" class="h-10 w-auto"> <!-- Adjusted size for better visibility -->
        <button onclick="toggleSidebar()" class="text-gray-300 hover:text-white focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
        </button>
    </div>

    <!-- Sidebar Navigation -->
    <nav class="flex-1 p-4">
        <ul class="space-y-4">
            <li>
                <a href="../src/acc_management.php" class="flex items-center space-x-3 py-2 hover:bg-gray-800 rounded-md transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                    <span class="sidebar-text text-white">Account Management</span>
                </a>
            </li>
            <li>
                <a href="../src/arc_management.php" class="flex items-center space-x-3 py-2 hover:bg-gray-800 rounded-md transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m7-7l-7 7 7 7" />
                    </svg>
                    <span class="sidebar-text text-white">ARC</span>
                </a>
            </li>
            <li>
                <a href="../src/history_reports.php" class="flex items-center space-x-3 py-2 hover:bg-gray-800 rounded-md transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17l3-3-3-3m8 6l-3-3 3-3M3 21h18" />
                    </svg>
                    <span class="sidebar-text text-white">History & Reports</span>
                </a>
            </li>
            <li>
                <a href="../src/newsfeed.php" class="flex items-center space-x-3 py-2 hover:bg-gray-800 rounded-md transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 8h2M3 20h6m2-4h2M3 16h6m2-4h2" />
                    </svg>
                    <span class="sidebar-text text-white">News & Notifications</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center space-x-3 py-2 hover:bg-gray-800 rounded-md transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 11V7a1 1 0 012 0v4h3l-4 4-4-4h3z" />
                    </svg>
                    <span class="sidebar-text text-white">Gamification</span>
                </a>
            </li>
        </ul>
    </nav>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById("sidebar");
        const sidebarTexts = document.querySelectorAll(".sidebar-text");
        sidebar.classList.toggle("w-16");
        sidebar.classList.toggle("w-64");

        // Add a small delay to ensure smooth transition for hiding text
        setTimeout(() => {
            sidebarTexts.forEach(text => {
                if (sidebar.classList.contains("w-16")) {
                    text.classList.add("hidden");
                } else {
                    text.classList.remove("hidden");
                }
            });
        }, 150); // Adjusted delay for smoother text hiding
    }
</script>
