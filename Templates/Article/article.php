<?php
    
        $article = $_SESSION['listeArticles'][$articleCompteur];
        
        //tous les attributs de tuple courrant
        $idArticle = $article['idArticle'];
        $titreArticle = $article['titre'];
        $nbVues = $article['nbVues'];
        $likes = $article['likes'];
        $dateCreaArticle = $article['dateCreaArticle'];

    }
?>