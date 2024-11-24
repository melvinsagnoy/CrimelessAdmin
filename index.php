<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrimeLess - Enhancing Community Safety</title>
    <!-- Tailwind CSS JIT -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-800 min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="w-full bg-[#242E49] py-4 px-6 flex justify-between items-center">
        <div class="text-2xl font-bold text-white">CrimeLess</div>
        <div class="space-x-4">
            <a href="#about" class="hover:text-[#FDA481] text-white">About</a>
            <a href="#features" class="hover:text-[#FDA481] text-white">Features</a>
            <a href="#contact" class="hover:text-[#FDA481] text-white">Contact</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="flex flex-col items-center justify-center text-center py-20 bg-gradient-to-r from-[#FDA481] via-[#B4182D] to-[#54162B] text-white">
        <h1 class="text-5xl font-bold mb-6">Report with Ease, Fear No Less</h1>
        <p class="text-xl mb-8 max-w-xl">
            Together we build a safer address. CrimeLess empowers communities with real-time alerts, anonymous reporting, and communication channels for a secure environment.
        </p>
        <div class="space-x-4">
            <a href="login.php" class="bg-white text-[#B4182D] px-6 py-3 rounded-lg font-semibold shadow transform transition duration-300 hover:scale-105 hover:shadow-2xl hover:bg-[#B4182D] hover:text-white">Login</a>
            <a href="signup.php" class="bg-white text-[#54162B] px-6 py-3 rounded-lg font-semibold shadow transform transition duration-300 hover:scale-105 hover:shadow-2xl hover:bg-[#54162B] hover:text-white">Sign Up</a>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="w-full py-16 px-8 text-center">
        <h2 class="text-3xl font-bold mb-4 text-[#B4182D]">About CrimeLess</h2>
        <p class="max-w-2xl mx-auto text-gray-600">
            CrimeLess is a mobile application designed to enhance community safety in Cebu City through reliable reporting, collaboration, and alert mechanisms. Stay informed, report anonymously, and make a difference.
        </p>
    </section>

    <!-- Features Section -->
    <section id="features" class="w-full py-16 px-8 bg-gray-100 text-center">
        <h2 class="text-3xl font-bold mb-4 text-[#B4182D]">Features</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-[#FDA481] text-white shadow-lg p-6 rounded-lg transform transition duration-300 hover:scale-105 hover:shadow-2xl hover:bg-white hover:text-[#FDA481]">
                <h3 class="text-xl font-semibold mb-2">Real-Time Alerts</h3>
                <p>
                    Receive instant notifications about crime activities and safety updates.
                </p>
            </div>
            <div class="bg-[#B4182D] text-white shadow-lg p-6 rounded-lg transform transition duration-300 hover:scale-105 hover:shadow-2xl hover:bg-white hover:text-[#B4182D]">
                <h3 class="text-xl font-semibold mb-2">Anonymous Reporting</h3>
                <p>
                    Report incidents without revealing your identity to ensure privacy and security.
                </p>
            </div>
            <div class="bg-[#54162B] text-white shadow-lg p-6 rounded-lg transform transition duration-300 hover:scale-105 hover:shadow-2xl hover:bg-white hover:text-[#54162B]">
                <h3 class="text-xl font-semibold mb-2">Community Collaboration</h3>
                <p>
                    Connect with local responders and collaborate for a safer neighborhood.
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="w-full py-16 px-8">
        <h2 class="text-3xl font-bold mb-4 text-[#B4182D] text-center">Contact Us</h2>
        <p class="max-w-xl mx-auto text-gray-600 mb-4 text-center">
            Have questions or need support? Reach out to us using the information below, and we’ll get back to you as soon as possible.
        </p>
        <div class="text-center">
            <p class="text-lg text-gray-700">
                📧 Email: <a href="mailto:crimeless.05@gmail.com" class="text-[#B4182D] hover:underline">crimeless.05@gmail.com</a>
            </p>
            <p class="text-lg text-gray-700">
                📞 Phone: <a href="tel:09433354427" class="text-[#B4182D] hover:underline">09433354427</a>
            </p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="w-full bg-[#242E49] py-4 text-center">
        <p class="text-white">&copy; 2024 CrimeLess. All rights reserved.</p>
    </footer>
</body>
</html>
