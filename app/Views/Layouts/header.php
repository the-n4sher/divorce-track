<!DOCTYPE html>
<html lang="en" class="no-js">

<head>
    <?php if (getenv("CI_ENVIRONMENT") == "production") : ?>

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-154591878-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());

            gtag('config', 'UA-154591878-1');
        </script>

    <?php endif; ?>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>DivorceTrack - <?= $title; ?></title>

    <!-- Bootstrap Core CSS -->
    <link href="<?= site_url(); ?>css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <!--<link href="<?= site_url(); ?>css/modern-business.css" rel="stylesheet">-->
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
    <link href="<?= site_url(); ?>css/custom.css" rel="stylesheet">
    <link href="<?= site_url(); ?>css/navbar-fixed-side.css" rel="stylesheet" />
    <link href="<?= site_url(); ?>css/animate.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?= site_url(); ?>font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/jquery.metismenu/2.4.2/metisMenu.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->


    <!-- Bootstrap Core JavaScript -->

    <!--<script src="<?= site_url(); ?>js/modernizr-custom.js"></script>-->
    <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
    <script src="<?= site_url(); ?>js/bootstrap.min.js"></script>
    <script src="<?= site_url(); ?>js/postcodes.min.js"></script>
    <script src="<?= site_url(); ?>js/stages.js"></script>
    <script src="<?= site_url(); ?>js/consent_order.js"></script>
    <script>
        window.postcodeApiKey = <?= json_encode(getenv('postcode.apiKey')) ?>;
    </script>
    <script src="<?= site_url(); ?>js/postcode_finders.js"></script>
    <script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.jsdelivr.net/jquery.metismenu/2.4.2/metisMenu.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="<?= site_url(); ?>js/Tooltip.js"></script>



    <script>
        $(function() {
            $("#accordion").accordion();
        });
    </script>


    <script>
        function showLoader() {
            $('#loading').show();
        }
        $(document).ready(function() {
            $('#main_cases').DataTable({
                "aoColumnDefs": [{
                        'bSortable': false,
                        'aTargets': [6, 7]
                    },
                    {
                        "bSearchable": false,
                        "aTargets": [6, 7]
                    }
                ]
            });

            $('#my_cases').DataTable({
                "aoColumnDefs": [{
                        'bSortable': false,
                        'aTargets': [5, 6]
                    },
                    {
                        "bSearchable": false,
                        "aTargets": [5, 6]
                    }
                ]
            });

            $('.dpicker').datepicker({
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                yearRange: '1916:<?= date("Y"); ?>',
                dateFormat: 'dd-mm-yy'
            });

            $('#myaccount-Dob').datepicker({
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                yearRange: '1916:<?= date("Y"); ?>',
                dateFormat: 'dd-mm-yy'
            });

        });
    </script>

    <style>
        h2 {
            font-size: 30px;

        }

        h2,
        h3 {
            margin-bottom: 20px;
            text-align: center;
        }

        @media only screen and (max-width: 768px) {

            h2 {
                font-size: 30px;
            }

            .navbar-fixed-side {
                display: none;
            }

            .navbar-brand {
                margin-top: 0px;
            }

        }
    </style>


</head>