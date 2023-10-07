<?php

const ARCHIVO_DESTINOS = 'peliculas.json';

$vistas = [];
$pendientes = [];

if (file_exists(ARCHIVO_DESTINOS) && filesize(ARCHIVO_DESTINOS) > 0) {
    $contenidoArchivo = file_get_contents(ARCHIVO_DESTINOS);
    $peliculasAnteriores = json_decode($contenidoArchivo, true);

    foreach ($peliculasAnteriores as $pelicula) {
        if ($pelicula['raiting'] == '' || $pelicula['raiting'] == null) {
            $pendientes[] = $pelicula;
        } else {
            $vistas[] = $pelicula;
        }
    }
} else {
    $peliculasAnteriores = [];
    $vistas = [];
    $pendientes = [];
}

function guardarPeliculas(array $peliculas)
{
    $contenidoArchivo = json_encode($peliculas);
    file_put_contents(ARCHIVO_DESTINOS, $contenidoArchivo);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['eliminar_vista'])) {
        $idEliminar = $_POST['eliminar_vista'];
      
        array_splice($vistas, $idEliminar, 1);
        $peliculasAnteriores = array_merge($vistas, $pendientes);
        guardarPeliculas($peliculasAnteriores);
            
        
    }elseif (isset($_POST['eliminar_pendiente'])) {
        $idEliminar = $_POST['eliminar_pendiente'];

        array_splice($pendientes, $idEliminar, 1);
        $peliculasAnteriores = array_merge($vistas, $pendientes);
        guardarPeliculas($peliculasAnteriores);
    
        header("Location: index.php");
        exit(); 
    } elseif (isset($_POST['nombre']) || isset($_POST['raiting'])) {
        $nuevaPelicula = [
            'nombre' => $_POST['nombre'],
            'raiting' => $_POST['raiting'],
        ];

        // Agregar la nueva película al array correspondiente
        if ($nuevaPelicula['raiting'] == '' || $nuevaPelicula['raiting'] == null) {
            $pendientes[] = $nuevaPelicula;
        } else {
            $vistas[] = $nuevaPelicula;
        }

        $peliculasAnteriores = array_merge($vistas, $pendientes);
        guardarPeliculas($peliculasAnteriores);

        header("Location: index.php");
        exit(); 
    }
}

$peliculas = file_exists(ARCHIVO_DESTINOS) ? json_decode(file_get_contents(ARCHIVO_DESTINOS), true) : [];

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilo.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Lista Películas</title>
</head>

<body>
    <div class="container-fluid">
        <h1 class="text-center m-3">Películas</h1>

        <div class="card border-info mb-5 card-hover ">
            <!-- Formulario para agregar películas -->
            <form method="post" action="" class="p-3">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="nombre" id="destinoInput" placeholder="Nueva película" autocomplete="off" required>
                    <label for="destinoInput">Nueva película</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="raiting" id="fechaInput" placeholder="Raiting">
                    <label for="fechaInput">Valoración</label>
                </div>

                <div class="d-grid">
                    <button class="btn btn-primary mb-2" type="submit">Agregar</button>
                </div>
            </form>
        </div>

        <div class="row">
            <div class="col-md-6">
                <!-- Tabla para películas vistas -->
                <h2 class="text-center">Películas Vistas</h2>
                <div class="table-responsive">
                    <?php if (!empty($vistas)) : ?>
                        <table class="table text-center table-bordered table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">Título</th>
                                    <th scope="col">Valoración</th>
                                    <th scope="col">Eliminar</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                <?php foreach ($vistas as $key=> $value) : ?>
                                    <tr>
                                        <td><?= $value['nombre'] ?></td>
                                        <td><?= $value['raiting'] ?></td>
                                        <td>
                                            <form method="post" action="" id="form-vistas-<?= $key ?>" class="form-eliminar">
                                                <input type="hidden" name="eliminar_vista" value="<?= $key ?>">
                                                <button class="btn btn-danger eliminar-pelicula"  data-nombre="<?= $value['nombre'] ?>" data-form-id="form-vistas-<?= $key ?>" type="submit">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <p class="mensaje-vacio">No hay películas vistas.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-6">
                <!-- Tabla para películas pendientes -->
                <h2 class="text-center">Películas Pendientes</h2>
                <div class="table-responsive">
                    <?php if (!empty($pendientes)) : ?>
                        <table class="table text-center table-bordered table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">Título</th>
                                    <th scope="col">Valoración</th>
                                    <th scope="col">Eliminar</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                <?php foreach ($pendientes as $key=>$value) : ?>
                                    <tr>
                                        <td><?= $value['nombre'] ?></td>
                                        <td> Pendiente</td>
                                        <td>
                                            <form method="post" action="" id="form-pendientes-<?= $key ?>" class="form-eliminar">
                                                <input type="hidden" name="eliminar_pendiente" value="<?= $key ?>">
                                                <button class="btn btn-danger eliminar-pelicula"  data-nombre="<?= $value['nombre'] ?>" data-form-id="form-pendientes-<?= $key ?>" type="submit">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <p class="mensaje-vacio">No hay películas pendientes.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>