<!DOCTYPE html>
<?php 
   include_once "conf/default.inc.php";
   require_once "conf/Conexao.php";
   $title = "Lista de Carros";
   $procurar = isset($_POST["procurar"]) ? $_POST["procurar"] : "";
   $radio = isset($_POST["radio"]) ? $_POST["radio"] : 1;
   ini_set('display_errors', 0 );error_reporting(0);
    
?>
<html>
<head>
    <link rel="stylesheet" href="css/estilo.css">
    <meta charset="UTF-8">
    <title> <?php echo $title; ?> </title>
</head>
<body>
<?php include "menu.php" ?>
    <form method="post">
    <fieldset>
        <legend>Procurar Carros</legend>
        <input type="text"   name="procurar" id="procurar" size="37" value="<?php echo $procurar;?>">
        <input type="submit" name="acao"     id="acao" >
        <input type="radio"   name="radio" size="37" value="1" <?php if($radio == 1) echo 'checked'?>>Nome
        <input type="radio"   name="radio" size="37" value="2" <?php if($radio == 2) echo 'checked'?>>Valor
        <input type="radio"   name="radio" size="37" value="3" <?php if($radio == 3) echo 'checked'?>>Km

        <br><br>
        <table>
	    <tr><td><b>Id|</b></td>
            <td><b>|Nome|</b></td>
            <td><b>|Valor em R$|</b></td>
            <td><b>|Km|</b></td>
            <td><b>|Data de Fabricação|</b></td>
            <td><b>|Anos de Fabricação|</b></td>
            <td><b>|Média de Km/Ano|</b></td>
            <td><b>|Valor com desconto|</b></td>
        </tr>
        <?php
            $pdo = Conexao::getInstance();
           
            if ($radio == 1) {$consulta = $pdo->query("SELECT * FROM carro
                WHERE nome LIKE '$procurar%' 
                ORDER BY nome");
            }elseif ($radio == 2 && $procurar == ""){
                echo "Preencha o campo de pesquisa";
            }elseif($radio == 2){$consulta = $pdo->query("SELECT * FROM carro
                WHERE valor <= $procurar 
                ORDER BY valor");
            }elseif ($radio == 3 && $procurar == ""){
                echo "Preencha o campo de pesquisa";
            }else {$consulta = $pdo->query("SELECT * FROM carro
                WHERE km <= $procurar 
                ORDER BY km");
            }
            
            while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) { 
                $fab = date("Y",strtotime($linha['datafabricacao']));
                $hoje = date("Y");
                $ano = $hoje - $fab;
                $mediakm = $linha['km'] / $ano;
            if ($linha['km'] > 100000){
                $desconto = $linha['valor'] - ($linha['valor'] * 10 / 100);
            }else{
                $desconto = $linha['valor'];
            }
            if ($ano > 10){
                $desconto2 = $desconto - ($linha['valor'] * 10 / 100);
            }else{
                $desconto2 = $desconto;
            }

            if ($desconto2 < $linha['valor']){
                $class = "red";
            }
        ?>
	    <tr><td><?php echo $linha['id'];?></td>
            <td><?php echo $linha['nome'];?></td>
            <td><?php echo number_format ($linha['valor'], 2, ',', '.');?></td>
            <td><?php echo number_format ($linha['km'], 1, ',', '.');?></td>
            <td><?php echo date("d/m/Y",strtotime($linha['datafabricacao']));?></td>
            <td><?php echo $ano;?></td>
            <td><?php echo number_format ($mediakm, 2, ',', '.');?></td>
            <td class="<?php echo $class;?>"><?php echo number_format ($desconto2, 2, ',', '.');?></td>
	    </tr>
            <?php } ?>       
        </table>
    </fieldset>
    </form>
    <?php  ?>

</body>
</html>