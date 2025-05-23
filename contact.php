<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Reservili</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; line-height: 1.6; background-color: #f4f4f4; overflow-x: hidden; }
        header { background-color: #4CAF50; color: white; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; }
        header h1 { margin: 0; }
        header nav a { color: white; text-decoration: none; margin: 0 10px; font-weight: bold; }
        header nav a:hover { text-decoration: underline; }
        .contact-section { padding: 50px 20px; text-align: center; background: linear-gradient(135deg, #4CAF50, #45a049); color: white; }
        .contact-section h1 { font-size: 40px; margin: 0; animation: fadeInDown 1s ease; }
        .contact-section p { font-size: 18px; margin: 20px auto; max-width: 600px; animation: fadeInUp 1s ease; }
        .form-container { padding: 50px 20px; background: #fff; max-width: 600px; margin: 30px auto; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); animation: fadeIn 1s ease; }
        .form-container h2 { color: #4CAF50; margin-bottom: 20px; }
        .form-container form { display: flex; flex-direction: column; gap: 15px; }
        .form-container input, .form-container textarea, .form-container button { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 16px; }
        .form-container textarea { resize: none; height: 150px; }
        .form-container button { background-color: #4CAF50; color: white; font-weight: bold; cursor: pointer; transition: background-color 0.3s ease; }
        .form-container button:hover { background-color: #45a049; }
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

<section class="contact-section">
    <h1>Contact Us</h1>
    <p>
        Got questions or need support? We'd love to hear from you!  
        Fill out the form below, and we'll get back to you as soon as possible.
    </p>
</section>

<div class="form-container">
    <h2>Get in Touch</h2>
    <form action="submit_contact.php" method="POST">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="email" name="email" placeholder="Your Email" required>
        <textarea name="message" placeholder="Your Message" required></textarea>
        <button type="submit">Send Message</button>
    </form>
</div>

<footer>
    <p>&copy; 2024 Reservili. All rights reserved.</p>
    <p><a href="index.php">Home</a> | <a href="about.php">About Us</a></p>
</footer>
</body>
</html>
