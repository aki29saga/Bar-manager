<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Website</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Custom CSS for animations */
        .animated-text {
            font-size: 2rem;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .animated-image {
            animation: slideIn 1s ease-in-out;
        }

        @keyframes slideIn {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }

        /* Background Image Slider */
        .slideshow-container {
            position: relative;
            max-width: 100%;
            margin: auto;
        }

        .mySlides {
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%; 
            background-size: cover;
            background-position: center;
            transition: opacity 0.5s ease-in-out;
        }

        .active {
            opacity: 1;
        }

        /* Fading effect between images */
        .fade {
            animation-name: fade;
            animation-duration: 1.5s;
        }

        @keyframes fade {
            from {opacity: .4} 
            to {opacity: 1}
        }

        /* Next & previous buttons */
        .prev,
        .next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            width: auto;
            margin-top: -22px;
            padding: 16px;
            color: white;
            font-weight: bold;
            font-size: 18px;
            transition: 0.6s ease;
            border-radius: 0 3px 3px 0;
            user-select: none;
        }

        .next {
            right: 0;
            border-radius: 3px 0 0 3px;
        }

        .prev:hover,
        .next:hover {
            background-color: rgba(0,0,0,0.8);
        }

        /* Caption text */
        .text {
            color: #f2f2f2;
            font-size: 15px;
            padding: 8px 12px;
            position: absolute;
            bottom: 8px;
            width: 100%;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="slideshow-container">

        <div class="mySlides fade">
            <img src="img/logo.jpg" style="width:100%">
        </div>

        <div class="mySlides fade">
            <img src="img/logo.jpg" style="width:100%">
        </div>

        <div class="mySlides fade">
            <img src="img/logo.jpg" style="width:100%">
        </div>

        <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
        <a class="next" onclick="plusSlides(1)">&#10095;</a>

    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <h1 class="animated-text">Welcome to My Website</h1>
                <p class="mt-3">This is a simple home page with some basic animations using Bootstrap.</p>
                <button class="btn btn-primary">Learn More</button>
            </div>
            <div class="col-md-6">
                <img src="img/logo.jpg" alt="Image" class="img-fluid animated-image"> 
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        let slideIndex = 0;
        showSlides();

        function plusSlides(n) {
            showSlides(slideIndex += n);
        }

        function showSlides() {
            let i;
            let slides = document.getElementsByClassName("mySlides");
            if (n > slides.length) {slideIndex = 1}    
            if (n < 1) {slideIndex = slides.length}
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slides[slideIndex-1].style.display = "block";
        }
    </script>

</body>
</html>