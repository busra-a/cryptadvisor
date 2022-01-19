<?php
require_once "./connexion.php";

class ModeleArticle extends Connexion {

    public function getListeArticles($page){
        if($page < 1)
            $page = 1;
        $listeDebut = ($page-1)*5;
        $req = self::$bdd->prepare("SELECT idArticle, titre, contenuArticle, nbVues, likes, dateCreaArticle FROM Article LIMIT 5 OFFSET ?");
        $req->bindParam(1, $listeDebut, PDO::PARAM_INT);
        $req->execute();
        $listeArticles = $req->fetchAll(PDO::FETCH_ASSOC);

        $req = self::$bdd->prepare("SELECT count(*) as nbArticles FROM Article;");
        $req->execute();
        $nbArticles = $req->fetch(PDO::FETCH_ASSOC)['nbArticles'];

        $listeArticles['nbArticles'] = $nbArticles;

        if(isset($_SESSION['pseudo'])){
            $req = self::$bdd->prepare("SELECT idUtilisateur FROM Utilisateur WHERE pseudo = ?");
            $req->bindParam(1, $_SESSION['pseudo'], PDO::PARAM_STR);
            $req->execute();
            $idUtilisateur = $req->fetch(PDO::FETCH_ASSOC);
        }
        $listeArticles['page']=$page;
        if(isset($_SESSION['pseudo'])){
            $req = self::$bdd->prepare("SELECT pseudoUtilisateur, titre, contenu, dateCreation FROM demandeCreationArticle");
            $req->execute();
            $listeDemandes = $req->fetchAll(PDO::FETCH_ASSOC);
            $_SESSION['demandesCreationArticle'] = $listeDemandes;
        }
        return $listeArticles;
    }

    public function getArticle($id){
        $req = self::$bdd->prepare("UPDATE Article SET nbVues = nbVues+1 where idArticle = ?");
        $req->bindParam(1, $id, PDO::PARAM_INT);
        $req->execute();
        $req = self::$bdd->prepare("SELECT * FROM Article where idArticle = ?");
        $req->bindParam(1, $id, PDO::PARAM_INT);
        $req->execute();
        $article = $req->fetch(PDO::FETCH_ASSOC);
        return $article;
    }

    public function posterMessage($idArticle, $pseudo, $message){
        $req = self::$bdd->prepare("INSERT INTO Message (pseudoUtilisateur, texte, datePublication, idArticle) VALUES (:pseudo, :texte, now(), :idArticle); ");
        $req->bindParam(':idArticle', $idArticle, PDO::PARAM_INT);
        $req->bindParam(':pseudo', $pseudo);
        $req->bindParam(':texte', $message);
        $req->execute();
    }

    public function getAllComments($idArticle){
        $req = self::$bdd->prepare("SELECT * from Message where idArticle = :idArticle;");
        $req->bindParam(':idArticle', $idArticle);
        $req->execute();
        $res = $req->fetchAll();
        return $res;
    }
}