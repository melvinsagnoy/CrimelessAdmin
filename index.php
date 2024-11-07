<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrimeLess - Enhancing Community Safety</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-red-500 to-blue-500 text-white min-h-screen flex flex-col items-center justify-center">
    <!-- Navbar -->
    <nav class="w-full bg-gray-800 py-4 px-6 flex justify-between items-center">
        <div class="text-2xl font-bold">CrimeLess</div>
        <div class="space-x-4">
            <a href="#about" class="hover:text-gray-300">About</a>
            <a href="#features" class="hover:text-gray-300">Features</a>
            <a href="#contact" class="hover:text-gray-300">Contact</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="flex flex-col items-center justify-center text-center py-20">
        <h1 class="text-5xl font-bold mb-6">Report with Ease, Fear No Less</h1>
        <p class="text-xl mb-8 max-w-xl">Together we build a safer address. CrimeLess empowers communities with real-time alerts, anonymous reporting, and communication channels for a secure environment.</p>
        <div class="space-x-4">
            <a href="login.php" class="bg-white text-red-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-200">Login</a>
            <a href="signup.php" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-200">Sign Up</a>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="w-full py-16 px-8 bg-gray-900 text-center">
        <h2 class="text-3xl font-bold mb-4">About CrimeLess</h2>
        <p class="max-w-2xl mx-auto text-gray-300">CrimeLess is a mobile application designed to enhance community safety in Cebu City through reliable reporting, collaboration, and alert mechanisms. Stay informed, report anonymously, and make a difference.</p>
    </section>

    <!-- Features Section -->
    <section id="features" class="w-full py-16 px-8 bg-gray-800 text-center">
        <h2 class="text-3xl font-bold mb-4">Features</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-gray-700 p-6 rounded-lg">
                <h3 class="text-xl font-semibold mb-2">Real-Time Alerts</h3>
                <p class="text-gray-300">Receive instant notifications about crime activities and safety updates.</p>
            </div>
            <div class="bg-gray-700 p-6 rounded-lg">
                <h3 class="text-xl font-semibold mb-2">Anonymous Reporting</h3>
                <p class="text-gray-300">Report incidents without revealing your identity to ensure privacy and security.</p>
            </div>
            <div class="bg-gray-700 p-6 rounded-lg">
                <h3 class="text-xl font-semibold mb-2">Community Collaboration</h3>
                <p class="text-gray-300">Connect with local responders and collaborate for a safer neighborhood.</p>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="w-full py-16 px-8 bg-gray-900 text-center">
        <h2 class="text-3xl font-bold mb-4">Contact Us</h2>
        <p class="max-w-xl mx-auto text-gray-300">Have questions or need support? Reach out to our team and we’ll get back to you as soon as possible.</p>
    </section>

    <!-- Footer -->
    <footer class="w-full bg-gray-800 py-4 text-center">
        <p class="text-gray-400">&copy; 2024 CrimeLess. All rights reserved.</p>
    </footer>
</body>
</html>
