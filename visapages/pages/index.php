<!--Este es el index-->
<!DOCTYPE html>
<html lang="es">
<head>
<title>visaPages | Inicio</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="../assets/css/animate.css">
<link rel="stylesheet" type="text/css" href="../assets/css/slick.css">
<link rel="stylesheet" type="text/css" href="../assets/css/theme.css">
<link rel="stylesheet" type="text/css" href="../assets/css/style.css">
<!--[if lt IE 9]>
<script src="../assets/js/html5shiv.min.js"></script>
<script src="../assets/js/respond.min.js"></script>
<![endif]-->
</head>
<body>
<div id="preloader">
  <div id="status">&nbsp;</div>
</div>

<a class="scrollToTop" href="#"><i class="fa fa-angle-up"></i></a>
<div class="container">
    <?php
    require_once "./elementos_pagina/header.php";
    ?>
  <section id="mainContent">
    <div class="content_top">
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm6">
          <div class="latest_slider">
            <div class="slick_slider">
              <?php
              obtenerArticulosCarouselIndex();
              ?>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm6">
          <div class="content_top_right">
            <ul class="featured_nav wow fadeInDown">
              <?php
              obtenerCarouselAleatorioIndex();
              ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="content_middle">
      <div class="col-lg-3 col-md-3 col-sm-3">
        <div class="content_middle_leftbar">
          <div class="single_category wow fadeInDown">
            <h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <a href="#" class="title_text">descubre</a> </h2>
            <ul class="catg1_nav">
              <?php
              obtenerArticuloAleatorioIndex([1,2]);
              ?>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-6">
        <div class="content_middle_middle">
          <div class="slick_slider2">
            <?php
            obtenerCarouselAleatorioGrande();
            ?>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3">
        <div class="content_middle_rightbar">
          <div class="single_category wow fadeInDown">
            <h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <a href="#" class="title_text">explora</a> </h2>
            <ul class="catg1_nav">
                <?php
                obtenerArticuloAleatorioIndex([3,4]);
                ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="content_bottom">
      <div class="col-lg-8 col-md-8">
        <div class="content_bottom_left">
          <div class="single_category wow fadeInDown">
            <h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <a class="title_text" href=""><?php echo ucwords(obtenerCategoria(1))?></a> </h2>
            <div class="business_category_left wow fadeInDown">
              <ul class="fashion_catgnav">
                <?php
                obtenerArticuloIndex(1, true);
                ?>
              </ul>
            </div>
            <div class="business_category_right wow fadeInDown">
              <ul class="small_catg">
                  <?php
                  obtenerArticuloPequenioIndex(1);
                  ?>
              </ul>
            </div>
          </div>
          <div class="games_fashion_area">
            <div class="games_category">
              <div class="single_category">
                <h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <a class="title_text" href="#"><?php echo ucwords(obtenerCategoria(2))?></a> </h2>
                <ul class="fashion_catgnav wow fadeInDown">
                    <?php
                    obtenerArticuloIndex(2, false);
                    ?>
                </ul>
                <ul class="small_catg wow fadeInDown">
                    <?php
                    obtenerArticuloPequenioIndex(2);
                    ?>
                </ul>
              </div>
            </div>
            <div class="fashion_category">
              <div class="single_category">
                <div class="single_category wow fadeInDown">
                  <h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <a class="title_text" href="#"><?php echo ucwords(obtenerCategoria(3))?></a> </h2>
                  <ul class="fashion_catgnav wow fadeInDown">
                      <?php
                      obtenerArticuloIndex(3, false);
                      ?>
                  </ul>
                  <ul class="small_catg wow fadeInDown">
                      <?php
                      obtenerArticuloPequenioIndex(3);
                      ?>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <div class="technology_catrarea">
            <div class="single_category">
              <h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <a class="title_text" href="#"><?php echo ucwords(obtenerCategoria(4))?></a> </h2>
              <div class="business_category_left">
                <ul class="fashion_catgnav wow fadeInDown">
                    <?php
                    obtenerArticuloIndex(4, false);
                    ?>
                </ul>
              </div>
              <div class="business_category_right">
                <ul class="small_catg wow fadeInDown">
                    <?php
                    obtenerArticuloPequenioIndex(4);
                    ?>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php
      require_once "./elementos_pagina/side.php";
      ?>
    </div>
  </section>
</div>
<?php
include './elementos_pagina/footer.php';
?>
<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
<script src="../assets/js/wow.min.js"></script>
<script src="../assets/js/slick.min.js"></script>
<script src="../assets/js/custom.js"></script>
</body>
</html>