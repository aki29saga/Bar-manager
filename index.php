<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bar Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .fixed-buttons {
            position: fixed;
            top: 80%;
            right: 20px;
            transform: translateY(-50%);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .fixed-buttons button {
            background-color: #ffc107;
            color: #000;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            cursor: pointer;
        }

        .fixed-buttons button:hover {
            background-color: #e0a800;
            color: #fff;
        }
        html, body {
            height: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
        }

        .content {
            flex: 1;
        }

        footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 10px 0;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img src="img/logo.jpg" alt="" height="46" width="46"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="menu.php">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">Cart</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="content">
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-7 d-none d-md-block">
                    <img src="img/logo.jpg" alt="Image" class="img-fluid animated-image"> 
                </div>
                <div class="col-md-5 bg-dark text-white rounded p-3">
                    <h1 class="animated-text text-center">The Hideout</h1>
                    <p class="text-center">Lounge</p>
                    <p class="mt-3 text-center">Lets get hyped up with our numiruo variety of drinks and many more all at your pleasure, <br> make an order now...</p>
                    
                    <div class="row justify-content-center">
                        <div class="col-3 bg-warning p-1 rounded shadow m-1">
                            <center>
                                <img src="img/beer.png" alt="" height="80" width="70">
                                <br>
                                <button class="btn btn-default font-weight-bold">Beer</button>
                            </center>
                        </div>
                        <div class="col-3 bg-warning p-1 rounded shadow m-1">
                            <center>
                                <img src="img/wine.png" alt="" height="80" width="70">
                                <br>
                                <button class="btn btn-default font-weight-bold">Wine</button>
                            </center>
                        </div>
                        <div class="col-3 bg-warning p-1 rounded shadow m-1">
                            <center>
                                <img src="img/cocktail.png" alt="" height="80" width="70">
                                <br>
                                <button class="btn btn-default font-weight-bold">Cocktail</button>
                            </center>
                        </div>
                        <hr>
                        <div class="col-10 bg-black rounded shadow m-1 p-3">
                            <p>
                                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-shop" viewBox="0 0 16 16">
                                    <path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.37 2.37 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h1v-5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v5h6V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5M4 15h3v-5H4zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zm3 0h-2v3h2z"/>
                                </svg>
                              <a href="menu.php"><b>Create An Order</b></a>  
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="fixed-buttons">
        <button onclick="location.href='signup.php'">Create Account</button>
        <button onclick="location.href='login.php'">Login</button>
    </div>
    <footer>
        <p>&copy; 2025 Bar Management System. All rights reserved.</p>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  
</body>
</html>
