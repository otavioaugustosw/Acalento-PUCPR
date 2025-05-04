<?php
include (ROOT . "/php/config/database_php.php");
include(ROOT . "/components/sidebars/sidebars.php");
include(ROOT . "/components/cards/cards.php");
include(ROOT . "/components/filter/filter.php");
include(ROOT . "/php/handlers/filter_php.php");
include(ROOT . "/models/donator_models_php.php");

$conn = connectDatabase();
$where = setWhere('campanha_doacao');
$campaigns = get_campaigns_where($conn, $where)

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/form-style.css">
    <link rel="stylesheet" href="css/cards.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/main-content.css">
    <title>Acalento | Campanhas</title>
</head>

<body>
<?php make_mobile_sidebar() ?>
<div class="d-flex flex-nowrap">
    <?php make_sidebar(); ?>
    <div class="main-content">
        <main class="px-5 row addScroll">
            <div class="container-fluid">
                <div class="mb-3">
                    <h2>Campanhas</h2>
                    <?php makeFilter() ?>
                    <div class="row row-cols-1 row-cols-sm-1 row-cols-md-1 row-cols-lg-2 row-cols-xl-3 row-cols-xxl-4 g-4">
                        <?php
                        if (!$campaigns) {
                            showError(7);
                        }
                        if ($campaigns->num_rows <= 0) {
                            echo '<h3>Nenhuma campanha cadastrada</h3>';
                        }
                        else {
                            render_campaigns_card($campaigns);
                        }
                        ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>

