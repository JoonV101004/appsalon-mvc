use MVC\Router;
use Model\Cita;

public static function misCitas(Router $router) {
    session_start();
    isAuth();

    $usuarioId = $_SESSION['id'];

    // Traer citas del usuario
    $query = "SELECT * FROM citas WHERE usuarioid = ${usuarioId} AND estado != 'cancelada'";
    $citas = Cita::SQL($query);

    $router->render('cita/mis-citas', [
        'citas' => $citas
    ]);
}
