<div id="sidebar" class="bg-black text-white min-h-screen flex flex-col transition-all duration-300 ease-in-out w-64">
    <!-- Sidebar Header with Logo -->
    <div id="sidebar-header" class="p-4 flex items-center justify-between border-b border-gray-700">
        <a href = "../src/dashboard.php"><img id="sidebar-logo" src="../assets/crimeless_logo.png" alt="CrimeLess Logo" class="h-10 w-auto"></a> <!-- Adjusted size for better visibility -->
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
                    <!-- User icon for Account Management -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.7 0 4-1.5 4-3s-1.5-3-4-3-4 1.5-4 3 1.5 3 4 3zm0 2c-4 0-7 2-7 4v2h14v-2c0-2-3-4-7-4z" />
                    </svg>
                    <span class="sidebar-text text-white">Account Management</span>
                </a>
            </li>
            <li>
                <a href="../src/arc_management.php" class="flex items-center space-x-3 py-2 hover:bg-gray-800 rounded-md transition-colors">
                    <!-- Archive/Folder icon for ARC Management -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M4 4h16v16H4zM4 8h16M8 4v4" />
                    </svg>
                    <span class="sidebar-text text-white">ARC Management</span>
                </a>
            </li>
            <li>
                <a href="../src/history_reports.php" class="flex items-center space-x-3 py-2 hover:bg-gray-800 rounded-md transition-colors">
                    <!-- Clock icon for History & Reports -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 6v6h4M12 2a10 10 0 100 20 10 10 0 000-20z" />
                    </svg>
                    <span class="sidebar-text text-white">History & Reports</span>
                </a>
            </li>
            <li>
                <a href="../src/newsfeed.php" class="flex items-center space-x-3 py-2 hover:bg-gray-800 rounded-md transition-colors">
                    <!-- Bell icon for News & Notifications -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M15 17h5l-1.405-4.215A2 2 0 0016.721 11H7.279a2 2 0 00-1.874 1.785L4 17h5m0 0v1a3 3 0 006 0v-1m-6 0a3 3 0 006 0" />
                    </svg>
                    <span class="sidebar-text text-white">News & Notifications</span>
                </a>
            </li>
            <li>
                <a href="../src/game_management.php" class="flex items-center space-x-3 py-2 hover:bg-gray-800 rounded-md transition-colors">
                    <!-- Game controller icon for Gamification -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M5 16a2 2 0 114 0H5zm10 0a2 2 0 104 0h-4zM7 6h10M12 6v10" />
                    </svg>
                    <span class="sidebar-text text-white">Gamification</span>
                </a>
            </li>
        </ul>
    </nav>
    <div class="p-4 border-t border-gray-700">
        <a href="../process/logout.php" class="flex items-center space-x-3 py-2 hover:bg-gray-800 rounded-md transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3h-4a3 3 0 01-3-3v-1m3 0a3 3 0 010-6h4a3 3 0 013 3v1z" />
            </svg>
            <span class="sidebar-text text-white">Logout</span>
        </a>
    </div>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById("sidebar");
        const sidebarLogo = document.getElementById("sidebar-logo");
        const sidebarTexts = document.querySelectorAll(".sidebar-text");
        sidebar.classList.toggle("w-16");
        sidebar.classList.toggle("w-64");

        // Hide or show the logo based on the sidebar's collapsed state
        if (sidebar.classList.contains("w-16")) {
            sidebarLogo.classList.add("hidden");
        } else {
            sidebarLogo.classList.remove("hidden");
        }

        setTimeout(() => {
            sidebarTexts.forEach(text => {
                if (sidebar.classList.contains("w-16")) {
                    text.classList.add("hidden");
                } else {
                    text.classList.remove("hidden");
                }
            });
        }, 150);
    }
</script>