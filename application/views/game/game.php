<?php

$hash = $_GET['hash'];

?>

<h1>GAME</h1>
<p>Game identifier: <?= $hash ?></p>

<div id="game">

    <p>Your opponent: <?= $opponent ?></p>

    <?php
    foreach($field as $row){
        foreach($row as $el){
            echo $el;
        }
    }
    ?>
    
    <form action="" method="POST" id="ajax_form">
        <?php 
        $value = 0;
        for($i = 0; $i < count($field); $i++):
        ?>
            <div class="row">
                <?php
                for($j = 0; $j < count($field); $j++):
                    $symbol = "&nbsp;";
                    $disabled = '';
                    if($field[$i][$j] == 1):
                        $symbol = 'O';
                        $disabled = 'disabled';
                    elseif($field[$i][$j] == 2):
                        $symbol = 'X';
                        $disabled = 'disabled';
                    endif;
                ?>
                    <button <?= $disabled ?> type="button" class="turn_btn" name="turn" value="<?= $value ?>"><?= $symbol ?></button>
                <?php
                    $value++;
                endfor;
                ?>
            </div>
        <?php
        endfor;
        ?>
        <input type="hidden" value="<?= $hash ?>" name="hash">
        <input id="turn" type="hidden" value="" name="turn">
    </form>
</div>