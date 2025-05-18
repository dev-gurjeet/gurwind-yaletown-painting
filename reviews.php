
    <?php
    // --- PHP: Handle POST and fetch reviews from DB ---

    //LOCAL
    // $host = "localhost";
    // $user = "root";
    // $pass = "";
    // $dbname = "painting_reviews"; // Make sure this DB and table exist

    //LIVE
    $host = "localhost";
    $user = "u995171199_yaletown_user";
    $pass = "2F]a$04@Bk";
    $dbname = "u995171199_yaletown"; // Make sure this DB and table exist

    $conn = new mysqli($host, $user, $pass, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Handle POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['text'], $_POST['rating'])) {
        $name = $conn->real_escape_string(strip_tags($_POST['name']));
        $text = $conn->real_escape_string(strip_tags($_POST['text']));
        $rating = intval($_POST['rating']);
        $rating = max(1, min(5, $rating));
        $date = date('Y-m-d');
        $conn->query("INSERT INTO reviews (name, text, rating, date) VALUES ('$name', '$text', $rating, '$date')");
        // Redirect to avoid resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Pagination
    $reviewsPerPage = 5;
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $offset = ($page - 1) * $reviewsPerPage;

    // Get total count
    $totalResult = $conn->query("SELECT COUNT(*) as cnt FROM reviews");
    $totalRow = $totalResult->fetch_assoc();
    $totalReviews = $totalRow['cnt'];
    $totalPages = ceil($totalReviews / $reviewsPerPage);

    // Fetch reviews
    $res = $conn->query("SELECT * FROM reviews ORDER BY id DESC LIMIT $offset, $reviewsPerPage");
    $reviews = [];
    while ($row = $res->fetch_assoc()) $reviews[] = $row;
    ?>
<!-- filepath: g:\server-php-7.4\htdocs\painting-html-template-placehold\reviews.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reviews | Yaletown Painting</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/font-awesome.min.css" />
    <style>
        .review-form {
            background: #f5f5f5;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 40px;
        }

        .review-list {
            margin-top: 30px;
        }

        .review-item {
            background: #fff;
            border-radius: 6px;
            box-shadow: 0 2px 8px #eee;
            padding: 20px;
            margin-bottom: 20px;
        }

        .review-item .review-name {
            font-weight: bold;
        }

        .review-pagination {
            margin: 30px 0;
            text-align: center;
        }

        .review-pagination button {
            margin: 0 5px;
        }

        .star-rating {
            font-size: 1.5em;
            color: #ffd700;
            cursor: pointer;
        }

        .star-rating .fa-star-o {
            color: #ccc;
        }

        .star-rating input {
            display: none;
        }

        .review-stars {
            color: #ffd700;
        }
    </style>
</head>

<body>
    <!-- Header/Menu (copy from your index.html if needed) -->
    <div class="temp_banner_wrapper banner_main_bg wow fadeIn">
        <div class="container-fluid">
            <div class="temp_header_p">
                <div class="top_header">
                    <div class="row">
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <div class="logo">
                                <a href="index.html">Yaletown Painting</a>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                            <div class="top_head_right">
                                <ul class="top_ul_info">
                                    <li>
                                        <div class="temp_li_flex">
                                            <span>
                                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 512 512" width="30px" height="30px">
                                                    <g>
                                                        <path d="M256,0C161.896,0,85.333,76.563,85.333,170.667c0,28.25,7.063,56.26,20.49,81.104L246.667,506.5
													c1.875,3.396,5.448,5.5,9.333,5.5s7.458-2.104,9.333-5.5l140.896-254.813c13.375-24.76,20.438-52.771,20.438-81.021
													C426.667,76.563,350.104,0,256,0z M256,256c-47.052,0-85.333-38.281-85.333-85.333c0-47.052,38.281-85.333,85.333-85.333
													s85.333,38.281,85.333,85.333C341.333,217.719,303.052,256,256,256z" />
                                                    </g>
                                                </svg>
                                            </span>
                                            <span class="top_contact_span">33 Bellini Ave,<br>Brampton, Ontario, L6P0E2</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="temp_header">
                    <div class="row">
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="temp_menu_wrapper">
                                <nav class="navbar" id="nav">
                                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                        <div class="temp_menu nav navbar-nav">
                                            <ul class="right">
                                                <li><a href="index.html">home</a></li>
                                                <li><a href="about.html">about us</a></li>
                                                <li><a href="project.html">projects</a></li>
                                                <li><a href="#"> Services</a>
                                                    <ul class="submenu">
                                                        <li><a href="interior.html">Interior Painting</a></li>
                                                        <li><a href="exterior.html">Exterior Painting</a></li>
                                                    </ul>
                                                </li>
                                                <li><a href="contact.html"> contact</a></li>
                                                <li><a href="reviews.php"> Reviews</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <button type="button" class="navbar-toggle collapsed center3" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar temp_toggle1"></span>
                                <span class="icon-bar temp_toggle2"></span>
                                <span class="icon-bar temp_toggle3"></span>
                            </button>
                            <div class="temp_menu_wrapper_right">
                                <a href="contact.html" class="temp_tooltip">
                                    <span>
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="15px" height="15px" viewBox="0 0 488.152 488.152">
                                            <g>
                                                <path d="M177.854,269.311c0-6.115-4.96-11.069-11.08-11.069h-38.665c-6.113,0-11.074,4.954-11.074,11.069v38.66
											c0,6.123,4.961,11.079,11.074,11.079h38.665c6.12,0,11.08-4.956,11.08-11.079V269.311L177.854,269.311z" />
                                                <path d="M274.483,269.311c0-6.115-4.961-11.069-11.069-11.069h-38.67c-6.113,0-11.074,4.954-11.074,11.069v38.66
											c0,6.123,4.961,11.079,11.074,11.079h38.67c6.108,0,11.069-4.956,11.069-11.079V269.311z" />
                                                <path d="M371.117,269.311c0-6.115-4.961-11.069-11.074-11.069h-38.665c-6.12,0-11.08,4.954-11.08,11.069v38.66
											c0,6.123,4.96,11.079,11.08,11.079h38.665c6.113,0,11.074-4.956,11.074-11.079V269.311z" />
                                                <path d="M177.854,365.95c0-6.125-4.96-11.075-11.08-11.075h-38.665c-6.113,0-11.074,4.95-11.074,11.075v38.653
											c0,6.119,4.961,11.074,11.074,11.074h38.665c6.12,0,11.08-4.956,11.08-11.074V365.95L177.854,365.95z" />
                                                <path d="M274.483,365.95c0-6.125-4.961-11.075-11.069-11.075h-38.67c-6.113,0-11.074,4.95-11.074,11.075v38.653
											c0,6.119,4.961,11.074,11.074,11.074h38.67c6.108,0,11.069-4.956,11.069-11.074V365.95z" />
                                                <path d="M371.117,365.95c0-6.125-4.961-11.075-11.069-11.075h-38.67c-6.12,0-11.08,4.95-11.08,11.075v38.653
											c0,6.119,4.96,11.074,11.08,11.074h38.67c6.108,0,11.069-4.956,11.069-11.074V365.95L371.117,365.95z" />
                                                <path d="M440.254,54.354v59.05c0,26.69-21.652,48.198-48.338,48.198h-30.493c-26.688,0-48.627-21.508-48.627-48.198V54.142
											h-137.44v59.262c0,26.69-21.938,48.198-48.622,48.198H96.235c-26.685,0-48.336-21.508-48.336-48.198v-59.05
											C24.576,55.057,5.411,74.356,5.411,98.077v346.061c0,24.167,19.588,44.015,43.755,44.015h389.82
											c24.131,0,43.755-19.889,43.755-44.015V98.077C482.741,74.356,463.577,55.057,440.254,54.354z M426.091,422.588
											c0,10.444-8.468,18.917-18.916,18.917H80.144c-10.448,0-18.916-8.473-18.916-18.917V243.835c0-10.448,8.467-18.921,18.916-18.921
											h327.03c10.448,0,18.916,8.473,18.916,18.921L426.091,422.588L426.091,422.588z" />
                                                <path d="M96.128,129.945h30.162c9.155,0,16.578-7.412,16.578-16.567V16.573C142.868,7.417,135.445,0,126.29,0H96.128
											C86.972,0,79.55,7.417,79.55,16.573v96.805C79.55,122.533,86.972,129.945,96.128,129.945z" />
                                                <path d="M361.035,129.945h30.162c9.149,0,16.572-7.412,16.572-16.567V16.573C407.77,7.417,400.347,0,391.197,0h-30.162
											c-9.154,0-16.577,7.417-16.577,16.573v96.805C344.458,122.533,351.881,129.945,361.035,129.945z" />
                                            </g>
                                        </svg>
                                    </span>
                                    <span class="tooltiptext">make an apointment</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="temp_inner_banner wow fadeIn">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h1 class="heading">Reviews</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html" class="paragraph">Home</a></li>
                        <li class="breadcrumb-item active"><span class="paragraph">reviews</span></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="temp_getintouch_wrapper">
        <svg class="svg_border2 color_gry" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100"
            preserveAspectRatio="none" height="50" width="100%">
            <path class="exqute-fill-white"
                d="M790.5,93.1c-59.3-5.3-116.8-18-192.6-50c-29.6-12.7-76.9-31-100.5-35.9c-23.6-4.9-52.6-7.8-75.5-5.3 c-10.2,1.1-22.6,1.4-50.1,7.4c-27.2,6.3-58.2,16.6-79.4,24.7c-41.3,15.9-94.9,21.9-134,22.6C72,58.2,0,25.8,0,25.8V100h1000V65.3 c0,0-51.5,19.4-106.2,25.7C839.5,97,814.1,95.2,790.5,93.1z">
            </path>
        </svg>
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <?php if (empty($reviews)): ?>
                        <p class="text-center">No reviews yet. Be the first to write one!</p>
                    <?php else: ?>
                        <?php foreach ($reviews as $r): ?>
                            <div class="review-item">
                                <div class="review-name"><?= htmlspecialchars($r['name']) ?></div>
                                <div class="review-date" style="font-size:12px;color:#888;"><?= htmlspecialchars($r['date']) ?></div>
                                <div class="review-stars">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <span class="fa <?= $i <= $r['rating'] ? 'fa-star' : 'fa-star-o' ?>"></span>
                                    <?php endfor; ?>
                                </div>
                                <div class="review-text" style="margin-top:10px;"><?= nl2br(htmlspecialchars($r['text'])) ?></div>
                            </div>
                        <?php endforeach; ?>
                        <div class="review-pagination">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <a href="?page=<?= $i ?>" class="btn btn-default<?= $i == $page ? ' btn-primary' : '' ?>"><?= $i ?></a>
                            <?php endfor; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="temp_getintouch_wrapper bg-white">
        <svg class="svg_border2 color_white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100"
            preserveAspectRatio="none" height="50" width="100%">
            <path class="exqute-fill-white"
                d="M790.5,93.1c-59.3-5.3-116.8-18-192.6-50c-29.6-12.7-76.9-31-100.5-35.9c-23.6-4.9-52.6-7.8-75.5-5.3 c-10.2,1.1-22.6,1.4-50.1,7.4c-27.2,6.3-58.2,16.6-79.4,24.7c-41.3,15.9-94.9,21.9-134,22.6C72,58.2,0,25.8,0,25.8V100h1000V65.3 c0,0-51.5,19.4-106.2,25.7C839.5,97,814.1,95.2,790.5,93.1z">
            </path>
        </svg>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center" style="margin:40px 0 20px;">
                    <h1 class="heading">Share your Reviews</h1>
                    <p class="paragraph italic">Share your experience with Yaletown Painting!</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <!-- Review Form -->
                    <form id="reviewForm" class="review-form" method="post" action="">
                        <div class="form-group">
                            <label for="reviewName">Name</label>
                            <input type="text" class="form-control" id="reviewName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="reviewText">Your Review</label>
                            <textarea class="form-control" id="reviewText" name="text" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Your Rating</label>
                            <div class="star-rating" id="starRating">
                                <span data-value="1" class="fa fa-star-o"></span>
                                <span data-value="2" class="fa fa-star-o"></span>
                                <span data-value="3" class="fa fa-star-o"></span>
                                <span data-value="4" class="fa fa-star-o"></span>
                                <span data-value="5" class="fa fa-star-o"></span>
                            </div>
                            <input type="hidden" name="rating" id="rating" value="0" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </form>
                    <!-- Reviews List -->
                    <div class="review-list" id="reviewList"></div>
                    <!-- Pagination -->
                    <div class="review-pagination" id="reviewPagination"></div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Star rating UI
        const stars = document.querySelectorAll('#starRating .fa');
        const ratingInput = document.getElementById('rating');
        stars.forEach(star => {
            star.addEventListener('mouseover', function() {
                const val = parseInt(this.getAttribute('data-value'));
                stars.forEach((s, i) => {
                    s.classList.toggle('fa-star', i < val);
                    s.classList.toggle('fa-star-o', i >= val);
                });
            });
            star.addEventListener('mouseout', function() {
                const val = parseInt(ratingInput.value);
                stars.forEach((s, i) => {
                    s.classList.toggle('fa-star', i < val);
                    s.classList.toggle('fa-star-o', i >= val);
                });
            });
            star.addEventListener('click', function() {
                const val = parseInt(this.getAttribute('data-value'));
                ratingInput.value = val;
                stars.forEach((s, i) => {
                    s.classList.toggle('fa-star', i < val);
                    s.classList.toggle('fa-star-o', i >= val);
                });
            });
        });
    </script>
    
    <div class="temp_footer_wrapper">
        <svg class="svg_border1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none" height="50" width="100%">
            <path class="elementor-shape-fill" d="M421.9,6.5c22.6-2.5,51.5,0.4,75.5,5.3c23.6,4.9,70.9,23.5,100.5,35.7c75.8,32.2,133.7,44.5,192.6,49.7
 c23.6,2.1,48.7,3.5,103.4-2.5c54.7-6,106.2-25.6,106.2-25.6V0H0v30.3c0,0,72,32.6,158.4,30.5c39.2-0.7,92.8-6.7,134-22.4
 c21.2-8.1,52.2-18.2,79.7-24.2C399.3,7.9,411.6,7.5,421.9,6.5z"></path>
        </svg>
        <div class="container">
            <div class="row">
                <div class="temp_footer_Section">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                        <div class="logo">
                            <a href="index.html">Yaletown Painting</a>
                        </div>
                        <div class="footer_section  bottompadder60 ">
                            <p>An expert painter brings color to life, turning spaces into art. To clients, it's a masterpiece; to others, a fresh transformation.</p>
                        </div>
                    </div>
                    <!-- <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 ">
					<div class="footer_section  bottompadder60 ">
						<p class="subheading">Recent Post</p>
						<div class="footer_flex bottompadder20">
							<div class="footer_left">
								<a href="blog.html"><img src="http://placehold.it/74x74" alt="painter-recent-post" class="img-responsive"></a>
							</div>
							<div class="footer_right">
								<a href="blog.html">Cambridge amico</a>
								<p>Angleso it va semblar un used simplificat Angles</p>
							</div>
						</div>
						<div class="footer_flex">
							<div class="footer_left">
								<a href="blog.html"><img src="http://placehold.it/74x74" alt="painter-recent-post2" class="img-responsive"></a>
							</div>
							<div class="footer_right">
								<a href="blog.html">Cambridge amico</a>
								<p>Angleso it va semblar un used simplificat Angles</p>
							</div>
						</div>
					</div>
				</div> -->
                    <!-- <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 ">
					<div class="footer_section f_sec3 bottompadder60 ">
						<p class="subheading">Instagram Post</p>
						<ul>
							<li><a href="#"><img src="/assets/images/test2.jpg" alt="painter-insta1" class="img-responsive"></a></li>
							<li><a href="#"><img src="/assets/images/test2.jpg" alt="painter-insta2" class="img-responsive"></a></li>
							<li><a href="#"><img src="/assets/images/test2.jpg" alt="painter-insta3" class="img-responsive"></a></li>
							<li><a href="#"><img src="/assets/images/test2.jpg" alt="painter-insta4" class="img-responsive"></a></li>
							<li><a href="#"><img src="/assets/images/test2.jpg" alt="painter-insta5" class="img-responsive"></a></li>
							<li><a href="#"><img src="/assets/images/test2.jpg" alt="painter-insta6" class="img-responsive"></a></li>
						</ul>
					</div>
				</div> -->
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                        <div class="footer_section sec4 bottompadder60 ">
                            <p class="subheading ">Contact Info</p>
                            <ul>
                                <li><span><i class="fa fa-map" aria-hidden="true"></i></span><span>33 Bellini Ave, Brampton, Ontario, L6P0E2.</span></li>
                                <li><a href="mailto:Yaletownpainting35@gmail.com"><span><i class="fa fa-envelope" aria-hidden="true"></i></span><span>Yaletownpainting35@gmail.com</span></a></li>
                                <li><span><i class="fa fa-phone-square" aria-hidden="true"></i></span><span>+16472852035</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="temp_copyright text-center">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <p class="paragraph">Copyright &copy; 2021, All Rights Reserved by Yaletown Painting. </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>