<?php
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_CASE => PDO::CASE_NATURAL,
    PDO::ATTR_ORACLE_NULLS => PDO::NULL_EMPTY_STRING
];
require_once 'Config.php';
$p = new Config("CRUD", "localhost", "root", "", $options);
$erro_r = "";
$erro_l = "";
//' or ''=''
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Raleway&display=swap');

        * {
            font-family: 'Raleway', sans-serif;
            padding: 0;
            margin: 0;

        }

        label,
        input {
            display: block;
            line-height: 30px;
            height: 30px;
            width: 90%;
            outline: none;
            font-size: 13px;
        }

        form {
            width: 330px;
            padding: 20px;
            margin: 30px auto;
        }

        input[type="sumbit"] {
            margin-top: 10px;
            cursor: pointer;
        }

        #left {
            width: 45%;
            background-color: pink;
            float: left;
            height: 500px;
            background-color: gray;
        }

        #left h2 {
            text-align: center;
        }

        #right {
            width: 55%;
            float: right;
            height: 500px;
            max-width: 100%;
            overflow: scroll;
            background-color: pink;

        }

        #date_show {
            background-color: pink;
            width: 90%;
            margin: 30px auto;
        }

        #date_show tr {
            line-height: 50px;

        }


        #date_show a {
            background-color: white;
            color: black;
            padding: 5px;
            margin: 5px;
            text-decoration: none;
        }

        #title {
            font-weight: bold;
            background-color: rgba(0, 0, 0, .6);
            color: white;

        }

        .pop {
            display: flex;
            border: 3px black solid;
            text-align: center;
            align-items: center;
            justify-content: center;
            background-color: white;
        }

        .pop .right {
            color: green;
            font-weight: 600;

        }

        .wrong {
            color: red;
            font-weight: 600;
        }

        .warning {

            display: flex;
            font-weight: 500;
            width: 100%;
            height: 50px;
            background-color: white;
            align-items: center;
            justify-content: center;



        }

        .warning .bi-exclamation-triangle {
            height: 50px;
        }
    </style>
</head>

<body>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //$nome = preg_replace('/[^[:alpha:]_]/', '', filter_input(INPUT_POST, 'nome')); //evitando inserção de comandos
        $nome = filter_input(INPUT_POST, 'nome');
        if (isset($_GET['id_update']) && !empty($_GET['id_update'])) {
            $id_up = addslashes($_GET['id_update']);

            $telefone = addslashes($_POST['telefone']);
            $email = addslashes($_POST['email']);


            if (!empty($nome) && !empty($telefone) && !empty($email)) {
                //atualizar
                if (!$p->verication_email($email)) {
                    $erro_l = "<div class='pop'> <p class='wrong'>Email  já cadastrado</p></div>";
                } else {
                    $erro_l = "<div class='pop'> <p class='right'>Alterado com Sucesso</p></div>>";
                    header("location:index.php");
                    $p->update_date($id_up, $nome, $telefone, $email);
                }
            } else {
                $erro_l = "<div class='pop'> <p class='wrong'>Preencha todos os dados</p></div>";
            }
        } else {

            $telefone = addslashes($_POST['telefone']);
            $email = addslashes($_POST['email']);

            if (!empty($nome) && !empty($telefone) && !empty($email)) {
                //cadastrar
                if (!$p->verication_email($email)) {
                    $erro_l = "<div class='pop'> <p class='wrong'>Email  já cadastrado</p></div>";
                } else {
                    $p->cadastrar($nome, $telefone, $email);
                    header("location:index.php");
                }
            } else {
                $erro_l = "<div class='pop'> <p class='wrong'>Preencha todos os dados</p></div>";
            }
        }
    }
    ?>

    <?php
    if (isset($_GET['id_update'])) {
        $id = addslashes($_GET['id_update']);
        $res = $p->select_one($id);
    }
    ?>
    <section id="left">
        <form method="POST">
            <h2>CADASTRAR PESSOA</h2>
            <?php echo ($erro_l . $erro_r);
            ?>
            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome" value="<?php if (isset($res)) {
                                                                echo $res['nome'];
                                                            } ?>">
            <label for="telefone">Telefone</label>
            <input type="text" name="telefone" id="telefone" value="<?php if (isset($res)) {
                                                                        echo $res['telefone'];
                                                                    } ?>">
            <label for="email">Email</label>
            <input type="text" name="email" id="email" value="<?php if (isset($res)) {
                                                                    echo $res['email'];
                                                                } ?>">
            <br>
            <input type="submit" value="<?php if (isset($res)) {
                                            echo "Atualizar";
                                        } else {
                                            echo "Cadastrar";
                                        } ?>">
        </form>
    </section>
    <section id="right">
        <table id="date_show">
            <tr id="title">
                <td>Nome</td>
                <td>Telefone</td>
                <td colspan="2">Email</td>
            </tr>
            <?php
            $dados = $p->buscarDados();
            echo "<pre>";
            if (count($dados) > 0) {
                for ($i = 0; $i < count($dados); $i++) {
                    echo ("<tr>");
                    foreach ($dados[$i] as $k => $v) {
                        if ($k != 'id') {
                            echo ("<td>" . $v . "</td>");
                        }
                    }
            ?>
                    <td>
                        <!-- <?php //echo $dados[$i]['id'] 
                                ?> -->
                        <a href='index.php?id_update=<?php echo $dados[$i]['id'] ?>'>Editar</a>
                        <a href='index.php?id=<?php echo $dados[$i]['id'] ?>'>Excluir</a>
                    </td>
            <?php
                    echo '</tr>';
                }
            } else {
                echo '<div class="warning"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
                <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z"/>
                <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z"/>
              </svg><span>Sem pessoas cadastradas</span></div>';
            }

            echo "</pre>";
            ?>

        </table>
    </section>

</body>
<script>
    $('telefone').mask('(99) 99999-9999');
</script>

</html>

<?php
if (isset($_GET['id'])) {
    $id = addslashes($_GET['id']);
    $p->excluir($id);
    header("location:index.php");
}

?>