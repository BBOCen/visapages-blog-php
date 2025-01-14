<!--Este es el footer-->
<footer id="footer">
  <div class="footer_top">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4">
          <div class="single_footer_top wow fadeInLeft">
            <h2>Artículos Recientes</h2>
              <!--La siguiente función imprime las imágenes de los artículos más recientes del footer-->
            <ul class="flicker_nav">
              <?php
              generarImagenesFooter();
              ?>
            </ul>
          </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
          <div class="single_footer_top wow fadeInDown">
            <h2>Categorías</h2>
            <ul class="labels_nav">
                <!--Esta imprime las categorías del footer-->
              <?php imprimirCategoriasFooter();?>
            </ul>
          </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
          <div class="single_footer_top wow fadeInRight">
            <h2>Nuestra misión</h2>
            <p>Nuestro objetivo es facilitarte la planificación de tus viajes, proporcionándote información confiable, actualizada y clara sobre políticas migratorias, cambios en normativas y consejos prácticos para tus desplazamientos internacionales.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="footer_bottom">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
          <div class="footer_bottom_left">
            <p>Copyright &copy; 2024 <a href="./index.php">visaPages</a></p>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
          <div class="footer_bottom_right">
            <p>Desarrollado por BBO</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>