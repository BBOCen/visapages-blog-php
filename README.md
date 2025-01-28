<h1 align="center">📝 Visapages || Blog en PHP: Publicación y Gestión de Artículos 📝</h1>

<p align="center">
  Este proyecto es un blog desarrollado en PHP que incluye una sección pública y privada. Los usuarios pueden leer los artículos en la sección pública, 
  mientras que los administradores gestionan y organizan el contenido en la parte privada. Además, los usuarios registrados pueden dejar comentarios, 
  y el blog cuenta con funciones avanzadas como detección de IP para asignar una bandera según el país.
</p>

---

<h2>🚀 Características principales</h2>

<h3>📖 Sección Pública y Privada</h3>
<ul>
  <li><strong>Sección pública:</strong> Los visitantes pueden leer artículos sobre diversos temas relacionados con visados.</li>
  <li><strong>Sección privada:</strong> Accesible solo para administradores, quienes tienen la capacidad de crear, editar, eliminar y organizar artículos.</li>
</ul>

<h3>💬 Comentarios y Perfiles de Usuario</h3>
<ul>
  <li><strong>Sistema de comentarios:</strong> Los usuarios registrados pueden dejar comentarios en los artículos.</li>
  <li><strong>Perfiles de usuario:</strong> Los usuarios pueden registrarse y gestionar su perfil dentro del blog.</li>
</ul>

<h3>🌍 Funcionalidades adicionales</h3>
<ul>
  <li><strong>Detección de IP:</strong> El sistema detecta la IP del usuario y asigna automáticamente una bandera según el país de origen.</li>
  <li><strong>Organización de artículos por categorías:</strong> Los artículos se agrupan en categorías para facilitar la navegación.</li>
  <li><strong>Búsqueda por título:</strong> Los usuarios pueden buscar artículos por título en la plataforma.</li>
</ul>

---

<h2>🧰 Requisitos</h2>
<ul>
  <li><strong>XAMPP o WAMP:</strong> Necesitarás un servidor local como XAMPP o WAMP para ejecutar el proyecto.</li>
  <li><strong>Base de datos:</strong> La base de datos está incluida en el archivo <code>visapages.sql</code>.</li>
</ul>

<h2>🚀 Instrucciones para ejecutar</h2>
<ol>
  <li><strong>Clonar o descargar el repositorio:</strong>
    <ul>
      <li>Clona este repositorio usando Git o descarga el archivo ZIP.</li>
    </ul>
  </li>
  <li><strong>Configurar el servidor local:</strong>
    <ul>
      <li>Instala y configura XAMPP o WAMP en tu máquina si no lo tienes.</li>
      <li>Coloca la carpeta del repositorio dentro de la carpeta <code>htdocs</code> de XAMPP o la carpeta <code>www</code> de WAMP.</li>
    </ul>
  </li>
  <li><strong>Crear la base de datos:</strong>
    <ul>
      <li>Abre phpMyAdmin (generalmente en <code>http://localhost/phpmyadmin</code>).</li>
      <li>Crea una nueva base de datos con el nombre de tu elección.</li>
      <li>Importa el archivo <code>visapages.sql</code> dentro de la base de datos creada.</li>
    </ul>
  </li>
  <li><strong>Configurar la conexión a la base de datos:</strong>
    <ul>
      <li>Abre el archivo de configuración de la base de datos (por ejemplo, <code>config.php</code> o un archivo similar en el proyecto).</li>
      <li>Asegúrate de que los detalles de conexión (usuario, contraseña y nombre de base de datos) sean correctos según tu configuración local.</li>
    </ul>
  </li>
  <li><strong>Ejecutar el proyecto:</strong>
    <ul>
      <li>Inicia el servidor Apache y MySQL desde el panel de control de XAMPP o WAMP.</li>
      <li>Abre tu navegador y accede a <code>http://localhost/visapages</code>.</li>
    </ul>
  </li>
</ol>

<p><strong>¡Listo!</strong> Ahora podrás acceder al blog, explorar los artículos y, si eres administrador, gestionar el contenido.</p>

---

<h2>🔧 Contribuciones</h2>
<p>Si deseas contribuir, realiza un fork de este repositorio, realiza los cambios y abre un pull request.</p>

---

El template usado en este proyecto es el siguiente: https://www.free-css.com/free-css-templates/page200/magexpress
