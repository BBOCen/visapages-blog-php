<!--Este el side que aparacerá en ciertas páginas-->
<div class="col-lg-4 col-md-4">
        <div class="content_bottom_right">
          <div class="single_bottom_rightbar">
            <h2>Artículos recientes</h2>
            <ul class="small_catg popular_catg wow fadeInDown">
                <!--Este código imprime los artículos más recientes-->
                <?php
                obtenerArticulosRecientesSide();
                ?>
            </ul>
          </div>
          <div class="single_bottom_rightbar">
            <ul role="tablist" class="nav nav-tabs custom-tabs">
              <li class="active" role="presentation"><a data-toggle="tab" role="tab" aria-controls="home" href="#mostPopular">Artículos populares</a></li>
              <li role="presentation"><a data-toggle="tab" role="tab" aria-controls="messages" href="#recentComent">Comentarios recientes</a></li>
            </ul>
            <div class="tab-content">
              <div id="mostPopular" class="tab-pane fade in active" role="tabpanel">
                <ul class="small_catg popular_catg wow fadeInDown">
                    <!--Este código imprime los artículos más populares en el side (con más vistas)-->
                    <?php
                    obtenerArticulosPopularesSide();
                    ?>
                </ul>
              </div>
              <div id="recentComent" class="tab-pane fade" role="tabpanel">
                <ul class="small_catg popular_catg">
                    <!--Este código imprime los artículos con comentarios más recientes-->
                    <?php
                    imprimirArticulosComentarios();
                    ?>
                </ul>
              </div>
            </div>
          </div>
          <div class="single_bottom_rightbar wow fadeInDown">
            <h2>Enlaces populares</h2>
            <ul>
              <li><a href="./index.php">Inicio</a></li>
                <li><a href="./articulos.php?pagina=1">Artículos</a></li>
              <li><a href="./login.html">Iniciar sesión</a></li>
              <li><a href="./registrar.html/">Registrar cuenta</a></li>
            </ul>
          </div>
        </div>
      </div>