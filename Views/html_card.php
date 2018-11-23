<?php
/** Toutes les données seront récupérer via la BDD et Session 
 * Donc cela est juste  pour le test */ 
$name_card='Les Dents de la Mer';
$picture_card ='../Uploads/cards/cuisine/picture_etchebest.png';
$card_description='Je suis la description du requin des dents de la mer ';
$mana_card= 7;
$pa_card=8;
$hp_card=6;
$quote_card='Je suis une citation';
$type_of_card='../Uploads/type_cards/back_crea.png';


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="../assets/css/font-face.css">
        <link rel="stylesheet" href="../assets/css/main.css">
        <link rel="stylesheet" href="../assets/css/card.css">
        <title>Code pour Carte</title>
    </head>
    <body>
    <main>
    <div class="board" >

    </div>
        <!-- Juste a changer le font-size dans le HTml pour rendre la carte Responsive -->
        <div class="carte">
        <div class="back_card no_event">
                <img class="picture_card" src='<?php  echo $picture_card; ?>' alt='' />
                <img class="type_of_card " src='<?php  echo $type_of_card; ?>' alt='' />
            
                <p class='name_card orange_color calango'> <?php  echo $name_card; ?> </p>  
                <p class="hp_card orange_color calango"> <?php  echo $hp_card; ?> </p>
                <p class="pa_card orange_color calango"> <?php  echo $pa_card; ?> </p>
                <p class="mana_card orange_color calango"> <?php  echo $mana_card; ?> </p> 
                <q class="quote_card orange_color"> <?php  echo $quote_card; ?> </q>
                <p class="descrip_card orange_color"> <?php  echo $card_description; ?> </p>       
        </div>
        </div>
        <div class="carte">
        <div class="back_card no_event">
                <img class="picture_card" src='<?php  echo $picture_card; ?>' alt='' />
                <img class="type_of_card" src='<?php  echo $type_of_card; ?>' alt='' />
            
                <p class='name_card orange_color calango'> <?php  echo $name_card; ?> </p>        
                <p  class="hp_card orange_color calango"> <?php  echo $hp_card; ?> </p>
                <p  class="pa_card orange_color calango"> <?php  echo $pa_card; ?> </p>
                <p class="mana_card orange_color calango"> <?php  echo $mana_card; ?> </p> 
                <q class="quote_card orange_color"> <?php  echo $quote_card; ?> </q>
                <p class="descrip_card orange_color"> <?php  echo $card_description; ?> </p>       
        </div>
        </div>

 </main>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        
    (function(){
        var dndHandler = {

            draggedElement: null, // Propriété pointant vers l'élément en cours de déplacement

            applyDragEvents: function(element) {
                
                element.draggable = true;

                var dndHandler = this; // Cette variable est nécessaire pour que l'événement « dragstart » accède facilement au namespace « dndHandler »

                element.addEventListener('dragstart', function(e) {
                    dndHandler.draggedElement = e.target; // On sauvegarde l'élément en cours de déplacement

                    e.dataTransfer.setData('text/plain', ''); // Nécessaire pour Firefox
                },false);  
            },

            applyDropEvents: function(dropper) {
            
                dropper.addEventListener('dragover', function(e) {
                    e.preventDefault(); // On autorise le drop d'éléments
                    this.className = 'board drop_hover'; // Et on applique le style adéquat à notre zone de drop quand un élément la survole
                },false);
            
                dropper.addEventListener('dragleave', function() {
                    this.className = 'board'; // On revient au style de base lorsque l'élément quitte la zone de drop
                });
            
                var dndHandler=this;

                dropper.addEventListener('drop', function(e) {

                    var target = e.target,
                    draggedElement = dndHandler.draggedElement, // Récupération de l'élément concerné
                    clonedElement = draggedElement.cloneNode(true); // On créé immédiatement le clone de cet élément
                    while (target.className.indexOf('board') == -1) { // Cette boucle permet de remonter jusqu'à la zone de drop parente
                    target = target.parentNode;
                    }
                    target.className = 'board'; // Application du style par défaut
                    clonedElement = target.appendChild(clonedElement); // Ajout de l'élément cloné à la zone de drop actuelle
                    dndHandler.applyDragEvents(clonedElement); // Nouvelle application des événements qui ont été perdus lors du cloneNode()
                    draggedElement.parentNode.removeChild(draggedElement); // Suppression de l'élément d'origine
                });
            }
        };

        var elements = document.querySelectorAll('.carte'),
            elementsLen = elements.length;

        for (var i = 0 ; i < elementsLen ; i++) {
            dndHandler.applyDragEvents(elements[i]); // Application des paramètres nécessaires aux éléments déplaçables
        }

            var droppers = document.querySelectorAll('.board'),
            droppersLen = droppers.length;

        for (var i = 0 ; i < droppersLen ; i++) {
            dndHandler.applyDropEvents(droppers[i]); // Application des événements nécessaires aux zones de drop
        }

    })();
    })

</script>

 

    </body>
</html>




