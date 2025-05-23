<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Reservili</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; line-height: 1.6; background-color: #f4f4f4; overflow-x: hidden; }
        header { background-color: #4CAF50; color: white; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; }
        header h1 { margin: 0; }
        header nav a { color: white; text-decoration: none; margin: 0 10px; font-weight: bold; }
        header nav a:hover { text-decoration: underline; }
        .about-section { position: relative; padding: 50px 20px; text-align: center; background: linear-gradient(135deg, #4CAF50, #45a049); color: white; overflow: hidden; }
        .about-section::before, .about-section::after {
            content: '';
            position: absolute;
            width: 200%; height: 200%;
            top: -75%; left: -50%;
            background: rgba(255, 255, 255, 0.05);
            transform: rotate(45deg);
            animation: floating 5s infinite linear;
        }
        .about-section::after { animation-delay: -2.5s; }
        .about-section h1 { font-size: 40px; margin: 0; animation: fadeInDown 1s ease; }
        .about-section p { font-size: 18px; max-width: 600px; margin: 20px auto; animation: fadeInUp 1s ease; }
        .team-section { padding: 50px 20px; background: #fff; text-align: center; }
        .team-section h2 { font-size: 32px; color: #4CAF50; animation: fadeInDown 1s ease; }
        .team-card { margin: 20px auto; max-width: 500px; background: #f9f9f9; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); animation: fadeIn 1s ease; }
        .team-card h3 { margin: 0; color: #333; }
        .team-card p { margin: 10px 0; color: #666; }
        footer { text-align: center; padding: 20px; background: #333; color: white; }
        footer a { color: #4CAF50; text-decoration: none; }
        footer a:hover { text-decoration: underline; }

        /* Animations */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        @keyframes floating {
            from { transform: rotate(45deg) translate(0, 0); }
            to { transform: rotate(45deg) translate(30px, 30px); }
        }
    </style>
</head>
<body>
<header>
    <a href="index.php"><h1>Reservili</h1></a>
    <nav>
        <a href="index.php">Home</a>
        <a href="about.php">About Us</a>
        <a href="contact.php">Contact</a>
    </nav>
</header>

<section class="about-section">
    <h1>About Reservili</h1>
    <p>
        Welcome to Reservili, your go-to platform for managing events and reservations effortlessly.  
        This project was crafted with passion and dedication by me, <strong>Chahine Derbali</strong>,  
        as part of my Web Dynamique coursework.
    </p>
</section>

<
